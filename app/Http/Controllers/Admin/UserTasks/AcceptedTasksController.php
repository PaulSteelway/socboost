<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin\UserTasks;

use App\Http\Controllers\Controller;
use App\Models\UserTasks\UserTasks;
use Yajra\DataTables\DataTables;

/**
 * Class AcceptedTasksController
 * @package App\Http\Controllers\Admin\UserTasks
 */
class AcceptedTasksController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.user-tasks.accepted-tasks.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function datatable()
    {
        $events = UserTasks::select('*');

        return DataTables::of($events)
            ->make(true);
    }
}
