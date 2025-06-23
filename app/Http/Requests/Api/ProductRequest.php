<?php

namespace App\Http\Requests\Api;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ProductRequest extends FormRequest
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

        if (request()->method() === 'PUT') {
            return [
                "name" => "required|string|max:50",
                "description" => "required|string|max:500",
                "image" => "mimes:png,jpg,jpeg,webp|image",
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
                "price" => "required|numeric",
                "quantity" => "required|integer",
                "category_id" => "required|exists:categories,id"
            ];
        }
        return [
        "name" => "required|string|max:50",
        "description" => "required|string|max:500",
        "image" => "required|mimes:png,jpg,jpeg,webp|image",
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        "price" => "required|numeric",
        "quantity" => "required|integer",
        "category_id" => "required|exists:categories,id"
        ];

    }
}
