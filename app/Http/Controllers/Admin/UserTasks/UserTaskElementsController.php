<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin\UserTasks;

use App\Http\Controllers\Controller;
use App\Models\UserTasks\UserTaskActions;
use Yajra\DataTables\DataTables;

/**
 * Class UserTaskElementsController
 * @package App\Http\Controllers\Admin\UserTasks
 */
class UserTaskElementsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.user-tasks.users-tasks-elements.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function datatable()
    {
        $events = UserTaskActions::select('*');

        return DataTables::of($events)
            ->make(true);
    }
}
