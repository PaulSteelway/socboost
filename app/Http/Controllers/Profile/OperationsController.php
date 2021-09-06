<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\Wallet;
use Yajra\DataTables\DataTables;

class OperationsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $orders = \App\Models\Order::where('user_id', \Auth::user()->id)
            ->whereRaw('(jap_id  IS NOT NULL  OR product_id IS NOT NULL)')
            ->orderBy('created_at', 'desc');
        return view('profile.operations');
    }

    /**
     * @param null $type
     * @return mixed
     * @throws \Exception
     */
    public function dataTable($type = null)
    {
        $operations = cache()->tags('userAllOperations.' . getUserId())->remember('c.' . getUserId() . '.userOperations.type-' . $type, getCacheCLifetime('userOperations'), function () use ($type) {
            $operations = Transaction::where('user_id', getUserId());

            if (null !== $type) {
                $typeId = TransactionType::getByName($type);

                if (null !== $typeId) {
                    $operations = $operations->where('type_id', $typeId->id);
                }
            }

            return $operations
                ->with('user', 'currency', 'type')
                ->get();
        });

        return DataTables::of($operations)
            ->addColumn('type_name', function ($transaction) {
                return __($transaction->type->name);
            })
            ->addColumn('partner_from', function ($transaction) {
                if ($transaction->type->name != 'partner') {
                    return __('not affiliate');
                }

                $wallet = Wallet::where('id', $transaction->source)->first();

                if (null === $wallet) {
                    return __('source not found');
                }

                return $wallet->user->login;
            })
            ->make(true);
    }
}
