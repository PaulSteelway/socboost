<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Reviews;
use App\Models\Subscription;
use App\Modules\PaymentSystems\UnitpayModule;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Response;

class SubscriptionsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $subscriptions = Subscription::where('user_id', Auth::id())->orderBy('updated_at', 'desc')->paginate(3);
        return view('profile.subscriptions.index')->with('subscriptions', $subscriptions);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show($id)
    {
        /** @var Subscription $subscription */
        $subscription = Subscription::where('id', $id)->where('user_id', Auth::id())->first();
        if (empty($subscription)) {
            return redirect()->back()->withErrors(__('Subscription does not exist.'));
        }

        if (strcasecmp($subscription->status, 'active') == 0) {
            return redirect(route('profile.subscriptions.index'));
        }

        $category = $subscription->packet->category()->where('status', 1)->first();
        if (empty($category)) {
            return redirect(route('customer.main'))->with('error', __('Category not found'));
        }

        $categories = CategoryRepository::getHeaderCategories();
        $reviews = Reviews::where('status', 1)->where('type_id', 1)->orderBy('created_at', 'desc')->limit(3)->get();

        return view('customer.order.category')
            ->with('categories', $categories)
            ->with('category', $category->toArray())
            ->with('parent_category', $category->parent)
            ->with('order_now', null)
            ->with('packets', [$subscription->packet])
            ->with('reviews', $reviews)
            ->with('reviews_total', Reviews::where('status', 1)->count())
            ->with('subscription', $subscription);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {
            /** @var Subscription $subscription */
            $subscription = Subscription::where('id', $id)->where('user_id', Auth::id())->first();
            if (empty($subscription)) {
                return redirect(route('profile.subscriptions.index'))->withErrors(__('Subscription does not exist.'));
            }

            $this->validate($request, [
                'username' => 'required|string',
                'posts' => 'required|integer|min:1',
                'qtyMin' => 'required|integer|lte:qtyMax',
                'qtyMax' => 'required|integer|gte:qtyMin'
            ]);

            $this->validate($request, [
                'qtyMin' => [function ($attribute, $value, $fail) use ($subscription) {
                    if ($value < $subscription->packet->min) {
                        $fail(__('Min count by current packet:') . ' ' . $subscription->packet->min);
                    }
                }],
                'qtyMax' => [function ($attribute, $value, $fail) use ($subscription) {
                    if ($value > $subscription->packet->max) {
                        $fail(__('Max count by current packet:') . ' ' . $subscription->packet->max);
                    }
                }]
            ]);

            $subscription->username = $request->get('username');
            $subscription->posts = $request->get('posts');
            $subscription->qty_min = $request->get('qtyMin');
            $subscription->qty_max = $request->get('qtyMax');
            $subscription->save();

            return redirect(route('profile.subscriptions.index'))->with('success', __('Subscription has updated successfully.'));
        } catch (ValidationException $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->validator->errors()->getMessages());
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function close($id = null)
    {
        try {
            if (empty($id)) {
                $subscription = Subscription::where('user_id', Auth::id())
                    ->where('type', 'PremiumAccount')
                    ->whereIn('status', ['new', 'active', 'Active'])
                    ->first();
                if (empty($subscription)) {
                    Auth::user()->updatePremiumStatus(false);
                    return Response::json(['success' => true], 200);
                }
            } else {
                $subscription = Subscription::where('id', $id)->where('user_id', Auth::id())->first();
            }
            if (empty($subscription)) {
                throw new \Exception(__('Subscription does not exist.'));
            }

            UnitpayModule::closeSubscription($subscription);

            return Response::json(['success' => true], 200);
        } catch (\Exception $e) {
            return Response::json(['success' => true, 'message' => $e->getMessage()], 500);
        }
    }
}
