<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\PaymentSystem;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

/**
 * Class WithdrawalRequestsController
 * @package App\Http\Controllers\Admin
 */
class WithdrawalRequestsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.requests.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function dataTable()
    {
        /** @var TransactionType $transactionWithdrawType */
        $transactionWithdrawType    = TransactionType::getByName('withdraw');
        $wrs                        = Transaction::select('transactions.*')->with([
            'currency',
            'paymentSystem',
            'wallet',
            'user',
        ])
            ->where('approved', 0)
            ->where('type_id', $transactionWithdrawType->id);

        return Datatables::of($wrs)->editColumn('status', function ($wr) {
            return $wr->approved == 1 ? __('approved') : __('new');
        })->editColumn('amount', function (Transaction $wr) {
            return number_format($wr->amount, $wr->wallet->currency->precision, '.', '');
        })->addColumn('external', function (Transaction $wr) {
            return $wr->wallet->external;
        })->make(true);
    }

    /**
     * @param $transaction
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($transaction)
    {
        $transaction = Transaction::find($transaction);

        return view('admin.requests.show', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function approveMany(Request $request)
    {
        $messages = [];

        if ($request->approve) {
            foreach ($request->list as $item) {
                $messages[] = $this->approve($item, true);
            }
        } elseif ($request->approveManually) {
            foreach ($request->list as $item) {
                $messages[] = $this->approveManually($item, true);
            }
        } elseif ($request->reject) {
            foreach ($request->list as $item) {
                $messages[] = $this->reject($item, true);
            }
        }

        return back()->with('success', __('List of withdrawal requests processed.').'<hr>'.implode('<hr>', $messages));
    }

    /**
     * @param $transaction
     * @param bool $massMode
     * @return array|\Illuminate\Http\RedirectResponse|null|string
     * @throws \Exception
     */
    public function reject($transaction, $massMode=false)
    {
        /** @var Transaction $transaction */
        $transaction = Transaction::find($transaction);

        if ($transaction->isApproved()) {
            if (true === $massMode) {
                return __('This request already processed.');
            }
            return back()->with('error', __('This request already processed.'));
        }

        /** @var Wallet $wallet */
        $wallet         = $transaction->wallet()->first();
        /** @var User $user */
        $user           = $wallet->user()->first();
        /** @var PaymentSystem $paymentSystem */
        $paymentSystem  = $wallet->paymentSystem()->first();
        /** @var Currency $currency */
        $currency       = $wallet->currency()->first();
        $amount         = $transaction->amount;

        if (null === $wallet || null === $user || null === $paymentSystem || null === $currency) {
            throw new \Exception('Wallet, user, payment system or currency is not found for withdrawal reject.');
        }

        $wallet->returnFromRejectedWithdrawal($transaction);
        $transaction->delete();

        $data = [
            'withdraw_amount' => $amount,
            'currency'        => $currency,
            'payment_system'  => $paymentSystem
        ];
        $user->sendNotification('rejected_withdrawal', $data);

        if (true === $massMode) {
            return __('Request rejected');
        }
        return back()->with('success', __('Request rejected'));
    }

    /**
     * @param $transaction
     * @param bool $massMode
     * @return \Illuminate\Http\RedirectResponse|string
     * @throws \Exception
     */
    public static function approve($transaction, $massMode=false)
    {
        /** @var Transaction $transaction */
        $transaction = Transaction::find($transaction);

        if ($transaction->isApproved()) {
            if (true === $massMode) {
                return __('This request already processed.');
            }
            return back()->with('error', __('This request already processed.'));
        }

        /** @var Wallet $wallet */
        $wallet         = $transaction->wallet()->first();
        /** @var User $user */
        $user           = $wallet->user()->first();
        /** @var PaymentSystem $paymentSystem */
        $paymentSystem  = $wallet->paymentSystem()->first();
        /** @var Currency $currency */
        $currency       = $wallet->currency()->first();

        if (null === $wallet || null === $user || null === $paymentSystem || null === $currency) {
            throw new \Exception('Wallet, user, payment system or currency is not found for withdrawal approve.');
        }

        $ps = $paymentSystem->getClassName();

        if (empty($wallet->external)) {
            if (true === $massMode) {
                return __('ERROR:').' wallet is empty';
            }
            return back()->with('error', __('ERROR:').' wallet is empty');
        }

        try {
            $batchId = $ps::transfer($transaction);
        } catch (\Exception $e) {
            if (true === $massMode) {
                return __('ERROR:').' ' . $e->getMessage();
            }
            return back()->with('error', __('ERROR:').' ' . $e->getMessage());
        }

        if (empty($batchId)) {
            $batchErr = __('Unable to approve request, payment system transfer is failed ..');

            if (true === $massMode) {
                return __($batchErr);
            }
            return back()->with('error', __($batchErr));
        }

        $transaction->update([
            'batch_id' => $batchId,
            'approved' => true,
        ]);

        $data = [
            'withdraw_amount' => $transaction->amount,
            'currency'        => $currency,
            'payment_system'  => $paymentSystem
        ];
        $user->sendNotification('approved_withdrawal', $data);

        try {
            $ps::getBalances();
        } catch (\Exception $e) {
            if (true === $massMode) {
                return __('ERROR:').' ' . $e->getMessage();
            }
            return back()->with('error', __('ERROR:').' ' . $e->getMessage());
        }

        if (true === $massMode) {
            return $transaction->amount.$currency->symbol.' - '.__('Request approved, money transferred to user wallet');
        }
        return back()->with('success', $transaction->amount.$currency->symbol.' - '.__('Request approved, money transferred to user wallet'));
    }

    /**
     * @param $transaction
     * @param bool $massMode
     * @return \Illuminate\Http\RedirectResponse|string
     * @throws \Exception
     */
    public function approveManually($transaction, $massMode=false)
    {
        /** @var Transaction $transaction */
        $transaction = Transaction::find($transaction);

        if ($transaction->isApproved()) {
            if (true === $massMode) {
                return __('This request already processed.');
            }
            return back()->with('error', __('This request already processed.'));
        }

        /** @var Wallet $wallet */
        $wallet         = $transaction->wallet()->first();
        /** @var User $user */
        $user           = $wallet->user()->first();
        /** @var PaymentSystem $paymentSystem */
        $paymentSystem  = $wallet->paymentSystem()->first();
        /** @var Currency $currency */
        $currency       = $wallet->currency()->first();

        if (null === $wallet || null === $user || null === $paymentSystem || null === $currency) {
            throw new \Exception('Wallet, user, payment system or currency is not found for withdrawal approve.');
        }

        if (empty($wallet->external)) {
            if (true === $massMode) {
                return __('ERROR:').' wallet is empty';
            }
            return back()->with('error', __('ERROR:').' wallet is empty');
        }

        $transaction->update([
            'approved' => true
        ]);

        $data = [
            'withdraw_amount'   => $transaction->amount,
            'currency'          => $currency,
            'payment_system'    => $paymentSystem
        ];
        $user->sendNotification('approved_withdrawal', $data);

        $ps = $paymentSystem->getClassName();

        try {
            $ps::getBalances();
        } catch (\Exception $e) {
            if (true === $massMode) {
                return __('ERROR:').' ' . $e->getMessage();
            }
            return back()->with('error', __('ERROR:').' ' . $e->getMessage());
        }

        if (true === $massMode) {
            return $transaction->amount.$currency->symbol.' - '.__('Request approved.');
        }
        return back()->with('success', $transaction->amount.$currency->symbol.' - '.__('Request approved.'));
    }
}
