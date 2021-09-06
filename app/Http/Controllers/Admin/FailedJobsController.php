<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

/**
 * Class FailedJobsController
 * @package App\Http\Controllers\Admin
 */
class FailedJobsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.jobs.failed.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function dataTable()
    {
        $jobs = DB::table('failed_jobs')->get();

        return DataTables::of($jobs)
            ->addColumn('deposit_id', function ($job) {
                $txt = $job->payload;
                $re1 = '.*?';    # Non-greedy match on filler
                $re2 = Constants::UUID_REGEX;

                if (preg_match_all("/" . $re1 . $re2 . "/is", $txt, $matches)) {
                    return $matches[1][0];
                }
            })
            ->addColumn('type', function ($job) {
                return substr(explode(',', $job->payload)[0], 27, -1);
            })
            ->make(true);
    }
}
