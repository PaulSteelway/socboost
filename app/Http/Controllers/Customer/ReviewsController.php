<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestReview;
use App\Models\Reviews;
use Illuminate\Support\Facades\Auth;
use Validator;

class ReviewsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $direction = app()->getLocale() == 'en' ? 'desc' : 'asc';
        $reviews = Reviews::where('status', 1)
            ->where('type_id', 1)
            ->orderBy('lang_type', $direction)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('customer.ReviewsController')->with('reviews', $reviews);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function videoReview()
    {
        $direction = app()->getLocale() == 'en' ? 'desc' : 'asc';
        $reviews = Reviews::where('status', 1)
            ->where('type_id', 2)
            ->orderBy('lang_type', $direction)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('customer.ReviewsVideo')->with('reviews', $reviews);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form()
    {
        return view('customer.ReviewsForm');
    }

    /**
     * @param RequestReview $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(RequestReview $request)
    {

        $validator = Validator::make(request()->all(), [
            'g-recaptcha-response' => 'recaptcha',
        ]);
        
        // check if validator fails
        if($validator->fails()) {
            return Response::json(['status' => __('Not valid'), 'data' => $data], 200);
            $errors = $validator->errors();
        }

        $ip =  $_SERVER["REMOTE_ADDR"];
        $attempts = \DB::table('reviews')->where('user_ip', '=', $ip)->orderBy('time', 'DESC')->get()->first();

        if(!empty($attempts) && (time()-$attempts->time) < 600) {
            return redirect()->back()->with('fail', 'dhda');
        }

        $input = $request->all();

        $link = null;
        if (!empty($input['video'])) {
            $path = explode('https://youtu.be/', $request->get('video'));
            if (!empty($path[1])) {
                $link = "https://www.youtube.com/embed/{$path[1]}";
            }
        }

        Reviews::create([
            'user_id' => Auth::id(),
            'text' => empty($input['text']) ? null : $input['text'],
            'video' => $link,
            'type_id' => $request->type_id,
            'user_ip'   => $ip,
            'time'  => time()
        ]);

        return redirect()->back()->with('success', __('Your review has been accepted and will be published on the site according to the results of moderation.'));
    }
}
