<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests\Telegram;

use App\Rules\Telegram\RuleTelegramKeywordExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class RequestCreateTelegramBot
 * @package App\Http\Requests\Telegram
 *
 * @property string token
 * @property string keyword
 * @property string certificate
 * @property integer max_connections
 */
class RequestCreateTelegramBot extends FormRequest
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
            'token'           => 'required|string',
            'keyword'         => ['required', 'string', new RuleTelegramKeywordExists()],
//            'certificate'     => 'string',
            'max_connections' => 'integer|min:1|max:1000',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'token.required'          => __('Token is required'),
            'token.string'            => __('Token must be string'),

            'keyword.required'        => __('Keyword is required'),
            'keyword.string'          => __('Keyword must be string'),

//            'certificate.string'      => __('Certificate must be string'),

            'max_connections.integer' => __('Max connections must be numeric'),
            'max_connections.min'     => __('Max connections min value is 1'),
            'max_connections.max'     => __('Max connections max connections is 1000'),
        ];
    }
}