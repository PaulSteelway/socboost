<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin\UserTasks;

use App\Http\Controllers\Controller;
use App\Models\UserTasks\TaskScopes;
use Yajra\DataTables\DataTables;

/**
 * Class AvailableElementsController
 * @package App\Http\Controllers\Admin\UserTasks
 */
class AvailableElementsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.user-tasks.available-elements.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function datatable()
    {
        $events = TaskScopes::select('*');

        return DataTables::of($events)
            ->make(true);
    }
}
