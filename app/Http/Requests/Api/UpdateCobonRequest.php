<?php

namespace App\Http\Requests\Api;

use App\Helpers\ApiResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateCobonRequest extends FormRequest
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
        $id = request()->route('cobon');
        return [
            'cobon' => ['required', 'string', 'max:255', Rule::unique('cobons', 'cobon')->ignore($id)],
            'value' => ['required', 'numeric', 'min:1'],
             'name' => ['required', 'string', 'max:255'],
            'desc' => ['nullable', 'string', 'max:255'],
            'number_uses' => ['required', 'integer', 'min:1'],
            'expire_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }
}
