<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Automatic;

use App\Models\PaymentSystem;
use App\Models\TransactionType;
use App\Models\User;
use App\Modules\PaymentSystems\BlockioModule;
use Illuminate\Console\Command;

/**
 * Class ArchiveBlockioAddressesCommand
 * @package App\Console\Commands\Automatic
 */
class ArchiveBlockioAddressesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:blockio_addresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive unused Block.io addresses.';

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
        /*
         * Checking how many un-archive addresses do we have
         */
        $ps        = PaymentSystem::getByCode('blockio');
        $addresses = 0;
        $limit     = config('money.blockio_limit_addresses');

        if (null === $ps) {
            $this->info('Can not get BLOCKIO module. Skip archiving.');
        }

        foreach ($ps->currencies as $currency) {
            try {
                $requestAddresses = BlockioModule::getAddresses($currency->code);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
                return;
            }
            $addresses += count($requestAddresses->data->addresses);
        }

        if ($addresses <= $limit) {
            $this->info('Found '.$addresses.' addresses, limit is '.$limit.'. Stopping ...');
            return;
        }

        /*
         * Archiving all addresses
         */
        $connectedUsers = User::where(function($q) {
                $q->where('blockio_wallet_btc', '!=', null)
                    ->orWhere('blockio_wallet_ltc', '!=', null)
                    ->orWhere('blockio_wallet_doge', '!=', null);
            })
            ->get();
        $enterType      = TransactionType::getByName('enter');

        foreach ($ps->currencies as $currency) {
            /** @var User $user */
            foreach ($connectedUsers as $user) {
                /*
                 * Checking if user have enter transactions with this wallet at last 12 hours
                 */
                $notFinishedTransactions = $user->transactions()
                    ->where('payment_system_id', $ps->id)
                    ->where('currency_id', $currency->id)
                    ->where('type_id', $enterType->id)
                    ->where('created_at', '>', now()->subHours(12))
                    ->count();

                if ($notFinishedTransactions > 0) {
                    $msg = 'User '.$user->login.' have not finished enter transactions. Continue..';
                    \Log::info($msg);
                    $this->warn($msg);
                    continue;
                }

                /*
                 * Archiving
                 */
                $external = $user->getAttribute('blockio_wallet_'.strtolower($currency->code));

                if (empty($external)) {
                    continue;
                }

                try {
                    BlockioModule::archiveAddress($currency->code, $external);
                    $msg = 'Blockio. Address '.$external.' was archived for user '.$user->login;
                    \Log::info($msg);
                    $this->info($msg);
                } catch (\Exception $e) {
                    $this->warn($e->getMessage());
                    continue;
                }
            }
        }
    }
}
