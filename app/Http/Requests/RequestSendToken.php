<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests;

use App\Rules\RuleHasPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class RequestSendToken
 * @package App\Http\Requests
 *
 * @property string choise
 */
class RequestSendToken extends FormRequest
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
            'choise' => ['required', $this->isPhone()],
        ];
    }

    /**
     * @return RuleHasPhone|string
     */
    private function isPhone()
    {
        if ('phone' === request()->get('choise')) {
            return new RuleHasPhone();
        }

        return '';
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'choise.required' => __('Choise is required.'),
        ];
    }
}
