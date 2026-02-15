<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\LabReport;
use App\Models\AuditLog;

class PatientController extends Controller
{
    /**
     * Show patient dashboard.
     */
    public function dashboard()
    {
        $patient = Patient::where('user_id', auth()->id())
            ->with('user')
            ->firstOrFail();

        $totalVisits = $patient->medicalRecords()->count();

        $activeMeds = $patient->prescriptions()
            ->where('is_active', true)
            ->count();

        $pendingReports = $patient->labReports()
            ->where('status', '!=', 'reviewed')
            ->count();

        $upcomingFollowUps = $patient->medicalRecords()
            ->where('follow_up_date', '>=', today())
            ->orderBy('follow_up_date')
            ->with('doctor')
            ->take(3)
            ->get();

        $nextAppointment = $upcomingFollowUps->first();

        $recentRecords = $patient->medicalRecords()
            ->latest('record_date')
            ->take(3)
            ->with('doctor')
            ->get();

        $recentLabs = $patient->labReports()
            ->with('doctor')
            ->latest()
            ->take(5)
            ->get();

        $lastRecordWithVitals = $patient->medicalRecords()
            ->whereNotNull('vital_signs')
            ->latest()
            ->first();
        
        $lastVitals = $lastRecordWithVitals ? $lastRecordWithVitals->vital_signs : null;

        $activePrescriptionsCount = $patient->prescriptions()
            ->where('is_active', true)
            ->count();

        return view('patient.dashboard', [
            'patient' => $patient,
            'totalVisits' => $totalVisits,
            'activePrescriptions' => $activePrescriptionsCount,
            'pendingReports' => $pendingReports,
            'upcomingFollowUps' => $upcomingFollowUps,
            'nextAppointment' => $nextAppointment,
            'recentRecords' => $recentRecords,
            'recentLabs' => $recentLabs,
            'lastVitals' => $lastVitals
        ]);
    }

    /**
     * Show medical records list.
     */
    public function records(Request $request)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();
        $search = $request->search;

        $records = $patient->medicalRecords()
            ->with(['doctor', 'prescriptions', 'labReports'])
            ->when($search, function ($q) use ($search) {
                $q->where('diagnosis', 'like', "%{$search}%")
                    ->orWhere('chief_complaint', 'like', "%{$search}%");
            })
            ->latest('record_date')
            ->paginate(10);

        return view('patient.records', compact('records', 'search'));
    }

    /**
     * Show medical record details.
     */
    public function showRecord($id)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        $record = MedicalRecord::where('patient_id', $patient->id)
            ->with(['doctor', 'prescriptions', 'labReports'])
            ->findOrFail($id);

        // Security check: verify record belongs to this patient
        if ($record->patient_id !== $patient->id) {
            abort(403, 'Unauthorized access to this medical record.');
        }

        AuditLog::log('view_own_record', "Patient viewed medical record #{$id}");

        // If AJAX request, return partial view
        if (request()->ajax()) {
            return view('patient.partials.record-detail', compact('record', 'patient'))->render();
        }

        return view('patient.record-detail', compact('record'));
    }

    /**
     * Show prescriptions list.
     */
    public function prescriptions(Request $request)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();
        $filter = $request->filter;

        $prescriptions = $patient->prescriptions()
            ->with('doctor')
            ->when($filter === 'active', function ($q) {
                $q->where('is_active', true);
            })
            ->latest()
            ->paginate(10);

        return view('patient.prescriptions', compact('prescriptions', 'filter'));
    }

    /**
     * Show prescription details.
     */
    public function showPrescription($id)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        $prescription = Prescription::where('patient_id', $patient->id)
            ->with(['doctor', 'medicalRecord'])
            ->findOrFail($id);

        // Security check: verify prescription belongs to this patient
        if ($prescription->patient_id !== $patient->id) {
            abort(403, 'Unauthorized access to this prescription.');
        }

        // If AJAX request, return partial view
        if (request()->ajax()) {
            return view('patient.partials.prescription-detail', compact('prescription', 'patient'))->render();
        }

        return view('patient.prescription-detail', compact('prescription'));
    }

    /**
     * Show lab reports list.
     */
    public function labReports(Request $request)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();
        $type = $request->type;

        $reports = $patient->labReports()
            ->with('doctor')
            ->when($type, function ($q) use ($type) {
                $q->where('report_type', $type);
            })
            ->latest()
            ->paginate(10);

        return view('patient.lab-reports', compact('reports', 'type'));
    }

    /**
     * Show lab report details.
     */
    public function showLabReport($id)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        $report = LabReport::where('patient_id', $patient->id)
            ->with(['doctor', 'medicalRecord', 'reviewer'])
            ->findOrFail($id);

        // Security check: verify report belongs to this patient
        if ($report->patient_id !== $patient->id) {
            abort(403, 'Unauthorized access to this lab report.');
        }

        AuditLog::log('view_own_report', "Patient viewed lab report #{$id}");

        // If AJAX request, return partial view
        if (request()->ajax()) {
            return view('patient.partials.lab-report-detail', compact('report', 'patient'))->render();
        }

        return view('patient.lab-report-detail', compact('report'));
    }

    /**
     * Download lab report file.
     */
    public function downloadLabReport($id)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        $report = LabReport::where('patient_id', $patient->id)->findOrFail($id);

        // Security check: verify report belongs to this patient
        if ($report->patient_id !== $patient->id) {
            abort(403, 'Unauthorized access to this lab report.');
        }

        if (!$report->file_path || !\Storage::disk('public')->exists($report->file_path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        AuditLog::log('download_own_report', "Patient downloaded lab report: {$report->file_name}");

        return \Storage::disk('public')->download($report->file_path, $report->file_name);
    }
}
