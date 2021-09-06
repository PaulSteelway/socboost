<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic;

use App\Jobs\CheckScriptJob;
use App\Jobs\SendLogsJob;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

/**
 * Class ScriptCheckerCommand
 * @package App\Console\Commands\Automatic
 */
class ScriptCheckerCommand extends Command
{
    /** @var string $licenceServer */
    private $licenceServer = 'https://licence.hyipium.com/active/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:script';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking script files and licence.';

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
     * @throws \Exception
     */
    public function handle()
    {
        $this->line('================');
        $this->info('Scanning files and directories:');
        $this->checkFilesRights();

        $this->line('================');
        $this->info('Checking how JOBS system service is work:');
        $this->checkJobs();

        $this->line('================');
        $this->info('Checking how MEMCACHED system service is work:');
        $this->checkCache();

        $this->line('================');
        $this->info('Checking how mush storage is left:');
        $this->checkStorage();

        $this->line('================');
        $this->info('Trying to open website home page:');
        $this->checkHomePageOpening();

//        $this->line('================');
//        $this->info('Checking domain licence:');
//        $this->checkLicence();
    }

    /**
     * @throws \Exception
     */
    private function checkFilesRights()
    {
        $directoriesToScan = [
            'storage'         => 0777,
            'resources/lang'  => 0777,
            'bootstrap/cache' => 0777,
        ];

        foreach ($directoriesToScan as $directory => $rights)
        {
            $this->info('Scanning "'.$directory.'"');

            if (!\File::exists($directory)) {
                $this->warn('Directory '.$directory.' is not exists.');
                $this->info('Trying to create this folder automatically.');

                try {
                    \File::makeDirectory(base_path($directory), $rights, true);
                    $this->info('SUCCESS, directory '.$directory.' was created with writable rights');
                } catch (\Exception $e) {
                    $msg = 'Can not create directory '.base_path($directory).' with rights '.$rights.'. Please, do it manually.';
                    $this->warn($msg);
                    throw new \Exception($msg);
                }
            }

            if (!\File::isWritable($directory)) {
                $this->warn('Directory '.base_path($directory).' is not writable.');
                $this->info('Trying to set rights automatically.');

                try {
                    \File::chmod($directory, $rights);
                    $this->info('Directory rights was automatically updated. All fine.');
                } catch (\Exception $e) {
                    $msg = 'Can not automatically update directory '.$directory.' to rights '.$rights.'. Please, do it manually.';
                    $this->error($msg);
                    \Log::critical($msg);
                    SendLogsJob::dispatch($msg)->onQueue(getSupervisorName().'-low')->delay(0);
                    return;
                }
                return;
            }

            $this->info('Directory "'.$directory.'" checked. All fine.');
            $this->line('');
        }
    }

    /**
     * @return void
     */
    private function checkJobs()
    {
        $checkJobsFile = 'CheckScriptJob.tmp';

        if (Storage::exists($checkJobsFile)) {
            Storage::delete($checkJobsFile);
        }
        $this->info('creating JOB and delay it for 1 seconds.');
        CheckScriptJob::dispatch()->onQueue(getSupervisorName().'-high')->delay(now());
        $this->info('wait 15 seconds to get JOB result.');
        sleep(15);

        if (!Storage::exists($checkJobsFile)) {
            $msg = 'JOBs service is not working. Please, activate JOBS with type "redis". Horizon was terminated.';
            $this->error($msg);
            \Log::critical($msg);
            SendLogsJob::dispatch($msg)->onQueue(getSupervisorName().'-low')->delay(0);
            Artisan::call('horizon:terminate');
            return;
        }
        Storage::delete($checkJobsFile);
        $this->info('Jobs successfully checked. Temp files was removed.');
    }

    /**
     * @return void
     * @throws \Exception
     */
    private function checkCache()
    {
        $cacheKey   = 'ScriptCheckerCache';
        $cacheValue = 'cache was created';
        cache()->put($cacheKey, $cacheValue, Carbon::now()->addMinute());

        if (cache()->get($cacheKey) != $cacheValue) {
            $msg = 'Memcached is not working. Please, check it.';
            $this->error($msg);
            \Log::critical($msg);
            SendLogsJob::dispatch($msg)->onQueue(getSupervisorName().'-low')->delay(0);
            return;
        }
        $this->info('Memcached successfully checked. Service is active.');
    }

    /**
     * @return void
     */
    private function checkStorage()
    {
        $totalSpace   = intval(disk_total_space(base_path()) / 1024 / 1024);
        $freeSpace    = intval(diskfreespace(base_path()) / 1024 / 1024);
        $minimumSpace = 10240;

        $msg = 'Disk total space for directory '.base_path().' is '.$totalSpace.'MB ('.intval($totalSpace/1024).'GB), free space '.$freeSpace.'MB ('.intval($freeSpace/1024).'GB). Minimum space for production is '.$minimumSpace.'MB ('.intval($minimumSpace/1024).'GB)';

        if ($freeSpace < $minimumSpace) {
            $this->error($msg);
            \Log::critical($msg);
            SendLogsJob::dispatch($msg)->onQueue(getSupervisorName().'-low')->delay(0);
            return;
        } else {
            $this->info($msg);
        }
    }

    /**
     * @return void
     */
    private function checkHomePageOpening()
    {
        if (config('app.env') == 'demo') {
            return;
        }

        $client   = new Client();
        $baseUrl  = route('customer.main');
        $headers  = [];
        $verify   = config('app.env') == 'production' ? true : false;
        $params   = [
            'headers' => $headers,
            'verify'  => $verify,
            'allow_redirects' => [
                'max' => 10,
            ],
        ];

        try {
            $response = $client->get($baseUrl, $params);

            if ($response->getStatusCode() !== 200) {
                $msg = 'Request to '.$baseUrl.' was with response status '.$response->getStatusCode();
                $this->error($msg);
                \Log::critical($msg);
            } else {
                $this->info('Request to '.$baseUrl.' was success, response status code is '.$response->getStatusCode().'. All fine.');
            }
        } catch (\Exception $e) {
            $msg = 'Request to '.$baseUrl.' is failed. '.$e->getMessage();
            $this->error($msg);
            \Log::critical($msg);
            SendLogsJob::dispatch($msg)->onQueue(getSupervisorName().'-low')->delay(0);
        }
    }

    /**
     * @return void
     */
    private function checkLicence()
    {
        $host = parse_url(url('/'), PHP_URL_HOST);

        if (empty($host)) {
            $this->error('Empty host.');
            return;
        }

        $file   = $host.'.licence';
        $verify = config('app.env') == 'production' ? true : false;

        $disk           = 'licences';
        $indicatorFile  = getTodayLicenceFile();

        $removeMsg = 'licence error';

        try {
            $client = new Client();
            $res = $client->get($this->licenceServer . $file, [
                'verify' => $verify,
            ]);
        } catch (\Exception $e) {
            $this->error('Got error while trying to connect. '.$e->getMessage());
            Storage::disk($disk)->delete($indicatorFile);
            $this->info($removeMsg);
            \Log::error($removeMsg);
            SendLogsJob::dispatch($removeMsg)->onQueue(getSupervisorName().'-low')->delay(0);
            return;
        }
        $body = $res->getBody();

        if (!preg_match('/active/', $body)) {
            Storage::disk($disk)->delete($indicatorFile);
            $this->info($removeMsg);
            \Log::error($removeMsg);
            SendLogsJob::dispatch($removeMsg)->onQueue(getSupervisorName().'-low')->delay(0);
            return;
        }

        if (!Storage::disk($disk)->exists($indicatorFile)) {
            try {
                Storage::disk($disk)->put($indicatorFile, $body);
                $this->info('Licence '.$indicatorFile.' file was created.');
            } catch (\Exception $exception) {
                $msg = 'Can not create or update licence file. ' . $exception->getMessage();
                $this->error($msg);
                \Log::critical($msg);
                SendLogsJob::dispatch($msg)->onQueue(getSupervisorName().'-low')->delay(0);
                return;
            }
        }
        $this->info('looks fine');
        return;
    }

    /**
     * @return string
     */
    public static function checkClassExists()
    {
        return 'looks ok';
    }
}
