<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestBonusUser;
use App\Http\Requests\RequestPenaltyUser;
use App\Models\Deposit;
use App\Models\Order;
use App\Models\PageViews;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Webpatser\Countries\Countries;
use Yajra\Datatables\Datatables;

class UsersController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $countVerified = User::whereNotNull('email_verified_at')->count();
        $countVerifiedPhone = User::whereNotNull('phone_verified_at')->count();

        return view('admin/users/index')
            ->with('countVerified', $countVerified)
            ->with('countVerifiedPhone', $countVerifiedPhone);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function dataTable()
    {
        $users = User::orderBy('created_at', 'desc');

        return Datatables::of($users)->addColumn('show', function ($user) {
            return route('admin.users.show', $user->id);
        })->addColumn('edit', function ($user) {
            return route('admin.users.edit', $user->id);
        })->make(true);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, User $user)
    {
      // dd($user);

        $level = $request->has('level') ? $request->level : 1;
        $plevel = $request->has('plevel') ? $request->plevel : 1;

        return view('admin/users/show', [
            'user' => $user,
            'level' => $level,
            'plevel' => $plevel,
        ]);
    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function dataTableWrs($userId)
    {
        /** @var TransactionType $transactionWithdrawalType */
        $transactionWithdrawalType = TransactionType::getByName('withdraw');
        /** @var Transaction $wrs */
        $wrs = Transaction::select('transactions.*')->with([
            'currency',
            'paymentSystem',
            'user',
            'wallet',
        ])
            ->where('type_id', $transactionWithdrawalType->id)
            ->where('user_id', $userId);

        return Datatables::of($wrs)
            ->addColumn('status', function (Transaction $transaction) {
                return $transaction->isApproved() ? __('approved') : __('new');
            })
            ->editColumn('amount', function (Transaction $transaction) {
                return number_format($transaction->amount, $transaction->wallet->currency->precision, '.', '');
            })
            ->make(true);
    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function dataTableDeposits($userId)
    {
        $deposits = Deposit::where('user_id', $userId)
            ->with('rate', 'currency')
            ->select('deposits.*');

        return Datatables::of($deposits)
            ->editColumn('condition', function ($deposit) {
                return __($deposit->condition);
            })
            ->make(true);
    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function dataTableTransactions($userId)
    {
        $transactions = Transaction::where('user_id', $userId)
            ->with('currency', 'type')
            ->select('transactions.*');

        return Datatables::of($transactions)
            ->addColumn('type_name', function ($transaction) {
                return __($transaction->type->name);
            })
            ->editColumn('approved', function ($transaction) {
                return __($transaction->approved == 1 ? 'yes' : 'no');
            })
            ->make(true);
    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function dataTablePageViews($userId)
    {
        $pageViews = PageViews::where('user_id', $userId)
            ->select('page_views.*');



        $datatables = Datatables::of($pageViews)
            ->addColumn('location', function ($pv) {
                $countries = Cache::rememberForever('countries', function () {
                    return Countries::select('iso_3166_2', 'flag')->get();
                });
                $flag = $countries->where('iso_3166_2', geoip($pv->user_ip)->iso_code)->first();
                return '<img src="' . asset('flags') . '/' . $flag->flag . '"> ' . geoip($pv->user_ip)->country . ', ' . geoip($pv->user_ip)->city;
            })
            ->rawColumns(['location'])
            ->make(true);

        // dd($pageViews);
        return $datatables;
    }

    /**
     * @param RequestBonusUser $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bonus(RequestBonusUser $request)
    {
        $wallet = Wallet::find($request->wallet_id);
        $wallet = $wallet->addBonus($request->amount);
        if ($wallet) {
            // send notification to user
            $data = [
                'bonus_amount'   => $request->amount,
                'currency'       => $wallet->currency,
                'payment_system' => $wallet->paymentSystem,
                'balance'        => $wallet->balance,
            ];
            $wallet->user->sendNotification('bonus_accrued', $data);
            return back()->with('success', __('Bonus accrued'));
        }
        return back()->with('error', __('Unable to accrue bonus'));
    }

    /**
     * @param RequestPenaltyUser $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function penalty(RequestPenaltyUser $request)
    {
        /** @var Wallet $wallet */
        $wallet = Wallet::find($request->wallet_id);
        $wallet = $wallet->removeAmount($request->amount);

        if ($wallet) {
            // send notification to user
            $data = [
                'bonus_amount'   => $request->amount,
                'currency'       => $wallet->currency,
                'payment_system' => $wallet->paymentSystem,
                'balance'        => $wallet->balance,
            ];
            $wallet->user->sendNotification('penalty_accrued', $data);
            return back()->with('success', __('Penalty handled'));
        }
        return back()->with('error', __('Unable to handle penalty'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        return view('admin/users/edit', ['roles' => $roles, 'user' => $user]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'email' => 'required|email',
            'password' => 'nullable|min:6',
        ]);

        if ($user->update($request->except(['roles', 'password']))) {
            if (!empty($request->get('password'))) {
                $user->setPassword($request->password);
            }
            if ($request->roles) {
                $user->syncRoles($request->roles);
            }
            return redirect()->route('admin.users.show', $user->id)->with('success', __('User has been updated'));
        } else {
            return back()->with('error', __('Unable to update user'))->withInput();
        }
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            return redirect()->route('admin.users.index')->with('success', __('User has been deleted'));
        }
        return redirect()->route('admin.users.index')->with('error', __('Unable to delete user'));
    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function dataTableOrders($userId)
    {
        $orders = Order::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        return Datatables::of($orders)->make(true);
    }
}
