<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Managers\VoucherPurchaseManager;
use App\Models\AccountCategory;
use App\Models\BlogEntry;
use App\Models\Category;
use App\Models\Package;
use App\Models\Packet;
use App\Models\Product;
use App\Models\Reviews;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MainController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

      $packages = Package::query()
        ->with('category')
        ->get();

      return view('customer.main', [
        'packages' => $packages,
      ]);
    }




    // nit:Daan no refactor

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function faq()
    {
        return view('customer.FaqController');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function app()
    {
        return view('main_pages.app');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about()
    {
        return view('main_pages.about');
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function youtube()
    {
        return view('main_pages.lendings.youtube');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function telegram()
    {
        return view('main_pages.lendings.telegram');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function instagram()
    {
        return view('main_pages.lendings.instagram');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tiktok()
    {
        return view('main_pages.lendings.tiktok');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function voucher()
    {
        $vouchers = config('enumerations.vouchers');
        return view('main_pages.voucher')->with('vouchers', $vouchers);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function voucherPurchase(Request $request)
    {
        try {
            if (Auth::check()) {
                $this->validate($request, ['voucher_id' => 'required|in:' . implode(',', array_keys(config('enumerations.vouchers')))]);

                /** @var User $user */
                $user = Auth::user();
                (new VoucherPurchaseManager($user, config('enumerations.vouchers')[$request->get('voucher_id')]))->processing();

                return redirect()->back()->with('success', __('Voucher purchasing completed successfully. Please check your email'));
            } else {
                throw new \Exception(__('Log in to purchase voucher'));
            }
        } catch (ValidationException $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->validator->errors()->getMessages());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function affilateProgram()
    {
        return view('main_pages.affilate_program');
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function subscriptionSystem()
    {
        return view('main_pages.subscription_system');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function readyAccount($categoryUrl = false)
    {
        $categories = AccountCategory::orderBy('order')->get();
        $product = Product::where('url', $categoryUrl)->first();

        if (empty($category)) {
            $product = Product::first();

        }

        $direction = app()->getLocale() == 'en' ? 'desc' : 'asc';
        $reviews = Reviews::where('status', 1)
            ->where('type_id', 2)
            ->orderBy('lang_type', $direction)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        return view('main_pages.readyAccount')
            ->with('categories', $categories)
            ->with('product', $product ? $product->toArray() : [])
            ->with('category', isset($product) AND isset($product->accountCategory) ? $product->accountCategory : [])
            ->with('order_now', \request()->exists('order_now'))
            ->with('reviews', $reviews);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function readyAccount2($categoryUrl = false)
    {
        $categories = AccountCategory::orderBy('order')->get();
        $category = AccountCategory::where('url', $categoryUrl)->first();

        if (empty($category)) {
            $product = Product::first();
        }

        $direction = app()->getLocale() == 'en' ? 'desc' : 'asc';
        $reviews = Reviews::where('status', 1)
            ->where('type_id', 2)
            ->orderBy('lang_type', $direction)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        return view('main_pages.readyAccount2')
            ->with('categories', $categories)
            ->with('products', isset($category) && isset($category->products) ? $category->products()->orderBy('order')->get() : [])
            ->with('category', isset($category) ? $category : false)
            ->with('order_now', \request()->exists('order_now'))
            ->with('reviews', $reviews);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function premiumAccount()
    {
        return view('main_pages.premiumAccount');
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function prices(Request $request)
    {
        $category_id = $request->get('category_id');

        $categories = Category::whereNull('parent_id')->pluck(app()->getLocale() == 'en' ? 'name_en' : 'name_ru', 'id');
        $packages = Packet::where('service', 'JAP');

        if($category_id){
            $category = Category::find($category_id);
            if($category){
                $packages->whereIn('category_id', $category->child_categories);
            }
        }
        return view('main_pages.prices')
            ->with('packages', $packages->paginate(50))
            ->with('category_id', $category_id)
            ->with('categories', $categories);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pricesSearch(Request $request)
    {
        $category_id = $request->get('category_id');

        $categories = Category::whereNull('parent_id')->pluck(app()->getLocale() == 'en' ? 'name_en' : 'name_ru', 'id');
        $packages = Packet::where('service', 'JAP');
        if($category_id){
            $category = Category::find($category_id);
            if($category){
                $packages->whereIn('category_id', $category->child_categories);
            }
        }
         if($request->name){
             $packages->where('name_en', 'LIKE', "%$request->name%")->orWhere('name_ru', 'LIKE', "%$request->name%");
         }
        return view('main_pages.prices')
            ->with('packages', $packages->paginate(50))
            ->with('search', $request->name)
            ->with('category_id', $category_id)
            ->with('categories', $categories);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function publicOffer()
    {
        return view('main_pages.public_offer');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_referal_page()
    {
        return view('main_pages.user_referal_page');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function privacy_policy()
    {
        return view('main_pages.privacy_policy');
    }
    public function refund_policy()
    {
        return view('main_pages.refund_policy');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function my_profile_internal()
    {
        $referral = \Auth::user()->userReferral;
        $userRefLink = !empty($referral) && !empty($referral->link) ? route('referral.route', $referral->link) : null;
        $countReferrals = UserReferral::where('referral_id', \Auth::id())->count();

        return view('main_pages.my_profile_internal')
            ->with('user', Auth::user())
            ->with('userRefLink', $userRefLink)
            ->with('countReferrals', $countReferrals);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function referal_page()
    {
        $referral = \Auth::user()->userReferral;
        $userRefLink = !empty($referral) && !empty($referral->link) ? route('referral.route', $referral->link) : null;
        $countReferrals = UserReferral::where('referral_id', \Auth::id())->count();

        return view('main_pages.referal_page')
            ->with('user', Auth::user())
            ->with('userRefLink', $userRefLink)
            ->with('countReferrals', $countReferrals);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blog()
    {
        return view('main_pages.blog');
    }

    /**
     * @param $blog_slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function blog_internal($blog_slug)
    {
        $blog_entry = BlogEntry::where('slug', $blog_slug)->first();
        if ($blog_entry) {
            return view('main_pages.blog_internal')
                ->with('blog_entry', $blog_entry)
                //для отображение meta тегов чтоб не менять хедер
                ->with('category', $blog_entry);


        } else {
            return abort(404);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function courses()
    {
        return view('main_pages.courses');
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function disableModal()
    {

        $user = Auth::user();
        $user->discount_modal = true;
        $user->save();
    }


}
