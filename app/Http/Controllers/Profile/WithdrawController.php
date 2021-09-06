<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestWithdraw;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\Wallet;

/**
 * Class WithdrawController
 * @package App\Http\Controllers\Profile
 */
class WithdrawController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('profile.withdraw');
    }

    /**
     * @param RequestWithdraw $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(RequestWithdraw $request)
    {
        $wallet = Wallet::find($request->wallet_id);

        if ($wallet->balance - $wallet->balance * TransactionType::getByName('withdraw')->commission * 0.01 < $request->amount) {
            return back()->with('error', __('Requested amount including withdrawal commission exceeds the wallet balance'));
        }

        if (empty($wallet->external)) {
            return back()->with('error', __('You wallet address is empty. Please, fill it in the settings.'));
        }

        try {
            Transaction::withdraw($wallet, $request->amount);
        } catch(\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', __('Request has been sent to administration'));
    }
}
