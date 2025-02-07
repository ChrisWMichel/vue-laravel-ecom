<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
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
            'name' => 'required|max:255|unique:products',
            'desc' => 'required',
            'qty' => 'required|numeric',
            'price' => 'required|numeric',
            'color_ids' => 'required|exists:colors,id',
            'color_ids.*' => 'exists:colors,id',
            'size_ids' => 'required|exists:sizes,id',
            'size_ids.*' => 'exists:sizes,id',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'first_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'second_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'third_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
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
            'thumbnail.max' => 'The thumbnail must not be greater than 2MB',
            'thumbnail.image' => 'The thumbnail must be an image',
            'first_image.max' => 'The first image must not be greater than 2MB',
            'first_image.image' => 'The first image must be an image',
            'second_image.max' => 'The second image must not be greater than 2MB',
            'second_image.image' => 'The second image must be an image',
            'third_image.max' => 'The third image must not be greater than 2MB',
            'third_image.image' => 'The third image must be an image',
        ];
    }
}
