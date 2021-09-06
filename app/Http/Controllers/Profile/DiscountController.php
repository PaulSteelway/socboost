<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('discounts.index');
    }
}
