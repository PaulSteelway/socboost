<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic;

use App\Models\SysLoad;
use Illuminate\Console\Command;

/**
 * Class ScanSysLoadCommand
 * @package App\Console\Commands\Automatic
 */
class ScanSysLoadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:sys_load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan system load and save data for graphics.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function handle()
    {
        $cpuLoad = SysLoad::getCpuLoad();
        $ramLoad = SysLoad::getRamLoad();

        SysLoad::create([
            'cpu' => $cpuLoad,
            'ram' => $ramLoad,
        ]);

        SysLoad::where('created_at', '<', now()->subHours(3))->delete();

        $this->comment('CPU load: '.$cpuLoad.'%');
        $this->comment('RAM load: '.$ramLoad.'%');
    }
}
