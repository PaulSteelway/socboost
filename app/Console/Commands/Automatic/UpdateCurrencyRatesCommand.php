<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic;

use App\Models\Setting;
use App\Modules\FixerModule;
use App\Modules\LivecoinModule;
use Illuminate\Console\Command;

/**
 * Class UpdateCurrencyRatesCommand
 * @package App\Console\Commands\Automatic
 */
class UpdateCurrencyRatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:currency_rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all currency rates to RUB.';

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
     * @throws \GuzzleHttp\Exception\GuzzleException|\Exception
     */
    public function handle()
    {
        $currencies = [
            'BTC',
            'USD',
        ];

        $this->info('work with FIXER');
        $this->warn('API key: '.env('FIXER_ACCESS_KEY'));

        foreach($currencies as $currency) {
            $rates = FixerModule::getRate($currency, ['RUB']);
            $this->info(print_r($rates,true));

            if (isset($rates->rates->RUB)) {
                $k = strtolower($currency).'_to_rub';
                $v = $rates->rates->RUB;
                Setting::setValue($k, $v);
                $this->info($k.' -> '.$v);
            }
        }

        $this->line('-----------');

        foreach($currencies as $currency) {
            $rates = FixerModule::getRate('RUB', [$currency]);
            $this->info(print_r($rates,true));

            if (isset($rates->rates->$currency)) {
                $k = 'rub_to_'.strtolower($currency);
                $v = $rates->rates->$currency;
                Setting::setValue($k, $v);
                $this->info($k.' -> '.$v);
            }
        }
    }
}
