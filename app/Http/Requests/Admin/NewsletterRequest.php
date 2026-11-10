<?php

namespace App\Http\Requests\Admin;

use App\Models\Newsletter;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsletterRequest extends FormRequest
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
            'audience' => ['required', 'string', Rule::in(array_keys(Newsletter::AUDIENCE_LABELS))],
            'plugin_id' => [
                'nullable',
                'integer',
                Rule::requiredIf($this->input('audience') === Newsletter::AUDIENCE_PLUGIN),
                'exists:plugins,id',
            ],
            'subject' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:4000'],
            'download_url' => ['nullable', 'url', 'max:2048'],
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
            'audience.in' => 'Choose who should get this update.',
            'plugin_id.required' => 'Pick the plugin whose owners should get this.',
            'plugin_id.exists' => 'That plugin is no longer in the store.',
            'plugin_id.integer' => 'Pick the plugin whose owners should get this.',
            'subject.required' => 'Add a subject.',
            'message.required' => 'Write your message.',
            'download_url.url' => 'That download link is not a valid URL.',
        ];
    }
}
