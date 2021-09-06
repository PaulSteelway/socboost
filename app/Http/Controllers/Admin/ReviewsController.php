<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RequestReviews;
use App\Models\Reviews;
use App\Http\Controllers\Controller;

class ReviewsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $reviews = Reviews::all();
        return view('admin.reviews.index')->with('reviews', $reviews);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.reviews.create');
    }

    /**
     * @param RequestReviews $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RequestReviews $request)
    {
        if (Reviews::create($request->all())) {
            return back()->with('success', __('Review has been added'));
        }
        return back()->with('error', __('Unable to create review'))->withInput();
    }

    /**
     * @param Reviews $review
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Reviews $review)
    {
        return view('admin.reviews.edit', [
            'review' => $review
        ]);
    }

    /**
     * @param RequestReviews $request
     * @param Reviews $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RequestReviews $request, Reviews $review)
    {
        if ($review->update($request->all())) {
            return back()->with('success', __('Review has been updated'));
        }
        return back()->with('error', __('Unable to update review'))->withInput();
    }

    /**
     * @param Reviews $review
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Reviews $review)
    {
        if ($review->delete()) {
            return back()->with('success', __('Review has been deleted'));
        }
        return back()->with('error', __('Unable to delete review'));
    }
}
