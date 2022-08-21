<?php

namespace App\Http\Requests\Brand;

use App\Http\Requests\FormRequest;

class UpdateBrandRequest extends FormRequest
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
            'id' => 'required|integer|min:1',
            'name' => 'required|string',
            'status' => 'required|integer|min:0|max:1',
            'desc' => 'nullable|string',
        ];
    }
}
