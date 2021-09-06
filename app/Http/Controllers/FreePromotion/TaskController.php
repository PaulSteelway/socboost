<?php


namespace App\Http\Controllers\FreePromotion;

use App\Http\Managers\FreePromotion\InstagramManager;
use App\Http\Managers\FreePromotion\TaskManager;
use App\Models\Category;
use App\Models\User;
use App\Models\UserTasks\Tasks;
use App\Models\UserTasks\UserTasks;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;

class TaskController
{
    public function index()
    {
        return view('free_promotion.index');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tasklist()
    {
        /** @var User $user */
        $user = Auth::user();
        $userTasksId = UserTasks::select('task_id')->where('user_id', Auth::id())->whereIn('status', [2, 3])->get();
        $service_id = request('service_id');
        $type = request('type');
        if ($service_id && request('all')) {
            $category = Category::find($service_id);
            $tasksQuery = $category->allChildCategoriesTasks()
                ->select('tasks.*');
        } elseif ($service_id) {
            $tasksQuery = Tasks::select('tasks.*')
                ->where('category_id', $service_id);
        } else {
            $tasksQuery = Tasks::select('tasks.*');
        }
        if (!$type) {
            $tasksQuery->join('wallets', 'wallets.user_id', '=', 'tasks.user_id')
                ->join('payment_systems', function ($join) {
                    $join->on('payment_systems.id', '=', 'wallets.payment_system_id')
                        ->where('payment_systems.code', '=', 'freePromotion');
                })
                ->where('tasks.user_id', '!=', Auth::id())
                ->whereRaw('wallets.balance >= (tasks.reward_amount * 2)')
                ->whereRaw('tasks.execution_qty > tasks.executed_qty')
                ->whereRaw('tasks.execution_qty > (SELECT COUNT(ut.id) FROM user_tasks ut WHERE ut.task_id = tasks.id)');
        } else {
            $tasksQuery->where('tasks.user_id', '=', Auth::id());
        }
        $tasksQuery->leftJoin('user_tasks', function ($join) use ($user) {
            $join->on('user_tasks.task_id', '=', 'tasks.id')
                ->where('user_tasks.user_id', '=', $user->id)
                ->where('user_tasks.status', '=', 1);
        })->whereNull('user_tasks.id');

        $tasksQuery->whereNotIn('tasks.id', $userTasksId);

        $tasks = $tasksQuery->get();
        return view('free_promotion.all_tasks-list')
            ->with('service_name', \request('service_name') ? __('Task for') . ' ' . \request('service_name') : 'Все задания')
            ->with('tasks', $tasks);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_by_service(Request $request)
    {
        if (!$request->service_id) {
            $html = '<option value=""></option>';
        } else {
            $html = '';
            $categories = Category::where('parent_id', $request->service_id)->where('free_promotion', 1)->get();
            foreach ($categories as $category) {
                $html .= '<option value="' . $category->id . '">' . $category->name_ru . '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_total_rewards(Request $request)
    {
        // nit:Daan need refactor
        if (empty($request->reward) || empty($request->qty)) {
            $rewards = 0;
        } else {
            $rewards = 0;
            if ($request->reward !== 'undefined' || $request->qty !== 'undefined') {
                $rewards = TaskManager::getOneRewardWithCommission($request->reward) * $request->qty;
            }
        }
        return response()->json(['rewards' => $rewards]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check_task_validity(Request $request)
    {
        $valid = false;
        if (!empty($request->task_id)) {
            /** @var Tasks $task */
            $task = Tasks::find($request->task_id);
            if ($task instanceof Tasks) {
                $executedCount = $task->userTasks()->count();
                if (($task->execution_qty > $task->executed_qty) && ($task->execution_qty > $executedCount)) {
                    /** @var Wallet $wallet */
                    $wallet = $task->user->getFreePromotionWallet();
                    if ($wallet->balance >= TaskManager::getOneRewardWithCommission($task->reward_amount)) {
                        $valid = true;
                    }
                }
            }
        }
        return response()->json(['valid' => $valid]);
    }


    public function create()
    {
        if (request()->social_id || request()->status_id) {
            $taskQuery = $tasks = Tasks::where('user_id', Auth::id());

            if (request()->social_id) {
                $subCategories = Category::where('parent_id', request()->social_id)->pluck('id', 'id');
                $taskQuery->whereIn('category_id', $subCategories);
            }
            if (request()->status_id) {
                switch (request()->status_id) {
                    case "inprocess":
                        $taskQuery->where('execution_qty', '>', '`executed_qty`')->where('status_id', 0);
                        break;
                    case "complete":
                        $taskQuery->where('execution_qty', '<=', '`executed_qty`');
                        break;
                    case "stopped":
                        $taskQuery->where('status_id', 1);
                        break;
                }
            }
            $tasks = $taskQuery->get();
        } else {
            $tasks = Tasks::where('user_id', Auth::id())->get();
        }
        return view('free_promotion.tasks.create')->with('tasks', $tasks);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'social_id' => 'required',
                'category_id' => 'required',
                'title' => 'required',
                'link' => 'required',
                'reward_amount' => 'required',
                'execution_qty' => 'required'
            ]);

            $data = $request->all();

            /** @var User $user */
            $user = Auth::user();
            if (TaskManager::checkAbilityCreating($user, $data['reward_amount'], $data['execution_qty'])) {
                $data['user_id'] = $user->id;
                Tasks::create($data);
                return response()->json(['success' => true], 200);
            } else {
                throw new \Exception(__('Недостаточное количество баллов на балансе'));
            }
        } catch (ValidationException $e) {
            $result = [];
            foreach ($e->validator->errors()->getMessages() as $errors) {
                foreach ($errors as $error) {
                    $result[] = $error;
                }
            }
            session()->flash('error', implode('<br>', $result));
            return response()->json(['success' => false, 'error' => $e->validator->errors()->getMessages()], 412);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function blacklist(Request $request)
    {

    }

    public function hideTask(Request $request)
    {
        $user_task = UserTasks::create(
            [
                'user_id' => Auth::id(),
                'task_id' => $request->task_id,
                'status' => 3,
            ]
        );
        $user_task->save();
        return view('free_promotion.index');
    }

    public function update()
    {
        return view('free_promotion.index');

    }

    public function delete()
    {
        return view('free_promotion.index');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verificationImplementation(Request $request)
    {
        try {
            $result = false;

            /** @var Tasks $task */
            $task = Tasks::find($request->get('task_id'));
            if ($task instanceof Tasks) {
                /** @var User $user */
                $user = Auth::user();

                if (strcasecmp($task->category->parent->name_en, 'Instagram') == 0) {
                    if (strcasecmp($task->category->name_en, 'Likes') == 0) {
                        $result = InstagramManager::checkInstagramLikeAction($user->userSocialProfile->instagram_username, $task->link);
                    }
                    if (strcasecmp($task->category->name_en, 'Followers') == 0) {
                        $result = InstagramManager::checkInstagramFollowerAction($user, $task);
                    }
                }

                if ($result) {
                    TaskManager::markUserTaskAsCompleted($user, $task);
                }
            }
            return response()->json(['success' => true, 'result' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
