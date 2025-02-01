<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use App\Helpers\StateHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectUpdateRequest extends FormRequest
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
            'name' => ['required', Rule::unique('projects')->ignore($this->project)],
            'description' => 'nullable',
            'company_id' => 'required|exists:companies,id',
            'start_date' => ['required'],
            'address_street' => 'nullable',
            'address_city' => 'required_with:address_street',
            'address_state' => ['required_with:address_street', 'max:2', 'nullable', Rule::in(StateHelper::getStateAbbreviations())],
            'address_zip' => 'required_with:address_street|nullable|min:5',
            'status' => ['required', Rule::in([ProjectStatus::Active->value, ProjectStatus::Completed->value, ProjectStatus::Cancelled->value])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'company_id.required' => 'Company is required.',
            'company_id.exists' => 'Company does not exist.',
            'start_date.required' => 'Start date is required.',
            'address_city.required_with' => 'City is required.',
            'address_state.required_with' => 'State is required.',
            'address_state.max' => 'State must be 2 characters.',
            'address_zip.required_with' => 'Zip code is required.',
        ];
    }
}
