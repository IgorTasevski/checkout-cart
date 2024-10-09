<?php

namespace App\Http\Requests\Sku;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSkuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'sometimes|exists:products,id',
            'code' => 'sometimes|string|unique:skus,code,' . $this->route('id'),
            'amount' => 'sometimes|integer|min:0',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'product_id.exists' => 'Product id does not exist',
            'code.string' => 'Code must be a string',
            'code.unique' => 'Code has already been taken',
            'amount.integer' => 'Amount must be an integer',
            'amount.min' => 'Amount must be at least 0',
        ];
    }
}
