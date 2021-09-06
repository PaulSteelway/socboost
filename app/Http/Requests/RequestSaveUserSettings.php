<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests;

use App\Rules\RuleLoginIsCorrect;
use App\Rules\RulePartnerIdExists;
use App\Rules\RulePhoneIsCorrect;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use Webpatser\Countries\Countries;

/**
 * Class RequestSaveUserSettings
 * @package App\Http\Requests
 *
 * @property string email
 * @property integer partner_id
 * @property string phone
 * @property string skype
 * @property string login
 * @property string name
 * @property string country
 */
class RequestSaveUserSettings extends FormRequest
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
        $rules = [
//            'email'      => 'required_without:phone|nullable|email',
//            'partner_id' => !empty(Input::get('partner_id')) ? ['numeric', new RulePartnerIdExists] : '',
//            'phone'      => 'required_without:email|nullable',
            'telegram'   => '',
            'skype'      => '',
            'lastname'   => '',
//            'login'      => !empty(Input::get('login')) ? [new RuleLoginIsCorrect()] : '',
//            'login'      => Rule::unique('users')->ignore(\Auth::user()->id, 'id'),
            'name'       => '',
            'country'    => 'required|in:' . implode(',', Countries::all()->pluck('iso_3166_2')->toArray()),
        ];
        if(isset($_POST["password"])){
            $rules['password'] = 'min:6|confirmed';
            $rules['password_confirmation'] = 'required|min:6';
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'email.email'        => __('Wrong email format.'),
            'partner_id.numeric' => __('Partner ID have to be numeric.'),
        ];
    }
}
