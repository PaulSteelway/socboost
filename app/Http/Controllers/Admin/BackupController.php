<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

/**
 * Class BackupController
 * @package App\Http\Controllers\Admin
 */
class BackupController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $files = Storage::files($path = preg_replace('/[^a-zA-Z0-9.]/', '-', env('APP_NAME', $_SERVER['HTTP_HOST'])));

        return view('admin.backup.index', [
            'files' => $files,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function backupDB()
    {
        Artisan::call('backup:run', ['--only-db' => true]);
        return back()->with('success', __('DB backup was created'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function backupFiles()
    {
        Artisan::call('backup:run', ['--only-files' => true]);
        return back()->with('success', __('Files backup was created'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function backupAll()
    {
        Artisan::call('backup:run');
        return back()->with('success', __('Full backup was created'));
    }

    /**
     * @param $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($file)
    {
        Storage::delete($file);
        return back()->with('success', __('Backup has been removed'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function download(Request $request)
    {
        if (!$request->has('file')) {
            return back()->with('error', __('File not found'));
        }
        User::notifyAdminsViaNotificationBot('notification_bot.backup_downloading', [
            'requester' => \Auth::user(),
        ]);
        return Storage::download($request->file);
    }
}
