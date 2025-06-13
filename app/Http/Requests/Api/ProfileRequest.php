<?php

namespace App\Http\Requests\Api;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function failedValidation(Validator $validator) {
        $record = ApiResponse::sendResponse('Validation Errors',422,$validator->errors());
        throw new ValidationException($validator,$record);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|min:6|max:20",
            "email" => "required|email",
            "address" => "required|string|max:50",
            "phone" => "required|string|max:11",
            "image" => "image|mimes:png,jpg,jpeg",
            "current_password" => "string|max:20|min:8",
            "password" => "nullable|string|confirmed|min:8|max:20"
        ];
    }
}
