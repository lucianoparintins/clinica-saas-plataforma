<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('doctors', 'email')->ignore($this->doctor)],
            'crm' => ['required', 'string', 'max:20', Rule::unique('doctors', 'crm')->ignore($this->doctor)],
            'specialty' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ];
    }
}
