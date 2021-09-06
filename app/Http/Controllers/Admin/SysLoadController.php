<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SysLoad;

/**
 * Class SysLoadController
 * @package App\Http\Controllers\Admin
 */
class SysLoadController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sysLoads = SysLoad::where('created_at', '>', now()->subHours(env('SYS_LOAD_SUB_HOURS', 3)))
            ->orderBy('created_at')
            ->get();

        return view('admin.sys_load.index', [
            'sysLoads' => $sysLoads,
        ]);
    }
}
