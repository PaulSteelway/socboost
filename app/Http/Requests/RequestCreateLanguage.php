<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RequestCreateLanguage
 * @package App\Http\Requests
 *
 * @property string name
 * @property string code
 * @property string original_name
 */
class RequestCreateLanguage extends FormRequest
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
            'name'          => 'bail|required|alpha|unique:languages|min:2',
            'code'          => 'required|alpha||unique:languages|min:2',
            'original_name' => 'string|nullable|min:2'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'        => __('Language name is required'),
            'name.alpha'           => __('Language name must be with ALPHA symbols'),
            'name.unique'          => __('Language name must be unique'),
            'name.min'             => __('Language name minimum length is 2 symbols'),

            'code.required'        => __('Language code is required'),
            'code.alpha'           => __('Language code must be with ALPHA symbols'),
            'code.unique'          => __('Language code must be unique'),
            'code.min'             => __('Language code minimum length is 2 symbols'),

            'original_name.string' => __('Language original name must be as string'),
            'original_name.min'    => __('Language original name minimum length is 2 symbols'),
        ];
    }
}
