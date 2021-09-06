<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class RequestRateStoreUpdate
 * @package App\Http\Requests
 *
 * @property string name
 * @property string currency_id
 * @property float min
 * @property float max
 * @property float daily
 * @property float overall
 * @property integer duration
 * @property float payout
 * @property float reinvest
 * @property integer autoclose
 * @property integer active
 */
class RequestRateStoreUpdate extends FormRequest
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
            'name'        => 'required|min:3|max:60',
            'currency_id' => 'required|exists:currencies,id',
            'min'         => 'numeric|min:0',
            'max'         => 'numeric|min:0',
            'daily'       => 'numeric|min:0',
            'overall'     => 'numeric|min:0',
            'duration'    => 'required|numeric|min:1',
            'payout'      => 'nullable|numeric|min:0',
            'reinvest'    => 'nullable|int',
            'autoclose'   => 'nullable|int',
            'active'      => 'nullable|int',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'        => __('Name is required.'),
            'name.min'             => __('Minimum name length 3 symbols.'),
            'name.max'             => __('Maximum name length 60 symbols.'),

            'currency_id.required' => __('Currency id is required.'),
            'currency_id.exists'   => __('This currency is not exists.'),

            'min.numeric'          => __('Minimum investment amount have to numeric.'),
            'min.min'              => __('Minimum investment amount have to be greater than 0.'),

            'max.numeric'          => __('Maximum investment amount have to be numeric.'),
            'max.min'              => __('Maximum investment amount have to be greater than 0.'),

            'daily.numeric'        => __('Daily percent have to be numeric.'),
            'daily.min'            => __('Daily percent have to be greater than 0.'),

            'overall.numeric'      => __('Overall have to be numeric.'),
            'overall.min'          => __('Overall have to be grater than 0.'),

            'duration.numeric'     => __('Duration have to be numeric.'),
            'duration.required'    => __('Duration is required.'),
            'duration.min'         => __('Duration have to be greater than 0.'),

            'payout.numeric'       => __('Payout have to be numeric.'),
            'payout.min'           => __('Payout have to be greater than 0.'),

            'reinvest.int'         => __('Reinvest have to be boolean.'),

            'autoclose.int'        => __('Auto close have to be boolean.'),

            'active.int'           => __('Active option have to be boolean.'),
        ];
    }
}
