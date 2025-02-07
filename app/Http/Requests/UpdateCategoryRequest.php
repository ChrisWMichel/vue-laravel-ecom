<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'name' => 'required|max:255|unique:categories,name,'.$this->route('category')->id
        ];
    }

    public function attributes()
    {
        return [
            'name.required' => 'The category name cannot be empty.',
            'name.max' => 'The category name cannot exceed 255 characters.',
            'name.unique' => 'The category name must be unique.',
        ];
    }
}
