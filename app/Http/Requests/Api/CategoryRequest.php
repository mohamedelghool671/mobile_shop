<?php

namespace App\Http\Requests\Api;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CategoryRequest extends FormRequest
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
        if (request()->method === 'PUT') {
            $categoryId = request()->route('category')?->id ?? null;
                return [
                    "name" => "required|string|unique:categories,name," . $categoryId,
                    'category_image' => "image|mimes:png,jpg,jpeg"
                ];
        }
        return [
                "name" => "required|string",
                'category_image' => "required|image|mimes:png,jpg,jpeg"
        ];
    }
}
