<?php

namespace App\Http\Requests;

class AddRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required',
            'roles'    => 'required|array|min:1|max:3',
            'rolse.*' => 'required|string|distinct|max:2'
        ];
    }
}