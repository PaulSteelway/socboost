<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RequestFaqStore
 * @package App\Http\Requests
 *
 * @property string lang_id
 * @property string title
 * @property string text
 */
class RequestFaqStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lang_id' => 'bail|required|exists:languages,id',
            'title'   => 'required|max:255|min:3',
            'text'    => 'required|min:2',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'lang_id.required' => __('Language is required'),
            'lang_id.exists'   => __('Language is not exists'),

            'title.required'   => __('Title is required'),
            'title.max'        => __('Title maximum length is 255 symbols'),
            'title.min'        => __('Title minimum length is 3 symbols'),

            'text.required'    => __('Text is required'),
            'text.min'         => __('Text minimum length is 2 symbols'),
        ];
    }
}
