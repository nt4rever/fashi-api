<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;

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
            'id' => 'required|integer',
            'roles'    => 'required|array|min:1|max:3',
            'roles.*' => 'required|string|distinct'
        ];
    }
}