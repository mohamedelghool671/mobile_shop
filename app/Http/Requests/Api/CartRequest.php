<?php

namespace App\Http\Requests\Api;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CartRequest extends FormRequest
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
        return [
         'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:cart_items,id',
            'items.*.quantity' => 'required|integer'
        ];
    }

    public function messages()
    {
       return  [
            "items.*.item_id.exists" => "item not found"
       ];
    }
}
