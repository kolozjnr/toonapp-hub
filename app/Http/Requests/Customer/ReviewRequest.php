<?php

namespace App\Http\Requests\Customer;

use App\Models\Plugin;
use App\Models\User;
use App\Services\PurchaseHistory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Only the shopper who bought the plugin may rate it, so the stars on a
     * review always come from a real owner.
     */
    public function authorize(): bool
    {
        $plugin = $this->route('plugin');
        $user = $this->user();

        if (! $user instanceof User || ! $plugin instanceof Plugin) {
            return false;
        }

        return app(PurchaseHistory::class)
            ->plugins($user)
            ->pluck('plugin_id')
            ->contains($plugin->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
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
            'rating.required' => 'Pick a star rating before you submit.',
            'rating.integer' => 'Pick a star rating before you submit.',
            'rating.between' => 'Pick a rating between 1 and 5 stars.',
            'comment.max' => 'Keep your review under 1,000 characters.',
        ];
    }
}
