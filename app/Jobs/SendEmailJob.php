<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Jobs;

use App\Mail\NewsMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendLogsJob
 * @package App\Jobs
 */
class SendEmailJob implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var array $email_data */
    protected $email_data;

    protected $codesRU = ['RU', 'UA', 'BY', 'KZ', 'MD', 'AZ', 'AM', 'TJ', 'UZ', 'ru', 'ua', 'by', 'kz', 'md', 'az', 'am', 'tj', 'uz'];

    /**
     * SendEmailJob constructor.
     */
    public function __construct(array $email_data)
    {
        $this->email_data = $email_data;

    }

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        if ($this->email_data['email_country'] == 2) {
            $users = User::select('users.*')->whereNotIn('country', $this->codesRU)->whereNotNull('email_verified_at')->get();
        } else {
            $users = User::select('users.*')->whereIn('country', $this->codesRU)->whereNotNull('email_verified_at')->get();
        }
        \Log::info(json_encode($this->email_data['email_body']));
        foreach ($users as $user) {
            if ($user->email_verified_at) {
                Mail::to($user)->send(new NewsMail($user, $this->email_data['email-title'], $this->email_data['email_body']));
            }
        }
    }
}
