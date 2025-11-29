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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product')->id;

        return [
            'name' => 'required|string|max:255',
            'variant' => 'nullable|string|max:255',
            'photos' => 'required|array|max:4',
            'photos.*' => 'image|max:7168', // 7MB max
            'finger_print' => [
                'required',
                'string',
                Rule::unique('camera_samples')->where(function ($query) use ($productId) {
                    return $query->where('product_id', $productId);
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'finger_print.unique' => 'You have already submitted camera samples for this product.',
            'finger_print.required' => 'Unable to identify your device. Please try again.',
        ];
    }
}
