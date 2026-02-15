<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'record_date',
        'chief_complaint',
        'diagnosis',
        'treatment_plan',
        'symptoms',
        'vital_signs',
        'follow_up_date',
        'follow_up_notes',
        'status',
        'confidential',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'record_date' => 'date',
            'follow_up_date' => 'date',
            'vital_signs' => 'array',
            'confidential' => 'boolean',
        ];
    }

    /**
     * Get the patient that owns the medical record.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor that created the medical record.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the prescriptions for the medical record.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Get the lab reports for the medical record.
     */
    public function labReports()
    {
        return $this->hasMany(LabReport::class);
    }
}
