<?php

namespace App\Http\Requests;

use App\Models\Sku;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CartCheckoutRequest extends FormRequest
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
            'product_id'  => 'required|integer|exists:products,id',
            'sku_id'      => [
                'required',
                'integer',
                'exists:skus,id',
                function ($attribute, $value, $fail) {
                    $productId = $this->input('product_id');
                    $skuExists = Sku::where('id', $value)
                                    ->where('product_id', $productId)
                                    ->exists();

                    if (!$skuExists) {
                        $fail('The selected SKU does not belong to the specified product.');
                    }
                },
            ], 'quantity' => 'required|integer|min:1',
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
            'product_id.integer'  => 'Product ID must be an integer',
            'product_id.exists'   => 'Product ID does not exist',

            'sku_id.required' => 'SKU ID is required',
            'sku_id.integer'  => 'SKU ID must be an integer',
            'sku_id.exists'   => 'SKU ID does not exist',

            'quantity.required' => 'Quantity is required',
            'quantity.integer'  => 'Quantity must be an integer',
            'quantity.min'      => 'Quantity must be at least 1',
        ];
    }
}
