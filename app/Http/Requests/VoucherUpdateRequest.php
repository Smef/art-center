<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoucherUpdateRequest extends FormRequest
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
            'number' => ['nullable'],
            'po_number' => ['nullable'],
            'date' => ['required'],
            'file' => ['nullable', 'file', 'max:1024', 'mimes:pdf'],
            'lines' => ['nullable', 'array'],
            'lines.*.description' => ['required_with:lines', 'string'],
            'lines.*.quantity' => ['required_with:lines', 'numeric'],
            'lines.*.each' => ['required_with:lines', 'numeric'],

        ];
    }
}
