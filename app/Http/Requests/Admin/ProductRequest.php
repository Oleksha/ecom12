<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'category_id' => 'required',
            'brand_id' => 'required',
            'product_name' => 'required|max:200',
            'product_code' => 'required|max:30',
            'product_price' => 'required|numeric|gt:0',
            'product_color' => 'required|max:200',
            'family_color' => 'required|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Обязательно укажите название категории.',
            'brand_id.required' => 'Обязательно укажите название бренда.',
            'product_name.required' => 'Обязательно укажите название продукта.',
            'product_code.required' => 'Обязательно укажите код продукта.',
            'product_price.required' => 'Обязательно укажите цену продукта.',
            'product_price.numeric' => 'Цена продукта должна быть корректной.',
            'product_color.required' => 'Обязательно укажите цвет продукта.',
            'family_color.required' => 'Обязательно укажите семейство цветов продукта.',
        ];
    }
}
