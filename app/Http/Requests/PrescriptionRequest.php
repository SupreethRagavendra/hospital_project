<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isDoctor();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'medical_record_id' => 'required|exists:medical_records,id',
            'patient_id' => 'required|exists:patients,id',
            'medication_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:100',
            'frequency' => 'required|string|max:100',
            'duration' => 'required|string|max:100',
            'route' => 'required|in:oral,injection,topical,inhalation,other',
            'instructions' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'nullable|boolean',
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
            'medical_record_id.required' => 'Medical record is required.',
            'medical_record_id.exists' => 'The selected medical record does not exist.',
            'patient_id.required' => 'Patient is required.',
            'patient_id.exists' => 'The selected patient does not exist.',
            'medication_name.required' => 'Medication name is required.',
            'dosage.required' => 'Dosage is required.',
            'frequency.required' => 'Frequency is required.',
            'duration.required' => 'Duration is required.',
            'route.required' => 'Route of administration is required.',
            'route.in' => 'Invalid route selected.',
            'start_date.required' => 'Start date is required.',
            'end_date.after' => 'End date must be after start date.',
        ];
    }
}
