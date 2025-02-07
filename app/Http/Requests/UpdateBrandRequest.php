<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
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
            'name' => 'required|max:255|unique:brands,name,'.$this->route('brand')->id
        ];
    }

    public function attributes()
    {
        return [
            'name.required' => 'The brand name cannot be empty.',
            'name.max' => 'The brand name cannot exceed 255 characters.',
            'name.unique' => 'The brand name must be unique.',
        ];
    }
}
