<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryRequest extends FormRequest
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
            'category_name' => 'required',
            'url' => 'required|regex:/^[\pL\s\-]+$/u',
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'category_name.required' => 'Category Name is required.',
            'url.required' => 'Category Url is required.',
        ];
    }

    /**
     * Prepare request before validation
     */
    protected function prepareForValidation(): void
    {
        if ($this->route('category')) {
            $this->merge([
                'id' => $this->route('category')
            ]);
        }
    }

    /**
     * Custom validator logic for checking URL uniqueness
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $categoryCount = Category::where('url', $this->input('url'));
            if ($this->filled('id')) {
                $categoryCount->where('id', '!=', $this->input('id'));
            }
            if ($categoryCount->count() > 0) {
                $validator->errors()->add('url', 'Category already exists.');
            }
        });
    }

    /**
     * Customize validation failure response.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
