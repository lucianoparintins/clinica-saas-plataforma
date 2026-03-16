<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'doctor_id' => ['required', 'exists:doctors,id'],
            'patient_id' => ['required', 'exists:patients,id'],
            'scheduled_at' => [
                'required',
                'date',
                'after:now',
                function ($attribute, $value, $fail) {
                    $exists = Appointment::where('doctor_id', $this->doctor_id)
                        ->where('scheduled_at', $value)
                        ->where('status', '!=', 'cancelled')
                        ->exists();

                    if ($exists) {
                        $fail('Este médico já possui uma consulta agendada para este horário.');
                    }
                },
            ],
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled', 'completed'])],
            'notes' => ['nullable', 'string'],
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
            'doctor_id.required' => 'O médico é obrigatório.',
            'doctor_id.exists' => 'O médico selecionado é inválido.',
            'patient_id.required' => 'O paciente é obrigatório.',
            'patient_id.exists' => 'O paciente selecionado é inválido.',
            'scheduled_at.required' => 'A data e hora são obrigatórias.',
            'scheduled_at.date' => 'A data e hora informadas são inválidas.',
            'scheduled_at.after' => 'A consulta deve ser agendada para uma data futura.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status selecionado é inválido.',
        ];
    }
}
