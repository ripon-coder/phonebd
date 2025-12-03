<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'ip_address' => $this->ip(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'review' => 'required|string|max:1000',
            'rating_design' => 'nullable|integer|min:1|max:5',
            'rating_performance' => 'nullable|integer|min:1|max:5',
            'rating_camera' => 'nullable|integer|min:1|max:5',
            'rating_battery' => 'nullable|integer|min:1|max:5',
            'pros' => 'nullable|array',
            'pros.*' => 'nullable|string|max:100',
            'cons' => 'nullable|array',
            'cons.*' => 'nullable|string|max:100',
            'variant' => 'nullable|string|max:100',
            'photos.*' => 'nullable|image|max:1024', // 1MB max
            'finger_print' => [
                'required',
                'string',
                Rule::unique('reviews')->where(function ($query) {
                    return $query->where('product_id', $this->route('product')->id);
                }),
            ],
            'ip_address' => [
                'bail',
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (\App\Models\Review::where('ip_address', $value)->where('is_ip_banned', true)->exists()) {
                        $fail('Your IP address has been banned from submitting reviews.');
                    }
                },
                Rule::unique('reviews')->where(function ($query) {
                    return $query->where('product_id', $this->route('product')->id);
                }),
            ],
            'storage_type' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'finger_print.unique' => 'You have already submitted a review for this item.',
            'finger_print.required' => 'Unable to verify device identity. Please disable ad blockers and try again.',
            'ip_address.unique' => 'You have already submitted a review for this item.',
        ];
    }
}
