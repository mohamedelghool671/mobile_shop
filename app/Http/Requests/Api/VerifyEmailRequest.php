<?php

namespace App\Http\Requests\Api;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class VerifyEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        if (request()->is('api/*')) {
            $response = ApiResponse::sendResponse('Validation Errors',422,$validator->errors());
            throw new ValidationException($validator,$response);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if (request()->is('api/verfiy') && request()->method() === 'POST')  {
            return [
                'email_verfiy_code' => "required|exists:users,email_verfiy_code|max:6|min:6"
            ];
        }
        return [
            'email' => "required|exists:users,email|email"
        ];
    }
}
