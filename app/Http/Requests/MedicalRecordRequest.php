<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicalRecordRequest extends FormRequest
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
            'patient_id' => 'required|exists:patients,id',
            'record_date' => 'required|date',
            'chief_complaint' => 'required|string|min:10',
            'diagnosis' => 'required|string|min:10',
            'treatment_plan' => 'nullable|string',
            'symptoms' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'vital_signs.blood_pressure' => 'nullable|string',
            'vital_signs.heart_rate' => 'nullable|numeric',
            'vital_signs.temperature' => 'nullable|numeric',
            'vital_signs.weight' => 'nullable|numeric',
            'vital_signs.height' => 'nullable|numeric',
            'vital_signs.oxygen_level' => 'nullable|numeric',
            'follow_up_date' => 'nullable|date|after:today',
            'follow_up_notes' => 'nullable|string',
            'status' => 'required|in:active,completed,follow_up',
            'confidential' => 'nullable|boolean',
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
            'patient_id.required' => 'Please select a patient.',
            'patient_id.exists' => 'The selected patient does not exist.',
            'record_date.required' => 'Record date is required.',
            'chief_complaint.required' => 'Chief complaint is required.',
            'chief_complaint.min' => 'Chief complaint must be at least 10 characters.',
            'diagnosis.required' => 'Diagnosis is required.',
            'diagnosis.min' => 'Diagnosis must be at least 10 characters.',
            'follow_up_date.after' => 'Follow-up date must be in the future.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
        ];
    }
}
