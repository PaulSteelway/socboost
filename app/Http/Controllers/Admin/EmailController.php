<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\NewsMail;
use App\Models\User;
use Illuminate\Http\Request;


/**
 * Class BackupController
 * @package App\Http\Controllers\Admin
 */
class EmailController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.email.index');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function send_emails(Request $request)
    {

        SendEmailJob::dispatch($request->toArray())->onQueue(getSupervisorName() . '-emails')->delay(0);

        return view('admin.email.index');
    }
}
