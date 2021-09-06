<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Console\Commands\Automatic\ScriptCheckerCommand;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\UsersSocialMeta;
use Yajra\Datatables\Datatables;

/**
 * Class SocialMetaController
 * @package App\Http\Controllers\Admin
 */
class SocialMetaController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.social_meta.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function dataTable()
    {
        $socialMeta = UsersSocialMeta::with('user')
            ->select('users_social_meta.*')->join('users', function($query){
                return $query->on('users.id', '=', 'users_social_meta.user_id');
            });

        return Datatables::of($socialMeta)
            ->addColumn('user_name', function ($meta) {
                return $meta->user->name;
            })
            ->make(true);
    }
}
