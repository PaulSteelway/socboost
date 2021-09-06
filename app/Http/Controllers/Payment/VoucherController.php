<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Managers\UserManager;
use App\Models\PaymentSystem;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Validator;

class VoucherController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('profile.topup_voucher');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function apply(Request $request)
    {
        try {
            $this->validate($request, ['code' => 'required']);


            $validator = Validator::make(request()->all(), [
                'g-recaptcha-response' => 'recaptcha',
            ]);
            
            // check if validator fails
            if($validator->fails()) {
                $errors = $validator->errors();
            }

            /** @var Voucher $voucher */
            $voucher = Voucher::where('code', $request->get('code'))->whereNull('user_id')->first();
            if (empty($voucher)) {
                throw new \Exception(__('Voucher Code is invalid.'));
            }

            /** @var User $user */
            $user = Auth::user();

            $wallet = $user->getActiveWallet();
            if (empty($wallet)) {
                $wallet = Wallet::setActiveWallet($user);
            }

            $paymentSystem = PaymentSystem::where('code', 'voucher')->first();

            /** @var Transaction $transaction */
            $transaction = Transaction::enterByParameters($wallet, $paymentSystem, $voucher->amount, $voucher->currency);
            $transaction->source = $voucher->id;
            $transaction->approved = true;
            $transaction->save();

            $voucher->user_id = $user->id;
            $voucher->save();

            $commission = $transaction->amount * 0.01 * $transaction->commission;
            $transactionAmount = $transaction->amount - $commission;
            if ($transaction->currency->code == 'USD') {
                $transactionAmount = convertUsdToRub($transactionAmount);
            }
            $wallet->refill($transactionAmount, null);

//            UserManager::accrueReferralBonusForEnter($transaction, $transactionAmount);
//            UserManager::accrueBonusWithTopup($transaction);

            return redirect(route('payment.success'));
        } catch (ValidationException $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->validator->errors()->getMessages());
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }
}
