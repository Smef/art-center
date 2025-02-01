<?php

namespace App\Http\Requests;

use App\Helpers\StateHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyUpdateRequest extends FormRequest
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

        $request = request();

        return [
            'name' => ['required', Rule::unique('companies')->ignore($request->company)],
            'website' => 'nullable',
            'phone' => 'nullable',
            'address_street' => 'nullable',
            'address_city' => 'required_with:address_street',
            'address_state' => ['required_with:address_street', 'max:2', 'nullable', Rule::in(StateHelper::getStateAbbreviations())],
            'address_zip' => ['required_with:address_street', 'nullable', 'min:5'],
            'logo' => ['nullable', 'image', 'max:1024'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Company name must be unique.',
            'name.required' => 'Company name is required.',
            'address_city.required_with' => 'City is required.',
            'address_state.required_with' => 'State is required.',
            'address_state.max' => 'State must be 2 characters.',
            'address_zip.required_with' => 'Zip code is required.',
        ];
    }
}
