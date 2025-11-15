<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
        $rules = [
            'type' => 'required|string|max:255',
            'link' => 'nullable|url|max:500',
            'title' => 'required|string|max:255',
            'alt' => 'nullable|string|max:255',
            'sort' => 'nullable|integer|min:0',
            'status' => 'nullable|in:0,1',
        ];

        // For create or edit - validate image only if uploading
        if ($this->isMethod('post') || $this->hasFile('image')) {
            $rules['image'] = 'required|image|mimes:jpeg,jpg,png,gif|max:2048'; // Max 2Mb
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Please select a banner type.',
            'title.required' => 'Banner title is required.',
            'image.required' => 'Please upload a banner image.',
            'image.image' => 'Uploaded file must be an image.',
            'image.mimes' => 'Allowed image types are jpeg, jpg, png, gif.',
            'image.max' => 'Image size must be less than 2Mb.',
        ];
    }
}
