<?php

namespace App\Http\Requests\Api;

use App\Helpers\ApiResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateAddressRequest extends FormRequest
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
        $id = request()->route('addres_id');
        return [
            'address'   => ['required','string','max:255', Rule::unique('addresses', 'address')
                    ->where(function ($query) {
                        return $query->where('user_id', auth()->id());
                    })
                    ->ignore($id ?? null),],
            'phone'     => 'required|string|max:20',
            'country'   => 'required|string|max:100',
            'city'   => 'required|string|max:100',
            'area'      => 'required|string|max:100',
        ];
    }
}
