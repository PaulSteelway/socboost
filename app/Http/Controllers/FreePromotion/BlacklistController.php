<?php


namespace App\Http\Controllers\FreePromotion;


use App\Models\UserTasks\Tasks;
use App\Models\UserTasks\UserTasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlacklistController
{
    public function index()
    {
       $blacklisted_tasks = Tasks::where('tasks.user_id', '!=', \Auth::id())
            ->join('user_tasks', 'tasks.id', 'user_tasks.task_id')
            ->where('user_tasks.user_id', Auth::id())
            ->where('user_tasks.status', 2)
            ->get();
        return view('free_promotion.black_list')
            ->with('service_name', \request('service_name') ? __('Task for') . ' ' . \request('service_name') : 'Все задания')
            ->with('tasks', $blacklisted_tasks);
    }


    public function addToBlacklist(Request $request)
    {
        $data = $request->all();
        $user_task = UserTasks::create(
            [
                'user_id' => Auth::id(),
                'task_id' => $data['task_id'],
                'details' => $data["complain"],
                'status' => 2,
            ]
        );
        $user_task->save();
        return redirect()->back();
    }
}
