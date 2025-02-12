<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'discount' => 'required|numeric',
            'valid_until' => 'required|date'
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Coupon name is required',
            'name.string' => 'Coupon name must be a string',
            'discount.required' => 'Discount is required',
            'discount.numeric' => 'Discount must be a number',
            'valid_until.required' => 'Valid until is required',
            'valid_until.date' => 'Valid until must be a date'
        ];
    }
}
