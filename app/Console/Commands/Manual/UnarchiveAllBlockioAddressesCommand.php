<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Manual;

use App\Models\PaymentSystem;
use App\Modules\PaymentSystems\BlockioModule;
use Illuminate\Console\Command;

/**
 * Class UnarchiveAllBlockioAddressesCommand
 * @package App\Console\Commands\Manual
 */
class UnarchiveAllBlockioAddressesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unarchive:all_blockio_addresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unarchive all Blockio addresses to get money from main wallet.';

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
        $ps = PaymentSystem::getByCode('blockio');

        foreach ($ps->currencies as $currency) {
            try {
                $archivedAddresses = BlockioModule::getAddresses($currency->code, true);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
                break;
            }

            foreach ($archivedAddresses->data->addresses as $address) {
                try {
                    BlockioModule::unarchiveAddress($currency->code, $address->address);
                    $this->info($address->address.' unarchived.');
                } catch (\Exception $e) {
                    $this->warn($e->getMessage());
                    continue;
                }
            }
        }
    }
}
