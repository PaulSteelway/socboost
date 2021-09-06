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
 * Class RequestUpdateTelegramBot
 * @package App\Http\Requests\Telegram
 *
 * @property string id
 * @property string token
 * @property string keyword
 * @property string certificate
 * @property integer max_connections
 */
class RequestUpdateTelegramBot extends FormRequest
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
            'id'              => 'required|string|exists:telegram_bots,id',
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
            'id.required'             => __('BOT ID is required'),
            'id.string'               => __('BOT ID must be string'),
            'id.exists'               => __('BOT ID is not exists'),

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