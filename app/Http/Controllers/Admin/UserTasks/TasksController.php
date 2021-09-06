<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin\UserTasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserTasks\RequestCreateUserTask;
use App\Http\Requests\UserTasks\RequestUpdateUserTask;
use App\Models\Currency;
use App\Models\PaymentSystem;
use App\Models\UserTasks\TaskActions;
use App\Models\UserTasks\TaskCoefficients;
use App\Models\UserTasks\Tasks;
use App\Models\UserTasks\TaskScopes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

/**
 * Class TasksController
 * @package App\Http\Controllers\Admin\UserTasks
 */
class TasksController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.user-tasks.tasks.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function datatable()
    {
        $events = Tasks::select('*');

        return DataTables::of($events)
            ->make(true);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.user-tasks.tasks.create');
    }

    /**
     * @param RequestCreateUserTask $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function store(RequestCreateUserTask $request)
    {
//        $task                           = new Tasks();
//        $task->title                    = (string) $request->title;
//        $task->description              = (string) $request->description;
//        $task->reward_amount            = (float) $request->reward_amount;
//        $task->execution_qty            = (integer) $request->execution_qty;
//        $task->comments                 = $request->comments;
////        $task->reward_payment_system_id = null;
////        $task->reward_currency_id       = null;
//        $task->category_id                 = $request->category_id;
        $data = $request->all();
        $data['user_id'] = \Auth::id();
        $task = Tasks::create($data);

//        $task->deadline                 = Carbon::parse($request->deadline)->toDateTimeString();

//        if (preg_match('/\:/', $request->reward_payment_system)) {
//            $parsePaymentSystem = explode(':', $request->reward_payment_system);
//
//            if (isset($parsePaymentSystem[0]) && isset($parsePaymentSystem[1])) {
//                /** @var PaymentSystem $paymentSystem */
//                $paymentSystem = PaymentSystem::find($parsePaymentSystem[0]);
//                /** @var Currency $currency */
//                $currency      = Currency::find($parsePaymentSystem[1]);
//
//                if (null !== $paymentSystem && null !== $currency) {
//                    $task->reward_payment_system_id = $paymentSystem->id;
//                    $task->reward_currency_id       = $currency->id;
//                }
//            }
//        }
//        $task->save();

        $coefficients = $request->coefficients;

        if (isset($coefficients['min_minutes']) && isset($coefficients['max_minutes']) && isset($coefficients['reward_coefficient'])) {
            for ($i = 0; $i<=count($coefficients['min_minutes']); $i++) {
                if (!isset($coefficients['min_minutes'][$i]) || !isset($coefficients['max_minutes'][$i]) || !isset($coefficients['reward_coefficient'][$i])) {
                    continue;
                }

                $min_minutes        = $coefficients['min_minutes'][$i];
                $max_minutes        = $coefficients['max_minutes'][$i];
                $reward_coefficient = $coefficients['reward_coefficient'][$i];

                TaskCoefficients::create([
                    'task_id'               => $task->id,
                    'min_minutes'           => (int) $min_minutes,
                    'max_minutes'           => (int) $max_minutes,
                    'reward_coefficient'    => (int) $reward_coefficient,
                ]);
            }
        }

        $scopes = $request->scope;

        if (!empty($scopes) && count($scopes) > 0) {
            foreach ($scopes as $scopeKey => $sourceAddress) {
                if (empty($sourceAddress)) {
                    continue;
                }

                /** @var TaskScopes $scopeInfo */
                $scopeInfo = TaskScopes::where('key', $scopeKey)->first();

                if (null === $scopeInfo) {
                    continue;
                }

                $taskAction                 = new TaskActions();
                $taskAction->task_id        = $task->id;
                $taskAction->task_scope_id  = $scopeInfo->id;
                $taskAction->source_address = $sourceAddress;
                $taskAction->save();
            }
        }

        return redirect(route('admin.user-tasks.tasks.index'))->with('success', __('Task was registered'))->withInput();
    }

    /**
     * @param Tasks $task
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Tasks $task)
    {
        return view('admin.user-tasks.tasks.edit',[
            'task' => $task,
        ]);
    }

    /**
     * @param RequestUpdateUserTask $request
     * @param Tasks $task
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(RequestUpdateUserTask $request, Tasks $task)
    {
        $task->title                    = (string) $request->title;
        $task->description              = (string) $request->description;
        $task->reward_amount            = (float) $request->reward_amount;
        $task->reward_payment_system_id = null;
        $task->reward_currency_id       = null;
        $task->category                 = $request->category;
        $task->social_category          = $request->social_category;
        $task->deadline                 = Carbon::parse($request->deadline)->toDateTimeString();

        if (preg_match('/\:/', $request->reward_payment_system)) {
            $parsePaymentSystem = explode(':', $request->reward_payment_system);

            if (isset($parsePaymentSystem[0]) && isset($parsePaymentSystem[1])) {
                /** @var PaymentSystem $paymentSystem */
                $paymentSystem = PaymentSystem::find($parsePaymentSystem[0]);
                /** @var Currency $currency */
                $currency      = Currency::find($parsePaymentSystem[1]);

                if (null !== $paymentSystem && null !== $currency) {
                    $task->reward_payment_system_id = $paymentSystem->id;
                    $task->reward_currency_id       = $currency->id;
                }
            }
        }
        $task->save();

        return redirect(route('admin.user-tasks.tasks.edit', $task->id))->with('success', __('Task was updated'))->withInput();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $task = Tasks::find($request->id);

        if (null === $task) {
            return back()->with('error', __('Task not found.'))->withInput();
        }

        $task->delete();
        return redirect(route('admin.user-tasks.tasks.index'))->with('success', __('Task was deleted'))->withInput();
    }
}
