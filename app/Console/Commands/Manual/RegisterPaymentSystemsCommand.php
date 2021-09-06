<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Manual;

use App\Models\Currency;
use App\Models\PaymentSystem;
use Illuminate\Console\Command;

/**
 * Class RegisterPaymentSystemsCommand
 * @package App\Console\Commands\Manual
 */
class RegisterPaymentSystemsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register:payment_systems {demo?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register all needed payment systems.';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $questions = [
            'perfectmoney' => [
                'answer' => $this->argument('demo') == true ? 'yes' : $this->ask('Perfect Money [yes|no]', 'yes'),
                'name' => 'Perfect Money',
                'currencies' => [
                    'USD',
                    'EUR',
                ],
            ],
            'advcash' => [
                'answer' => $this->argument('demo') == true ? 'yes' : $this->ask('Advcash [yes|no]', 'yes'),
                'name' => 'Advcash',
                'currencies' => [
                    'USD',
                    'EUR',
                    'RUR',
                ],
            ],
            'payeer' => [
                'answer' => $this->argument('demo') == true ? 'yes' : $this->ask('Payeer [yes|no]', 'yes'),
                'name' => 'Payeer',
                'currencies' => [
                    'USD',
                    'EUR',
                    'RUR',
                ],
            ],
            'free-kassa' => [
                'answer' => $this->argument('demo') == true ? 'yes' : $this->ask('Free-Kassa [yes|no]', 'yes'),
                'name' => 'Free-Kassa',
                'currencies' => [
                    'USD',
                    'EUR',
                    'RUR',
                ],
            ],
            'coinpayments' => [
                'answer' => $this->argument('demo') == true ? 'yes' : $this->ask('Coinpayments [yes|no]', 'yes'),
                'name' => 'Coinpayments',
                'currencies' => [
                    'BTC',
                    'LTC',
                    'BCH',
                    'ETH',
                    'DOGE',
                ],
            ],
            'blockio' => [
                'answer' => $this->argument('demo') == true ? 'yes' : $this->ask('Blockio [yes|no]', 'yes'),
                'name' => 'Blockio',
                'currencies' => [
                    'BTC',
                    'LTC',
                    'DOGE',
                ],
            ],
            'nixmoney' => [
                'answer' => $this->argument('demo') == true ? 'yes' : $this->ask('Nixmoney [yes|no]', 'yes'),
                'name' => 'Nixmoney',
                'currencies' => [
                    'USD',
                    'EUR',
                    'BTC',
                ],
            ],
            'enpay' => [
                'answer' => $this->argument('demo') == true ? 'yes' : $this->ask('Enpay [yes|no]', 'yes'),
                'name' => 'Enpay',
                'currencies' => [
                    'RUR',
                    'USD',
                ],
            ],
            'waves' => [
                'answer' => $this->argument('demo') == true ? 'yes' : $this->ask('Waves [yes|no]', 'yes'),
                'name' => 'Waves',
                'currencies' => [
                    'hm1_testnet'
                ],
            ],
            'qiwi' => [
                'answer' => $this->argument('demo') == true ? 'yes' : $this->ask('QIWI [yes|no]', 'yes'),
                'name' => 'Qiwi',
                'currencies' => [
                    'RUR'
                ],
            ],
        ];

        foreach ($questions as $paymentSystemCode => $data) {
            $this->line('------');

            if ('yes' !== $data['answer']) {
                continue;
            }

            $this->info('Registering ' . $paymentSystemCode);
            $checkExists = PaymentSystem::where('code', $paymentSystemCode)->get()->count();

            if ($checkExists > 0) {
                $this->error($paymentSystemCode . ' already registered.');
                continue;
            }

            $reg = PaymentSystem::create([
                'name' => $data['name'],
                'code' => $paymentSystemCode
            ]);

            if (!$reg) {
                $this->error('Can not register ' . $paymentSystemCode);
                continue;
            }

            $this->info($paymentSystemCode . ' registered.');

            foreach ($data['currencies'] as $currency) {
                $currencyInfo = Currency::where('code', $currency)->first();

                if (empty($currencyInfo)) {
                    $this->warn('currency ' . $currency . ' is not registered, ' . $paymentSystemCode . ' will be without ' . $currency);
                    continue;
                }

                \DB::table('currency_payment_system')->insert([
                    'currency_id' => $currencyInfo->id,
                    'payment_system_id' => $reg->id,
                ]);

                $this->info('currency ' . $currency . ' registered for ' . $paymentSystemCode);
            }
        }
    }
}
