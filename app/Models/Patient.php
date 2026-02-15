<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'patient_id_number',
        'emergency_contact',
        'emergency_phone',
        'allergies',
        'chronic_conditions',
        'insurance_number',
        'status',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            if (!$patient->patient_id_number) {
                $patient->patient_id_number = self::generatePatientIdNumber();
            }
        });
    }

    /**
     * Generate unique patient ID number.
     */
    private static function generatePatientIdNumber(): string
    {
        $year = date('Y');
        $lastPatient = self::where('patient_id_number', 'like', "PAT-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPatient) {
            $lastNumber = (int) substr($lastPatient->patient_id_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "PAT-{$year}-{$newNumber}";
    }

    /**
     * Get the user that owns the patient record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the medical records for the patient.
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    /**
     * Get the prescriptions for the patient.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Get the lab reports for the patient.
     */
    public function labReports()
    {
        return $this->hasMany(LabReport::class);
    }
}
