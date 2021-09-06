<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RequestPenaltyUser
 * @package App\Http\Requests
 *
 * @property string wallet_id
 * @property string user_id
 * @property float amount
 */
class RequestPenaltyUser extends FormRequest
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
            'wallet_id' => ['required'],
            'user_id'   => ['required'],
            'amount'    => ['required'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'wallet_id.required' => __('wallet_id is required'),
            'user_id.required'   => __('user_id is required'),
            'amount.required'    => __('Amount is required'),
        ];
    }
}
