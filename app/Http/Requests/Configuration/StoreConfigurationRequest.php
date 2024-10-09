<?php

namespace App\Http\Requests\Configuration;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreConfigurationRequest extends FormRequest
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
            'product_id'   => 'required|exists:products,id',
            'rule_type'    => 'required|string|max:255',
            'rule_details' => 'required|json',
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
            'product_id.required'   => 'Product is required.',
            'product_id.exists'     => 'The selected product does not exist.',
            'rule_type.required'    => 'Rule type is required.',
            'rule_details.required' => 'Rule details must be provided in JSON format.',
        ];
    }
}
