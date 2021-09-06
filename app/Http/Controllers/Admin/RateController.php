<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RequestRateStoreUpdate;
use App\Models\Rate;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

/**
 * Class RateController
 * @package App\Http\Controllers\Admin
 */
class RateController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin/rates/index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin/rates/create');
    }

    /**
     * @param RequestRateStoreUpdate $request
     * @return $this|RedirectResponse
     */
    public function store(RequestRateStoreUpdate $request)
    {
        $rate = Rate::create($request->except(['reinvest', 'autoclose', 'active']));

        if (!$rate) {
            return back()->with('error', __('Unable to create tariff plan'))->withInput();
        }

        $rate->reinvest = !empty($request->reinvest) ? 1 : 0;
        $rate->autoclose = !empty($request->autoclose) ? 1 : 0;
        $rate->active = !empty($request->active) ? 1 : 0;
        $rate->save();

        return redirect()->route('admin.rates.index')->with('success', __('Deposit plan has been created'));
    }

    /**
     * @param Rate $rate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Rate $rate)
    {
        return view('admin.rates.show', [
            'rate' => $rate
        ]);
    }

    /**
     * @param Rate $rate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Rate $rate)
    {
        return view('admin/rates/edit', [
            'rate' => $rate
        ]);
    }

    /**
     * @param RequestRateStoreUpdate $request
     * @param Rate $rate
     * @return $this|RedirectResponse
     */
    public function update(RequestRateStoreUpdate $request, Rate $rate)
    {
        $rate->update($request->except(['reinvest', 'autoclose', 'active']));

        if (!$rate) {
            return back()->with('error', __('Unable to update deposit plan'))->withInput();
        }

        $rate->reinvest = !empty($request->reinvest) ? 1 : 0;
        $rate->autoclose = !empty($request->autoclose) ? 1 : 0;
        $rate->active = !empty($request->active) ? 1 : 0;
        $rate->save();

        return redirect()->route('admin.rates.edit', $rate->id)->with('success', __('Deposit plan has been updated'));
    }

    /**
     * @param Rate $rate
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy($rate)
    {
        $rate = Rate::find($rate);

        if ($rate->delete()) {
            return redirect()->route('admin.rates.index')->with('success', __('Deposit plan has been deleted'));
        }

        return redirect()->route('admin.rates.index')->with('error', __('Unable to delete deposit plan'));
    }
}
