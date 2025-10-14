<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DetailRequest extends FormRequest
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
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'mobile' => 'required|numeric|digits:11',
            'image' => 'image',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.regex' => 'Valid name is required',
            'name.max' => 'Maximum length is 255 characters',
            'mobile.required' => 'Mobile is required',
            'mobile.numeric' => 'Valid mobile is numeric',
            'mobile.digits' => 'Valid mobile is digits',
            'image.image' => 'Valid image is required',
        ];
    }
}
