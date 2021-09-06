<?php

namespace App\Http\Requests;

use App\Rules\WalletAmount;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ReferralPayoutRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cardNumber' => ['required'],
            'amount' => ['required', new WalletAmount, 'numeric', 'min:1'],
            'cardType' => ['required', 'in:Visa,Mastercard'],
        ];
    }
}
