<?php

namespace App\Http\Requests\Sku;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSkuRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'code'       => 'required|string|unique:skus,code',
            'amount'     => 'required|integer|min:0',
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
            'product_id.required' => 'Product ID is required',
            'product_id.exists'   => 'Product ID does not exist',
            'code.required'       => 'Code is required',
            'code.string'         => 'Code must be a string',
            'code.unique'         => 'Code already exists',
            'amount.required'     => 'Amount is required',
            'amount.integer'      => 'Amount must be an integer',
            'amount.min'          => 'Amount must be at least 0',
        ];
    }
}
