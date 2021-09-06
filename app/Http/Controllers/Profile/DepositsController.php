<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestCreateDeposit;
use App\Models\Deposit;
use App\Models\Rate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DepositsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('profile.deposits.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $rate = $request->has('rate_id') ? Rate::where('id', $request->rate_id)->first()->toArray() : '';

        return view('profile.deposits.create', [
            'rate' => $rate
        ]);
    }

    /**
     * @param RequestCreateDeposit $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(RequestCreateDeposit $request)
    {
        Deposit::addDeposit($request->all());
        return redirect()->route('profile.deposits')->with('success', __('Deposit has been created'));
    }

    /**
     * @param null $active
     * @return mixed
     * @throws \Exception
     */
    public function dataTable($active = null)
    {
        $deposits = cache()->tags('userDeposits.' . getUserId())->remember('c.' . getUserId() . '.userDeposits.active-' . $active, getCacheCLifetime('userDeposits'), function () use ($active) {
            $deposits = Deposit::where('user_id', getUserId());

            if (null !== $active) {
                $deposits = $deposits->where('active', $active);
            }

            return $deposits->with([
                'transactions',
                'user',
                'rate',
                'wallet',
                'paymentSystem'
            ])
                ->get();
        });

        return DataTables::of($deposits)
            ->editColumn('condition', function ($deposit) {
                return __($deposit->condition);
            })
            ->editColumn('closing_at', function ($deposit) {
                return Carbon::parse($deposit->created_at)->addDays($deposit->duration)->toDateString();
            })
            ->make(true);
    }

    public function show()
    {
        //
    }
}
