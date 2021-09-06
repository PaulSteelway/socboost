<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests;

use App\Rules\RuleEnoughBalance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class RequestBonusUser
 * @package App\Http\Requests
 *
 * @property string wallet_id
 * @property string user_id
 * @property float amount
 */
class RequestBonusUser extends FormRequest
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
            'wallet_id.required' => __('Wallet is required'),
            'user_id.required'   => __('User must be selected'),
            'amount.required'    => __('Amount is required'),
        ];
    }
}
