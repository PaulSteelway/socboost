<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RequestReview
 * @package App\Http\Requests
 *
 * @property string text
 * @property string video
 * @property string captcha
 */
class RequestReview extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'nullable|required_if:type_id,1|min:3',
            'type_id' => 'required',
            'video' => 'nullable|required_if:type_id,2|url|regex:/^https:\/\/youtu.be\//',
//            'captcha' => 'required|captcha',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'text.required_if' => trans('main.emails.request.text_required'),
            'text.min' => trans('validation.min.text'),

            'video.required_if' => trans('validation.video.required_if'),
            'video.regex' => trans('validation.youtube_link'),

//            'captcha.required' => trans('validation.captcha_required'),
//            'captcha.captcha' => trans('validation.captcha_captcha'),
        ];
    }
}
