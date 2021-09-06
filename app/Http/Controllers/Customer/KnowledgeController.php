<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class KnowledgeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('customer.KnowledgeController');
    }
}
