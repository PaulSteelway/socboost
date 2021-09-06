<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

/**
 * Class CheckScriptJob
 * @package App\Jobs
 */
class CheckScriptJob implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @throws \Exception
     */
    public function handle()
    {
        Storage::put('CheckScriptJob.tmp', 'file created');
    }
}
