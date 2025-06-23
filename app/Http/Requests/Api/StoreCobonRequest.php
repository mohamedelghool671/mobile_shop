<?php

namespace App\Http\Requests\Api;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreCobonRequest extends FormRequest
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
            $response = ApiResponse::sendResponse('Validation Errors',422,$validator->errors());
            throw new ValidationException($validator,$response);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'cobon' => ['required', 'string', 'max:255', 'unique:cobons,cobon'],
        'desc' => ['required', 'string', 'max:255'],
        'name' => ['required', 'string', 'max:255'],
        'value' => ['required', 'numeric', 'min:1'],
        'number_uses' => ['required', 'integer', 'min:1'],
        'expire_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }
}
