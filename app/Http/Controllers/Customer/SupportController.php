<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestSupport;
use App\Mail\NotificationMail;
use App\Mail\SupportMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('customer.support');
    }

    /**
     * @param RequestSupport $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function send(RequestSupport $request)
    {
        $cacheKey = 'sent_support_emails_from_' . $_SERVER['REMOTE_ADDR'];
        $counter = cache()->get($cacheKey);
        $counter = $counter > 0 ? $counter + 1 : 0;

        if ($counter > config('mail.spam_protection.limit')) {
            return back()->with('error', trans('main.emails.spam_protection'));
        }

        $email = htmlspecialchars($request->email);
        $text = htmlspecialchars($request->text);

        User::notifyAdmins('support_form', [
            'sender_email' => $email,
            'sender_text'  => $text,
        ]);

        cache()->put($cacheKey, $counter, now()->addHours(config('mail.spam_protection.lifetime')));
        return back()->with('success', trans('main.emails.sent_successfully'));
    }
}
