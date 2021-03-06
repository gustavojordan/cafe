<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsumerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return  [
            'consumption_limit' => ['required'],
            'user_id' => ['required', 'unique:consumer', 'exists:user,user_id'],
            'favorite_drinks.*' => ['required', 'exists:drink,drink_id'],
        ];
    }
}
