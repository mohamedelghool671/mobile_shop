<?php

namespace App\Http\Requests\Api;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;



class OrderRequest extends FormRequest
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
        return [
            "user_first_name" => "required|string",
            "user_last_name" => "required|string"
            ,"phone" => "required|string",
            "email" => "required|email",
            "city" => "required|string",
            "governorate" => "required|string",
            "address" => "required|string",
            "country" => "required|string",
            "postal_code" =>"required|string|min:5|max:5",
            "gift_recipient_phone" => "string",
            "gift_recipient_name" => "string",
            "gift_recipient_city" => "string",
            "gift_recipient_governorate" => "string",
            "gift_recipient_address" => "string",
            "gift_recipient_country" => "string",
            "gift_recipient_postal_code" => "string|min:5|max:5",
        ];
    }
}
