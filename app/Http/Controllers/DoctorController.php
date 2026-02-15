<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\LabReport;
use App\Models\AuditLog;
use App\Http\Requests\MedicalRecordRequest;
use App\Http\Requests\PrescriptionRequest;

class DoctorController extends Controller
{
    /**
     * Show doctor dashboard.
     */
    public function dashboard()
    {
        $doctorId = auth()->id();

        $myPatientsCount = MedicalRecord::where('doctor_id', $doctorId)
            ->distinct('patient_id')
            ->count('patient_id');

        $todaysRecords = MedicalRecord::where('doctor_id', $doctorId)
            ->whereDate('record_date', today())
            ->with('patient.user')
            ->get();

        $totalRecords = MedicalRecord::where('doctor_id', $doctorId)->count();

        $activePrescriptions = Prescription::where('doctor_id', $doctorId)
            ->where('is_active', true)
            ->count();

        $pendingReports = LabReport::where('doctor_id', $doctorId)
            ->where('status', 'pending')
            ->count();

        $upcomingFollowUps = MedicalRecord::where('doctor_id', $doctorId)
            ->where('follow_up_date', '>=', today())
            ->orderBy('follow_up_date')
            ->take(5)
            ->with('patient.user')
            ->get();

        $recentRecords = MedicalRecord::where('doctor_id', $doctorId)
            ->latest()
            ->take(5)
            ->with('patient.user')
            ->get();

        return view('doctor.dashboard', [
            'myPatientsCount' => $myPatientsCount,
            'todayAppointments' => $todaysRecords,
            'myRecordsCount' => $totalRecords,
            'myPrescriptionsCount' => $activePrescriptions,
            'pendingLabsCount' => $pendingReports,
            'upcomingFollowUps' => $upcomingFollowUps,
            'recentPatients' => $recentRecords
        ]);
    }

    /**
     * Show patients list.
     */
    public function patients(Request $request)
    {
        $search = $request->search;

        $patients = Patient::with('user')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })->orWhere('patient_id_number', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('doctor.patients', compact('patients', 'search'));
    }

    /**
     * Show patient details with tabs.
     */
    public function showPatient($id)
    {
        $patient = Patient::with('user')->findOrFail($id);

        $tab = request('tab', 'records');

        // Fetch paginated relations
        $records = $patient->medicalRecords()->with('doctor')->latest()->paginate(5, ['*'], 'records_page');
        $prescriptions = $patient->prescriptions()->with('doctor')->latest()->paginate(5, ['*'], 'prescriptions_page');
        $labReports = $patient->labReports()->with('doctor')->latest()->paginate(5, ['*'], 'labs_page');

        AuditLog::log('view_patient', "Viewed patient: {$patient->user->name}");

        return view('doctor.patient-detail', compact('patient', 'tab', 'records', 'prescriptions', 'labReports'));
    }

    /**
     * Search patients (AJAX).
     */
    public function searchPatients(Request $request)
    {
        $search = $request->search;

        $patients = Patient::with('user')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })->orWhere('patient_id_number', 'like', "%{$search}%");
            })
            ->take(10)
            ->get();

        return response()->json($patients->map(function ($patient) {
            return [
                'id' => $patient->id,
                'name' => $patient->user->name,
                'patient_id_number' => $patient->patient_id_number,
                'age' => $patient->user->age,
                'gender' => $patient->user->gender,
            ];
        }));
    }

    /**
     * Show medical records list.
     */
    public function records(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $records = MedicalRecord::where('doctor_id', auth()->id())
            ->with('patient.user')
            ->when($search, function ($q) use ($search) {
                $q->where('diagnosis', 'like', "%{$search}%")
                    ->orWhere('chief_complaint', 'like', "%{$search}%")
                    ->orWhereHas('patient.user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('doctor.records', compact('records', 'search', 'status'));
    }

    /**
     * Show create record form.
     */
    public function createRecord($patient_id)
    {
        $patient = Patient::with('user')->findOrFail($patient_id);
        $mode = 'create';

        return view('doctor.record-form', compact('patient', 'mode'));
    }

    /**
     * Store new medical record.
     */
    public function storeRecord(MedicalRecordRequest $request)
    {
        DB::beginTransaction();
        try {
            $record = MedicalRecord::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => auth()->id(),
                'record_date' => $request->record_date,
                'chief_complaint' => $request->chief_complaint,
                'diagnosis' => $request->diagnosis,
                'treatment_plan' => $request->treatment_plan,
                'symptoms' => $request->symptoms,
                'vital_signs' => $request->vital_signs,
                'follow_up_date' => $request->follow_up_date,
                'follow_up_notes' => $request->follow_up_notes,
                'status' => $request->status,
                'confidential' => $request->confidential ?? false,
            ]);

            $patient = Patient::with('user')->find($request->patient_id);
            AuditLog::log('create_record', "Created medical record for patient: {$patient->user->name}");

            DB::commit();

            return redirect()->route('doctor.records.show', $record->id)
                ->with('success', 'Medical record created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create record: ' . $e->getMessage());
        }
    }

    /**
     * Show medical record details.
     */
    public function showRecord($id)
    {
        $record = MedicalRecord::with([
            'patient.user',
            'doctor',
            'prescriptions',
            'labReports'
        ])->findOrFail($id);

        // Security check: verify ownership
        if ($record->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this medical record.');
        }

        AuditLog::log('view_record', "Viewed medical record #{$id}");

        return view('doctor.record-detail', compact('record'));
    }

    /**
     * Show edit record form.
     */
    public function editRecord($id)
    {
        $record = MedicalRecord::with('patient.user')->findOrFail($id);

        // Security check: verify ownership
        if ($record->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this medical record.');
        }

        $patient = $record->patient;
        $mode = 'edit';

        return view('doctor.record-form', compact('record', 'patient', 'mode'));
    }

    /**
     * Update medical record.
     */
    public function updateRecord($id, MedicalRecordRequest $request)
    {
        $record = MedicalRecord::findOrFail($id);

        // Security check: verify ownership
        if ($record->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this medical record.');
        }

        DB::beginTransaction();
        try {
            $record->update([
                'record_date' => $request->record_date,
                'chief_complaint' => $request->chief_complaint,
                'diagnosis' => $request->diagnosis,
                'treatment_plan' => $request->treatment_plan,
                'symptoms' => $request->symptoms,
                'vital_signs' => $request->vital_signs,
                'follow_up_date' => $request->follow_up_date,
                'follow_up_notes' => $request->follow_up_notes,
                'status' => $request->status,
                'confidential' => $request->confidential ?? false,
            ]);

            AuditLog::log('update_record', "Updated medical record #{$id}");

            DB::commit();

            return redirect()->route('doctor.records.show', $record->id)
                ->with('success', 'Medical record updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update record: ' . $e->getMessage());
        }
    }

    /**
     * Delete medical record.
     */
    public function deleteRecord($id)
    {
        $record = MedicalRecord::findOrFail($id);

        // Security check: verify ownership
        if ($record->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this medical record.');
        }

        AuditLog::log('delete_record', "Deleted medical record #{$id}");

        $record->delete();

        return redirect()->route('doctor.records')
            ->with('success', 'Medical record deleted successfully.');
    }

    /**
     * Show prescriptions list.
     */
    public function prescriptions(Request $request)
    {
        $status = $request->status;

        $prescriptions = Prescription::where('doctor_id', auth()->id())
            ->with(['patient.user', 'medicalRecord'])
            ->when($status === 'active', function ($q) {
                $q->where('is_active', true);
            })
            ->when($status === 'inactive', function ($q) {
                $q->where('is_active', false);
            })
            ->latest()
            ->paginate(10);

        $patients = Patient::with('user')->get();
        $records = MedicalRecord::where('doctor_id', auth()->id())->get();

        return view('doctor.prescriptions', compact('prescriptions', 'patients', 'records', 'status'));
    }

    /**
     * Store new prescription.
     */
    public function storePrescription(PrescriptionRequest $request)
    {
        DB::beginTransaction();
        try {
            $prescription = Prescription::create([
                'medical_record_id' => $request->medical_record_id,
                'patient_id' => $request->patient_id,
                'doctor_id' => auth()->id(),
                'medication_name' => $request->medication_name,
                'dosage' => $request->dosage,
                'frequency' => $request->frequency,
                'duration' => $request->duration,
                'route' => $request->route,
                'instructions' => $request->instructions,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active ?? true,
            ]);

            AuditLog::log('create_prescription', "Created prescription: {$prescription->medication_name}");

            DB::commit();

            return redirect()->back()->with('success', 'Prescription created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create prescription: ' . $e->getMessage());
        }
    }

    /**
     * Update prescription.
     */
    public function updatePrescription($id, PrescriptionRequest $request)
    {
        $prescription = Prescription::findOrFail($id);

        // Security check: verify ownership
        if ($prescription->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this prescription.');
        }

        DB::beginTransaction();
        try {
            $prescription->update([
                'medication_name' => $request->medication_name,
                'dosage' => $request->dosage,
                'frequency' => $request->frequency,
                'duration' => $request->duration,
                'route' => $request->route,
                'instructions' => $request->instructions,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active ?? true,
            ]);

            AuditLog::log('update_prescription', "Updated prescription #{$id}");

            DB::commit();

            return redirect()->back()->with('success', 'Prescription updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update prescription: ' . $e->getMessage());
        }
    }

    /**
     * Delete prescription.
     */
    public function deletePrescription($id)
    {
        $prescription = Prescription::findOrFail($id);

        // Security check: verify ownership
        if ($prescription->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this prescription.');
        }

        AuditLog::log('delete_prescription', "Deleted prescription #{$id}");

        $prescription->delete();

        return redirect()->back()->with('success', 'Prescription deleted successfully.');
    }

    /**
     * Show lab reports list.
     */
    public function labReports(Request $request)
    {
        $type = $request->type;
        $status = $request->status;

        $labReports = LabReport::where('doctor_id', auth()->id())
            ->with(['patient.user', 'medicalRecord'])
            ->when($type, function ($q) use ($type) {
                $q->where('report_type', $type);
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        $patients = Patient::with('user')->get();

        return view('doctor.lab-reports', compact('labReports', 'patients', 'type', 'status'));
    }

    /**
     * Store new lab report with file upload.
     */
    public function storeLabReport(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'report_title' => 'required|string|max:255',
            'report_type' => 'required|in:blood_test,urine_test,xray,mri,ct_scan,ultrasound,ecg,other',
            'report_date' => 'required|date',
            'findings' => 'nullable|string',
            'conclusion' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,jpg,png,jpeg|max:10240', // Increase to 10MB
        ], [
            'file.required' => 'Please select a file to upload.',
            'file.mimes' => 'The file must be a PDF, JPG, or PNG.',
            'file.max' => 'The file size must not exceed 10MB.',
            'file.uploaded' => 'The file upload failed. Your server (PHP) currently limits uploads to 2MB. Please try a smaller file or update your php.ini settings.',
        ]);

        DB::beginTransaction();
        try {
            $filePath = null;
            $fileName = null;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('lab_reports', $fileName, 'public');
            }

            $report = LabReport::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => auth()->id(),
                'medical_record_id' => $request->medical_record_id,
                'report_title' => $request->report_title,
                'report_type' => $request->report_type,
                'report_date' => $request->report_date,
                'findings' => $request->findings,
                'conclusion' => $request->conclusion,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'status' => 'pending',
            ]);

            AuditLog::log('upload_lab_report', "Uploaded lab report: {$report->report_title}");

            DB::commit();

            return redirect()->back()->with('success', 'Lab report uploaded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to upload lab report: ' . $e->getMessage());
        }
    }

    /**
     * Show lab report details.
     */
    public function showLabReport($id)
    {
        $report = LabReport::with(['patient.user', 'doctor', 'medicalRecord', 'reviewer'])->findOrFail($id);

        return view('doctor.lab-report-detail', compact('report'));
    }

    /**
     * Mark lab report as reviewed.
     */
    public function reviewLabReport($id)
    {
        $report = LabReport::findOrFail($id);

        $report->status = 'reviewed';
        $report->reviewed_by = auth()->id();
        $report->reviewed_at = now();
        $report->save();

        AuditLog::log('review_lab_report', "Reviewed lab report #{$id}");

        return redirect()->back()->with('success', 'Lab report marked as reviewed.');
    }

    /**
     * Download lab report file.
     */
    public function downloadLabReport($id)
    {
        $report = LabReport::findOrFail($id);

        // Verify ownership
        if ($report->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this lab report.');
        }

        if (!$report->file_path || !\Storage::disk('public')->exists($report->file_path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        AuditLog::log('download_report', "Downloaded lab report: {$report->file_name}");

        return \Storage::disk('public')->download($report->file_path, $report->file_name);
    }
}
