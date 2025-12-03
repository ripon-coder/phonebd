<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class StoreCameraSampleRequest extends FormRequest
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
        $productId = $this->route('product')->id;

        return [
            'name' => 'required|string|max:100',
            'variant' => 'nullable|string|max:100',
            'photos' => 'required|array|max:4',
            'photos.*' => 'image|max:7168', // 7MB max
            'finger_print' => [
                'required',
                'string',
                Rule::unique('camera_samples')->where(function ($query) use ($productId) {
                    return $query->where('product_id', $productId);
                }),
            ],
            'ip_address' => [
                'bail',
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (\App\Models\CameraSample::where('ip_address', $value)->where('is_ip_banned', true)->exists()) {
                        $fail('Your IP address has been banned from submitting camera samples.');
                    }
                },
                Rule::unique('camera_samples')->where(function ($query) use ($productId) {
                    return $query->where('product_id', $productId);
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'finger_print.unique' => 'You have already submitted camera samples for this item.',
            'finger_print.required' => 'Unable to identify your device. Please try again.',
            'ip_address.unique' => 'You have already submitted camera samples for this item.',
        ];
    }
}
