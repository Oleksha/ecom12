<?php

namespace App\Http\Requests\Admin;

use App\Models\Brand;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BrandRequest extends FormRequest
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
            'name' => 'required',
            'url' => 'required|regex:/^[\pL\s\-]+$/u',
        ];
    }

    /**
     * Пользовательские сообщения об ошибках проверки.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Обязательно укажите название бренда.',
            'url.required' => 'Обязательно укажите URL бренда.',
        ];
    }

    /**
     * Подготовка запроса перед проверкой.
     */
    protected function prepareForValidation(): void
    {
        if ($this->route('brand')) {
            $this->merge([
                'id' => $this->route('brand'),
            ]);
        }
    }

    /**
     * Пользовательская логика для проверки уникальности URL.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $brandCount = Brand::where('url', $this->input('url'));
            if ($this->filled('id')) {
                $brandCount->where('id', '!=', $this->input('id'));
            }
            if ($brandCount->count() > 0) {
                $validator->errors()->add('url', 'Бренд уже существует!');
            }
        });
    }

    /**
     * Настройте ответ на ошибку проверки.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()->withErrors($validator)->withInput()
        );
    }
}
