<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

use App\Models\TransactionType;
use Illuminate\Database\Seeder;

/**
 * Class TransactionTypesSeeder
 */
class TransactionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactionTypes = [
            'enter',
            'withdraw',
            'bonus',
            'partner',
            'dividend',
            'create_dep',
            'close_dep',
            'penalty',
        ];

        foreach ($transactionTypes as $type) {
            $searchType = TransactionType::where('name', $type)->count();

            if ($searchType > 0) {
                echo "Transaction type '".$type."' already registered.\n";
                continue;
            }

            TransactionType::create([
                'name'       => $type,
                'commission' => 0,
            ]);
            echo "Transaction type '".$type."' registered.\n";
        }
    }
}
