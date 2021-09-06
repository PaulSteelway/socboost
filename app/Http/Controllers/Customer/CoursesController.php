<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Managers\UserManager;
use App\Http\Managers\VoucherPurchaseManager;
use App\Models\AccountCategory;
use App\Models\BlogEntry;
use App\Models\Category;
use App\Models\Package;
use App\Models\Packet;
use App\Models\Product;
use App\Models\Reviews;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserReferral;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CoursesController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('main_pages.courses.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tiktok()
    {
        $price = 2490;
        $course = 'Tiktok';
        $signature = $this->getCourseSignature($course, $price);
        return view('main_pages.courses.tiktok')
            ->with('signature', $signature)
            ->with('price', $price)
            ->with('course', $course);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function youtube()
    {
        $price = 3570;
        $course = 'Youtube';
        $signature = $this->getCourseSignature($course, $price);
        return view('main_pages.courses.youtube')
            ->with('signature', $signature)
            ->with('price', $price)
            ->with('course', $course);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function telegram()
    {
        $price = 2750;
        $course = 'Telegram';
        $signature = $this->getCourseSignature($course, $price);
        return view('main_pages.courses.telegram')
            ->with('signature', $signature)
            ->with('price', $price)
            ->with('course', $course);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function instagram()
    {
        $price = 1990;
        $course = 'Instagram';
        $signature = $this->getCourseSignature($course, $price);
        return view('main_pages.courses.instagram')
            ->with('signature', $signature)
            ->with('price', $price)
            ->with('course', $course);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function payment(Request $request)
    {
        return \Response::json(['url' => 'https://yadi.sk/d/7Vx0XBwcfQcahA']);
    }

    /**
     * @return string
     */
    private function getCourseSignature($course, $price): string
    {
        $params = array(
            'account' => 'course',
            'sum' => $price,
            'desc' => 'Покупка курса ' . $course,
        );

        $secretKey = config('money.unitpay_secret_key');
        if (isFreePromotionSite()) {
            $secretKey = config('money.unitpay_secret_key_free');
        }

        $hashStr = $params['account'] . '{up}' . $params['desc'] . '{up}' . $params['sum'] . '{up}' . $secretKey;
        $signature = hash('sha256', $hashStr);
        return $signature;
    }

}
