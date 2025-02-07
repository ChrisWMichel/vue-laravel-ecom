<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'required|max:255|unique:products,name,'.$this->product->id,
            'desc' => 'required',
            'qty' => 'required|numeric',
            'price' => 'required|numeric',
            'color_ids' => 'required|exists:colors,id',
            'size_ids' => 'required|exists:sizes,id',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',

        ];
    }

    public function attributes()
    {
        return [
            'name.required' => 'The brand name cannot be empty.',
            'name.unique' => 'The brand name must be unique.',
            'color_ids.required' => 'Please select a color.',
            'size_ids.required' => 'Please select a size.',
            'category_id.required' => 'Please select a category.',
            'brand_id.required' => 'Please select a brand.',
            'desc.required' => 'The description cannot be empty.',
            'qty.required' => 'The quantity cannot be empty.',
            'price.required' => 'The price cannot be empty.',

        ];
    }
}
