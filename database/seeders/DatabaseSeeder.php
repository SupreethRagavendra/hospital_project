<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\LabReport;
use App\Models\AuditLog;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ═══════════════════════════════
        // 1. CREATE ADMIN
        // ═══════════════════════════════

        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'gender' => 'male',
            'phone' => '+91-9876543210',
            'address' => 'Admin Office, EMR Building',
            'is_active' => true,
            'date_of_birth' => '1985-05-15',
        ]);

        // ═══════════════════════════════
        // 2. CREATE DOCTORS
        // ═══════════════════════════════

        $doctor1 = User::create([
            'name' => 'Dr. Rajesh Kumar',
            'email' => 'rajesh@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'phone' => '+91-9876543211',
            'address' => 'Block A, Medical Complex',
            'specialization' => 'General Medicine',
            'license_number' => 'MED-2024-001',
            'gender' => 'male',
            'date_of_birth' => '1980-03-20',
            'is_active' => true,
        ]);

        $doctor2 = User::create([
            'name' => 'Dr. Priya Sharma',
            'email' => 'priya@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'phone' => '+91-9876543212',
            'address' => 'Block B, Medical Complex',
            'specialization' => 'Cardiology',
            'license_number' => 'MED-2024-002',
            'gender' => 'female',
            'date_of_birth' => '1982-07-14',
            'is_active' => true,
        ]);

        $doctor3 = User::create([
            'name' => 'Dr. Anil Verma',
            'email' => 'anil@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'phone' => '+91-9876543213',
            'address' => 'Block C, Medical Complex',
            'specialization' => 'Orthopedics',
            'license_number' => 'MED-2024-003',
            'gender' => 'male',
            'date_of_birth' => '1978-11-05',
            'is_active' => true,
        ]);

        // ═══════════════════════════════
        // 3. CREATE PATIENTS
        // ═══════════════════════════════

        $patientUser1 = User::create([
            'name' => 'Amit Singh',
            'email' => 'amit@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
            'phone' => '+91-9876543221',
            'address' => '123 Park Street, Delhi',
            'gender' => 'male',
            'date_of_birth' => '1990-06-10',
            'blood_group' => 'B+',
            'is_active' => true,
        ]);

        $patient1 = Patient::create([
            'user_id' => $patientUser1->id,
            'emergency_contact' => 'Mrs. Singh',
            'emergency_phone' => '+91-9876543231',
            'allergies' => 'Penicillin',
            'chronic_conditions' => null,
            'insurance_number' => 'INS-2024-0001',
            'status' => 'active',
        ]);

        $patientUser2 = User::create([
            'name' => 'Sneha Patel',
            'email' => 'sneha@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
            'phone' => '+91-9876543222',
            'address' => '456 Lake Road, Mumbai',
            'gender' => 'female',
            'date_of_birth' => '1985-09-22',
            'blood_group' => 'A+',
            'is_active' => true,
        ]);

        $patient2 = Patient::create([
            'user_id' => $patientUser2->id,
            'emergency_contact' => 'Mr. Patel',
            'emergency_phone' => '+91-9876543232',
            'allergies' => null,
            'chronic_conditions' => 'Diabetes Type 2',
            'insurance_number' => 'INS-2024-0002',
            'status' => 'active',
        ]);

        $patientUser3 = User::create([
            'name' => 'Vikram Rajan',
            'email' => 'vikram@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
            'phone' => '+91-9876543223',
            'address' => '789 Hill View, Bangalore',
            'gender' => 'male',
            'date_of_birth' => '1992-12-08',
            'blood_group' => 'O+',
            'is_active' => true,
        ]);

        $patient3 = Patient::create([
            'user_id' => $patientUser3->id,
            'emergency_contact' => 'Mrs. Rajan',
            'emergency_phone' => '+91-9876543233',
            'allergies' => 'Aspirin',
            'chronic_conditions' => null,
            'insurance_number' => 'INS-2024-0003',
            'status' => 'active',
        ]);

        $patientUser4 = User::create([
            'name' => 'Meera Nair',
            'email' => 'meera@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
            'phone' => '+91-9876543224',
            'address' => '321 Beach Road, Chennai',
            'gender' => 'female',
            'date_of_birth' => '1988-04-18',
            'blood_group' => 'AB+',
            'is_active' => true,
        ]);

        $patient4 = Patient::create([
            'user_id' => $patientUser4->id,
            'emergency_contact' => 'Mr. Nair',
            'emergency_phone' => '+91-9876543234',
            'allergies' => null,
            'chronic_conditions' => null,
            'insurance_number' => 'INS-2024-0004',
            'status' => 'active',
        ]);

        $patientUser5 = User::create([
            'name' => 'Karthik Kumar',
            'email' => 'karthik@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
            'phone' => '+91-9876543225',
            'address' => '654 Market Street, Hyderabad',
            'gender' => 'male',
            'date_of_birth' => '1975-01-30',
            'blood_group' => 'B-',
            'is_active' => true,
        ]);

        $patient5 = Patient::create([
            'user_id' => $patientUser5->id,
            'emergency_contact' => 'Mrs. Kumar',
            'emergency_phone' => '+91-9876543235',
            'allergies' => null,
            'chronic_conditions' => 'Hypertension',
            'insurance_number' => 'INS-2024-0005',
            'status' => 'active',
        ]);

        // ═══════════════════════════════
        // 4. CREATE MEDICAL RECORDS
        // ═══════════════════════════════

        $record1 = MedicalRecord::create([
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor1->id,
            'record_date' => Carbon::now()->subDays(10),
            'chief_complaint' => 'High fever and body ache for 3 days',
            'diagnosis' => 'Viral Fever with Upper Respiratory Tract Infection',
            'treatment_plan' => 'Rest, hydration, and antipyretics. Follow up if symptoms persist beyond 5 days.',
            'symptoms' => 'Fever (102°F), headache, body ache, mild cough',
            'vital_signs' => json_encode([
                'blood_pressure' => '120/80',
                'heart_rate' => 82,
                'temperature' => 102.0,
                'weight' => 75,
                'height' => 175,
                'oxygen_level' => 98,
            ]),
            'follow_up_date' => Carbon::now()->addDays(7),
            'follow_up_notes' => 'Check if fever has subsided',
            'status' => 'active',
            'confidential' => false,
        ]);

        $record2 = MedicalRecord::create([
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor1->id,
            'record_date' => Carbon::now()->subDays(15),
            'chief_complaint' => 'Routine diabetes checkup',
            'diagnosis' => 'Diabetes Type 2 - Well Controlled',
            'treatment_plan' => 'Continue current medication. Monitor blood sugar levels daily. Diet and exercise regimen.',
            'symptoms' => 'No acute symptoms. Regular follow-up.',
            'vital_signs' => json_encode([
                'blood_pressure' => '130/85',
                'heart_rate' => 78,
                'temperature' => 98.6,
                'weight' => 68,
                'height' => 162,
                'oxygen_level' => 99,
            ]),
            'follow_up_date' => Carbon::now()->addDays(30),
            'follow_up_notes' => 'Monthly diabetes monitoring',
            'status' => 'completed',
            'confidential' => false,
        ]);

        $record3 = MedicalRecord::create([
            'patient_id' => $patient3->id,
            'doctor_id' => $doctor3->id,
            'record_date' => Carbon::now()->subDays(5),
            'chief_complaint' => 'Pain in right knee after fall',
            'diagnosis' => 'Mild knee sprain. No fracture detected.',
            'treatment_plan' => 'Rest, ice, compression, elevation (RICE). Pain management with NSAIDs.',
            'symptoms' => 'Knee pain, mild swelling, limited mobility',
            'vital_signs' => json_encode([
                'blood_pressure' => '118/75',
                'heart_rate' => 70,
                'temperature' => 98.4,
                'weight' => 80,
                'height' => 178,
                'oxygen_level' => 99,
            ]),
            'follow_up_date' => Carbon::now()->addDays(14),
            'follow_up_notes' => 'Re-evaluate knee mobility',
            'status' => 'follow_up',
            'confidential' => false,
        ]);

        $record4 = MedicalRecord::create([
            'patient_id' => $patient5->id,
            'doctor_id' => $doctor2->id,
            'record_date' => Carbon::now()->subDays(20),
            'chief_complaint' => 'Chest discomfort and shortness of breath',
            'diagnosis' => 'Hypertension with mild angina symptoms',
            'treatment_plan' => 'Medication adjustment. Stress management. Lifestyle modifications.',
            'symptoms' => 'Occasional chest tightness, breathlessness on exertion',
            'vital_signs' => json_encode([
                'blood_pressure' => '145/95',
                'heart_rate' => 88,
                'temperature' => 98.6,
                'weight' => 85,
                'height' => 170,
                'oxygen_level' => 97,
            ]),
            'follow_up_date' => Carbon::now()->addDays(21),
            'follow_up_notes' => 'Monitor BP and cardiac symptoms',
            'status' => 'active',
            'confidential' => false,
        ]);

        $record5 = MedicalRecord::create([
            'patient_id' => $patient4->id,
            'doctor_id' => $doctor1->id,
            'record_date' => Carbon::now()->subDays(3),
            'chief_complaint' => 'Annual health checkup',
            'diagnosis' => 'Overall healthy. No significant findings.',
            'treatment_plan' => 'Maintain healthy lifestyle. Continue routine exercise and balanced diet.',
            'symptoms' => 'No complaints',
            'vital_signs' => json_encode([
                'blood_pressure' => '115/75',
                'heart_rate' => 72,
                'temperature' => 98.6,
                'weight' => 60,
                'height' => 165,
                'oxygen_level' => 99,
            ]),
            'follow_up_date' => Carbon::now()->addYear(),
            'follow_up_notes' => 'Annual checkup next year',
            'status' => 'completed',
            'confidential' => false,
        ]);

        $record6 = MedicalRecord::create([
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor1->id,
            'record_date' => Carbon::now()->subDays(2),
            'chief_complaint' => 'Skin rash on arms',
            'diagnosis' => 'Allergic dermatitis',
            'treatment_plan' => 'Topical corticosteroid cream. Avoid allergens. Antihistamine if itching persists.',
            'symptoms' => 'Red, itchy rash on both arms',
            'vital_signs' => json_encode([
                'blood_pressure' => '118/78',
                'heart_rate' => 76,
                'temperature' => 98.6,
                'weight' => 75,
                'height' => 175,
                'oxygen_level' => 99,
            ]),
            'follow_up_date' => Carbon::now()->addDays(10),
            'follow_up_notes' => 'Check if rash has cleared',
            'status' => 'active',
            'confidential' => false,
        ]);

        $record7 = MedicalRecord::create([
            'patient_id' => $patient3->id,
            'doctor_id' => $doctor1->id,
            'record_date' => Carbon::now()->subDays(25),
            'chief_complaint' => 'Persistent cough for 2 weeks',
            'diagnosis' => 'Bronchitis',
            'treatment_plan' => 'Antibiotics, cough syrup, rest, plenty of fluids',
            'symptoms' => 'Dry cough, mild throat irritation',
            'vital_signs' => json_encode([
                'blood_pressure' => '120/80',
                'heart_rate' => 80,
                'temperature' => 99.2,
                'weight' => 80,
                'height' => 178,
                'oxygen_level' => 98,
            ]),
            'follow_up_date' => null,
            'follow_up_notes' => null,
            'status' => 'completed',
            'confidential' => false,
        ]);

        $record8 = MedicalRecord::create([
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor2->id,
            'record_date' => Carbon::now()->subDays(8),
            'chief_complaint' => 'Follow-up for diabetes and cardiac health',
            'diagnosis' => 'Diabetes Type 2 with controlled cardiovascular risk',
            'treatment_plan' => 'Continue medications. Regular monitoring. Dietary counseling.',
            'symptoms' => 'No acute symptoms',
            'vital_signs' => json_encode([
                'blood_pressure' => '128/84',
                'heart_rate' => 76,
                'temperature' => 98.6,
                'weight' => 68,
                'height' => 162,
                'oxygen_level' => 99,
            ]),
            'follow_up_date' => Carbon::now()->addDays(45),
            'follow_up_notes' => 'Comprehensive health review',
            'status' => 'active',
            'confidential' => false,
        ]);

        // ═══════════════════════════════
        // 5. CREATE PRESCRIPTIONS
        // ═══════════════════════════════

        Prescription::create([
            'medical_record_id' => $record1->id,
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor1->id,
            'medication_name' => 'Paracetamol 650mg',
            'dosage' => '650mg',
            'frequency' => 'Twice daily',
            'duration' => '5 days',
            'route' => 'oral',
            'instructions' => 'Take after meals. Drink plenty of water.',
            'start_date' => Carbon::now()->subDays(10),
            'end_date' => Carbon::now()->subDays(5),
            'is_active' => false,
        ]);

        Prescription::create([
            'medical_record_id' => $record2->id,
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor1->id,
            'medication_name' => 'Metformin 500mg',
            'dosage' => '500mg',
            'frequency' => 'Twice daily',
            'duration' => 'Ongoing',
            'route' => 'oral',
            'instructions' => 'Take with breakfast and dinner.',
            'start_date' => Carbon::now()->subMonths(6),
            'end_date' => null,
            'is_active' => true,
        ]);

        Prescription::create([
            'medical_record_id' => $record4->id,
            'patient_id' => $patient5->id,
            'doctor_id' => $doctor2->id,
            'medication_name' => 'Amlodipine 5mg',
            'dosage' => '5mg',
            'frequency' => 'Once daily',
            'duration' => 'Ongoing',
            'route' => 'oral',
            'instructions' => 'Take in the morning.',
            'start_date' => Carbon::now()->subMonths(3),
            'end_date' => null,
            'is_active' => true,
        ]);

        Prescription::create([
            'medical_record_id' => $record6->id,
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor1->id,
            'medication_name' => 'Cetirizine 10mg',
            'dosage' => '10mg',
            'frequency' => 'Once daily at night',
            'duration' => '7 days',
            'route' => 'oral',
            'instructions' => 'Take at bedtime to avoid drowsiness.',
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(5),
            'is_active' => true,
        ]);

        Prescription::create([
            'medical_record_id' => $record6->id,
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor1->id,
            'medication_name' => 'Hydrocortisone Cream 1%',
            'dosage' => 'Apply thin layer',
            'frequency' => 'Twice daily',
            'duration' => '10 days',
            'route' => 'topical',
            'instructions' => 'Apply to affected area only. Avoid eyes.',
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(8),
            'is_active' => true,
        ]);

        Prescription::create([
            'medical_record_id' => $record7->id,
            'patient_id' => $patient3->id,
            'doctor_id' => $doctor1->id,
            'medication_name' => 'Amoxicillin 500mg',
            'dosage' => '500mg',
            'frequency' => 'Three times daily',
            'duration' => '7 days',
            'route' => 'oral',
            'instructions' => 'Complete the full course even if symptoms improve.',
            'start_date' => Carbon::now()->subDays(25),
            'end_date' => Carbon::now()->subDays(18),
            'is_active' => false,
        ]);

        Prescription::create([
            'medical_record_id' => $record3->id,
            'patient_id' => $patient3->id,
            'doctor_id' => $doctor3->id,
            'medication_name' => 'Ibuprofen 400mg',
            'dosage' => '400mg',
            'frequency' => 'Three times daily',
            'duration' => '10 days',
            'route' => 'oral',
            'instructions' => 'Take with food to avoid stomach upset.',
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->addDays(5),
            'is_active' => true,
        ]);

        Prescription::create([
            'medical_record_id' => $record4->id,
            'patient_id' => $patient5->id,
            'doctor_id' => $doctor2->id,
            'medication_name' => 'Aspirin 75mg',
            'dosage' => '75mg',
            'frequency' => 'Once daily',
            'duration' => 'Ongoing',
            'route' => 'oral',
            'instructions' => 'Take after breakfast.',
            'start_date' => Carbon::now()->subMonths(2),
            'end_date' => null,
            'is_active' => true,
        ]);

        Prescription::create([
            'medical_record_id' => $record8->id,
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor2->id,
            'medication_name' => 'Atorvastatin 10mg',
            'dosage' => '10mg',
            'frequency' => 'Once daily at night',
            'duration' => 'Ongoing',
            'route' => 'oral',
            'instructions' => 'Take at bedtime for better cholesterol control.',
            'start_date' => Carbon::now()->subDays(8),
            'end_date' => null,
            'is_active' => true,
        ]);

        Prescription::create([
            'medical_record_id' => $record2->id,
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor1->id,
            'medication_name' => 'Glimepiride 2mg',
            'dosage' => '2mg',
            'frequency' => 'Once daily before breakfast',
            'duration' => 'Ongoing',
            'route' => 'oral',
            'instructions' => 'Monitor blood sugar levels regularly.',
            'start_date' => Carbon::now()->subMonths(4),
            'end_date' => null,
            'is_active' => true,
        ]);

        Prescription::create([
            'medical_record_id' => $record7->id,
            'patient_id' => $patient3->id,
            'doctor_id' => $doctor1->id,
            'medication_name' => 'Dextromethorphan Cough Syrup',
            'dosage' => '10ml',
            'frequency' => 'Three times daily',
            'duration' => '7 days',
            'route' => 'oral',
            'instructions' => 'Shake well before use. Do not drive after taking.',
            'start_date' => Carbon::now()->subDays(25),
            'end_date' => Carbon::now()->subDays(18),
            'is_active' => false,
        ]);

        Prescription::create([
            'medical_record_id' => $record4->id,
            'patient_id' => $patient5->id,
            'doctor_id' => $doctor2->id,
            'medication_name' => 'Nitroglycerin Sublingual',
            'dosage' => '0.5mg',
            'frequency' => 'As needed for chest pain',
            'duration' => 'Ongoing',
            'route' => 'other',
            'instructions' => 'Place under tongue when chest pain occurs. Seek emergency help if pain persists.',
            'start_date' => Carbon::now()->subDays(20),
            'end_date' => null,
            'is_active' => true,
        ]);

        // ═══════════════════════════════
        // 6. CREATE LAB REPORTS
        // ═══════════════════════════════

        LabReport::create([
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor1->id,
            'medical_record_id' => $record2->id,
            'report_title' => 'Fasting Blood Sugar Test',
            'report_type' => 'blood_test',
            'report_date' => Carbon::now()->subDays(16),
            'findings' => 'Fasting glucose: 128 mg/dL (slightly elevated)',
            'conclusion' => 'Blood sugar levels are well-controlled with current medication.',
            'file_path' => null,
            'file_name' => null,
            'status' => 'reviewed',
            'reviewed_by' => $doctor1->id,
            'reviewed_at' => Carbon::now()->subDays(15),
        ]);

        LabReport::create([
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor1->id,
            'medical_record_id' => $record1->id,
            'report_title' => 'Complete Blood Count (CBC)',
            'report_type' => 'blood_test',
            'report_date' => Carbon::now()->subDays(9),
            'findings' => 'WBC count slightly elevated. All other parameters normal.',
            'conclusion' => 'Indicates ongoing viral infection. Monitor recovery.',
            'file_path' => null,
            'file_name' => null,
            'status' => 'reviewed',
            'reviewed_by' => $doctor1->id,
            'reviewed_at' => Carbon::now()->subDays(8),
        ]);

        LabReport::create([
            'patient_id' => $patient3->id,
            'doctor_id' => $doctor3->id,
            'medical_record_id' => $record3->id,
            'report_title' => 'Right Knee X-Ray',
            'report_type' => 'xray',
            'report_date' => Carbon::now()->subDays(5),
            'findings' => 'No fracture or bone abnormality detected. Soft tissue swelling visible.',
            'conclusion' => 'Consistent with mild sprain. No fracture.',
            'file_path' => null,
            'file_name' => null,
            'status' => 'reviewed',
            'reviewed_by' => $doctor3->id,
            'reviewed_at' => Carbon::now()->subDays(4),
        ]);

        LabReport::create([
            'patient_id' => $patient5->id,
            'doctor_id' => $doctor2->id,
            'medical_record_id' => $record4->id,
            'report_title' => 'Electrocardiogram (ECG)',
            'report_type' => 'ecg',
            'report_date' => Carbon::now()->subDays(19),
            'findings' => 'Sinus rhythm with occasional premature beats. No ST-segment changes.',
            'conclusion' => 'Mild cardiac irregularities. Continue monitoring and medication.',
            'file_path' => null,
            'file_name' => null,
            'status' => 'reviewed',
            'reviewed_by' => $doctor2->id,
            'reviewed_at' => Carbon::now()->subDays(18),
        ]);

        LabReport::create([
            'patient_id' => $patient4->id,
            'doctor_id' => $doctor1->id,
            'medical_record_id' => $record5->id,
            'report_title' => 'Annual Health Panel - Blood Work',
            'report_type' => 'blood_test',
            'report_date' => Carbon::now()->subDays(3),
            'findings' => 'All parameters within normal range. Cholesterol: 180 mg/dL. Hemoglobin: 13.5 g/dL.',
            'conclusion' => 'Excellent health status. No concerns.',
            'file_path' => null,
            'file_name' => null,
            'status' => 'completed',
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        LabReport::create([
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor2->id,
            'medical_record_id' => $record8->id,
            'report_title' => 'Lipid Profile Test',
            'report_type' => 'blood_test',
            'report_date' => Carbon::now()->subDays(7),
            'findings' => 'Total cholesterol: 215 mg/dL (borderline high). LDL: 145 mg/dL. HDL: 45 mg/dL.',
            'conclusion' => 'Lipid levels need management. Medication and lifestyle changes recommended.',
            'file_path' => null,
            'file_name' => null,
            'status' => 'pending',
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        // ═══════════════════════════════
        // 7. CREATE AUDIT LOGS
        // ═══════════════════════════════

        AuditLog::create([
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_role' => 'admin',
            'action' => 'login',
            'description' => 'User logged in successfully',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(30),
        ]);

        AuditLog::create([
            'user_id' => $doctor1->id,
            'user_name' => $doctor1->name,
            'user_role' => 'doctor',
            'action' => 'login',
            'description' => 'User logged in successfully',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(25),
        ]);

        AuditLog::create([
            'user_id' => $doctor1->id,
            'user_name' => $doctor1->name,
            'user_role' => 'doctor',
            'action' => 'create_record',
            'description' => 'Created medical record for patient: Vikram Rajan',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(25),
        ]);

        AuditLog::create([
            'user_id' => $doctor1->id,
            'user_name' => $doctor1->name,
            'user_role' => 'doctor',
            'action' => 'create_prescription',
            'description' => 'Created prescription: Amoxicillin 500mg',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(25),
        ]);

        AuditLog::create([
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_role' => 'admin',
            'action' => 'create_user',
            'description' => 'Created patient: Karthik Kumar',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(20),
        ]);

        AuditLog::create([
            'user_id' => $doctor2->id,
            'user_name' => $doctor2->name,
            'user_role' => 'doctor',
            'action' => 'view_patient',
            'description' => 'Viewed patient: Karthik Kumar',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(20),
        ]);

        AuditLog::create([
            'user_id' => $doctor2->id,
            'user_name' => $doctor2->name,
            'user_role' => 'doctor',
            'action' => 'upload_lab_report',
            'description' => 'Uploaded lab report: Electrocardiogram (ECG)',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(19),
        ]);

        AuditLog::create([
            'user_id' => $patientUser1->id,
            'user_name' => $patientUser1->name,
            'user_role' => 'patient',
            'action' => 'login',
            'description' => 'User logged in successfully',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(11),
        ]);

        AuditLog::create([
            'user_id' => $patientUser1->id,
            'user_name' => $patientUser1->name,
            'user_role' => 'patient',
            'action' => 'view_own_record',
            'description' => 'Patient viewed medical record #1',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(11),
        ]);

        AuditLog::create([
            'user_id' => $doctor1->id,
            'user_name' => $doctor1->name,
            'user_role' => 'doctor',
            'action' => 'update_record',
            'description' => 'Updated medical record #1',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(9),
        ]);

        AuditLog::create([
            'user_id' => $doctor3->id,
            'user_name' => $doctor3->name,
            'user_role' => 'doctor',
            'action' => 'review_lab_report',
            'description' => 'Reviewed lab report #3',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(4),
        ]);

        AuditLog::create([
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_role' => 'admin',
            'action' => 'view_user',
            'description' => 'Viewed user details',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(3),
        ]);

        AuditLog::create([
            'user_id' => $doctor1->id,
            'user_name' => $doctor1->name,
            'user_role' => 'doctor',
            'action' => 'create_record',
            'description' => 'Created medical record for patient: Amit Singh',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        AuditLog::create([
            'user_id' => $patientUser2->id,
            'user_name' => $patientUser2->name,
            'user_role' => 'patient',
            'action' => 'view_own_report',
            'description' => 'Patient viewed lab report #6',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(1),
        ]);

        AuditLog::create([
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_role' => 'admin',
            'action' => 'export_audit_logs',
            'description' => 'Exported 100 audit log entries',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subHours(12),
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('LOGIN CREDENTIALS:');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('ADMIN:');
        $this->command->info('  Email: admin@emr.com');
        $this->command->info('  Password: admin123');
        $this->command->info('');
        $this->command->info('DOCTORS:');
        $this->command->info('  Email: rajesh@emr.com | Password: doctor123');
        $this->command->info('  Email: priya@emr.com  | Password: doctor123');
        $this->command->info('  Email: anil@emr.com   | Password: doctor123');
        $this->command->info('');
        $this->command->info('PATIENTS:');
        $this->command->info('  Email: amit@emr.com    | Password: patient123');
        $this->command->info('  Email: sneha@emr.com   | Password: patient123');
        $this->command->info('  Email: vikram@emr.com  | Password: patient123');
        $this->command->info('  Email: meera@emr.com   | Password: patient123');
        $this->command->info('  Email: karthik@emr.com | Password: patient123');
        $this->command->info('═══════════════════════════════════════════');
    }
}
