<?php

namespace App\Http\Controllers\Customer\Order;

use App\Http\Controllers\Controller;
use App\Http\Managers\CategoryManager;
use App\Models\Category;
use App\Models\Reviews;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Response;

class CategoryController extends Controller
{
    /**
     * @param $categoryUrl
     * @param int|null $copy
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index($categoryUrl, $copy = null)
    {
        $categories = CategoryRepository::getHeaderCategories();

        $category = Category::where('status', 1)->where('url', $categoryUrl)->first();
        if (empty($category)) {
            return redirect(route('customer.main'))->with('error', __('Category not found'));
        }

        $direction = app()->getLocale() == 'en' ? 'desc' : 'asc';
        $reviews = Reviews::where('status', 1)
            ->where('type_id', 1)
            ->orderBy('lang_type', $direction)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $sessionOldData = session()->pull(Auth::id() . '_order_unfinished');
        if (!empty($sessionOldData)) {
            foreach ($sessionOldData as $key => $value) {
                session(['_old_input.' . $key => $value]);
            }
            if (stripos(parse_url(URL::full(), PHP_URL_QUERY), 'autoSubmitOrder=1') !== false) {
                session([Auth::id() . '_autoSubmitOrderEn' => true]);
            }
        }

        return view('customer.order.category')
            ->with('categories', $categories)
            ->with('category', CategoryManager::getCategoryWithMetaTagsAccordinglyUrl($category, $copy))
            ->with('parent_category', $category->parent)
            ->with('order_now', \request()->exists('order_now'))
            ->with('packets', $category->packets->toArray())
            ->with('reviews', $reviews)
            ->with('reviews_total', Reviews::where('status', 1)->count());
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function convert(Request $request)
    {
        $price = round($request->get('price'), 4);

        return Response::json(['price' => $price]);
    }
}
