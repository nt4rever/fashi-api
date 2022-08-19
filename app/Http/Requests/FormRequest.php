<?php

namespace App\Http\Requests;

use App\Http\Resources\API;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class FormRequest extends LaravelFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    abstract public function authorize();

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json([
                'errors' => $errors,
                'status' => API::STATUS_VALIDATE_FAILED
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    protected function failedAuthorization()
    {
        return response()->json([
            'message' => 'Action unauthorized!',
            'status' => API::STATUS_ATHORIZE_FAILED
        ], JsonResponse::HTTP_ACCEPTED);
    }
}
