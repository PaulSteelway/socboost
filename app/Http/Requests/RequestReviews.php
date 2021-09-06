<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RequestReviews
 * @package App\Http\Requests
 *
 * @property string lang_id
 * @property string name
 * @property string text
 */
class RequestReviews extends FormRequest
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
//            'lang_id' => 'bail|required|exists:languages,id',
//            'name'    => 'required|max:191|min:3',
            'text'    => 'required|min:3',
            'video'   => 'nullable|url',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
//            'lang_id.required' => __('Language ID is required.'),
//            'lang_id.exists'   => __('This language is not exists.'),

//            'name.required'    => __('Name is required.'),
//            'name.max'         => __('Maximum name length is 255 symbols.'),
//            'name.min'         => __('Minimum name length is 3 symbols.'),

            'text.required'    => __('Text is required.'),
            'text.min'         => __('Minimum text length is 3 symbols.'),

            'video.url'    => __('Video URL should be valid.'),
        ];
    }
}
