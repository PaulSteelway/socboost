<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Constants;

/**
 * Class RequestEnterToken
 * @package App\Http\Requests
 *
 * @property string code
 */
class RequestEnterToken extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => ['required', sprintf('regex:%s', Constants::TOKEN_FORMAT)],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'code.required' => __('Token is required'),
            'code.regex'    => __('Token must be in format:').' '.Constants::TOKEN_FORMAT,
        ];
    }
}
