<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Requests\UserTasks;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class RequestUpdateUserTask
 * @package App\Http\Requests\UserTasks
 *
 * @property string id
 * @property string title
 * @property string description
 * @property float reward_amount
 * @property string reward_payment_system
 * @property Carbon deadline
 * @property string category
 * @property string social_category
 */
class RequestUpdateUserTask extends FormRequest
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
            'id'                    => 'required|string|exists:tasks,id',
            'title'                 => 'required|string|min:3|max:255',
            'description'           => 'required|string|min:3',
            'reward_amount'         => 'numeric',
//            'reward_payment_system' => 'string',
//            'deadline'              => 'required|date_format:m/d/Y h:i A',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'id.required'                  => __('Task ID is required'),
            'id.string'                    => __('Task ID must be string'),
            'id.exists'                    => __('Task ID is not exists'),

            'title.required'               => __('Title is required'),
            'title.string'                 => __('Title must be a string'),
            'title.min'                    => __('Title minimum length is 3 symbols'),
            'title.max'                    => __('Title maximum length is 255 symbols'),

            'description.required'         => __('Description is required'),
            'description.string'           => __('Description must be a string'),
            'description.min'              => __('Description minimum value length is 3 symbols'),

            'reward_amount.float'          => __('Reward amount must be a float'),

//            'reward_payment_system.string' => __('Reward payment system must be a string'),

            'deadline.required'            => __('Deadline is required'),
            'deadline.date_format'         => __('Wrong deadline format'),
        ];
    }
}