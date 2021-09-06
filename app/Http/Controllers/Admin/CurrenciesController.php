<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class CurrenciesController
 * @package App\Http\Controllers\Admin
 */
class CurrenciesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.currencies.index');
    }

    /**
     * @param Currency $currency
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Currency $currency)
    {
        return view('admin.currencies.edit', [
            'currency' => $currency
        ]);
    }

    /**
     * @param Request $request
     * @param Currency $currency
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, Currency $currency)
    {
        if ($currency->update(
            [
                'name' => $request->name,
                'precision' => $request->precision
            ])) {
            return redirect()->route('admin.currencies.index')->with('success', __('Currency has been updated'));
        }
        return back()->with('error', __('Unable to update payment system'))->withInput();
    }
}
