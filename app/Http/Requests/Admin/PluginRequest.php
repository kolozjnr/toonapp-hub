<?php

namespace App\Http\Requests\Admin;

use App\Models\Plugin;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PluginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['superadmin', 'admin']) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:60'],
            'tagline' => ['nullable', 'string', 'max:90'],
            'description' => ['nullable', 'string', 'max:2000'],
            'features' => ['nullable', 'string', 'max:800'],
            'youtube_url' => ['nullable', 'url', 'max:2048'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'images' => ['nullable', 'array', 'max:4'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'image_titles' => ['nullable', 'array', 'max:4'],
            'image_titles.*' => ['nullable', 'string', 'max:60'],
            'image_texts' => ['nullable', 'array', 'max:4'],
            'image_texts.*' => ['nullable', 'string', 'max:600'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer', 'between:0,3'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'old_price' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'category' => ['required', Rule::in(Plugin::CATEGORIES)],
            'badge' => ['nullable', Rule::in(Plugin::BADGES)],
            'hosts' => ['nullable', 'array'],
            'hosts.*' => [Rule::in(array_keys(Plugin::HOSTS))],
            'version' => ['nullable', 'string', 'max:20'],
            'requirements' => ['nullable', 'string', 'max:60'],
            'download_url' => ['nullable', 'url', 'max:2048'],
            'is_live' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cover.uploaded' => 'The cover image could not be uploaded. Check that it is smaller than the server limit and try again.',
            'cover.image' => 'The cover must be a valid image (JPG, PNG, WebP or GIF).',
            'cover.max' => 'The cover image must be 4 MB or smaller.',
            'images.*.uploaded' => 'A gallery image could not be uploaded. Check its size and try again.',
            'images.*.image' => 'Every gallery file must be a valid image (JPG, PNG, WebP or GIF).',
            'images.*.max' => 'Each gallery image must be 4 MB or smaller.',
            'image_titles.*.max' => 'Each image title must be 60 characters or fewer.',
            'image_texts.*.max' => 'Each image description must be 600 characters or fewer.',
        ];
    }
}
