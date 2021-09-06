<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Yajra\Datatables\Datatables;

/**
 * Class DepositController
 * @package App\Http\Controllers\Admin
 */
class DepositController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.deposits.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function dataTable()
    {
        $deposits = Deposit::with('user', 'rate', 'currency')
            ->select('deposits.*');

        return Datatables::of($deposits)
            ->editColumn('condition', function ($deposit) {
                return __($deposit->condition);
            })->editColumn('invested', function ($deposit) {
                return number_format($deposit->invested, $deposit->currency->precision, '.', '');
            })
            ->make(true);
    }

    /**
     * @param Deposit $deposit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Deposit $deposit)
    {
        return view('admin.deposits.show', ['deposit' => $deposit]);
    }

    /**
     * @param Deposit $deposit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function block(Deposit $deposit)
    {
        return $deposit->block()
            ? back()->with('success', __('Blocked'))
            : back()->with('error', __('Unable to block'));
    }

    /**
     * @param Deposit $deposit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unblock(Deposit $deposit)
    {
        return $deposit->unblock()
            ? back()->with('success', __('Unblocked'))
            : back()->with('error', __('Unable to unblock'));
    }
}
