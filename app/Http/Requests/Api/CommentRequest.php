<?php

namespace App\Http\Requests\Api;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CommentRequest extends FormRequest
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
        if (request()->method() === 'PUT') {
            return
            [
               "content" => "required|string|min:5|max:50",
               "rating" => "nullable|integer|min:1|max:5"
           ];
        }
        return
         [
            "content" => "required|string|min:5|max:50",
            "product_id" => "required|exists:products,id",
            "rating" => "nullable|integer|min:1|max:5"
        ];
    }
}
