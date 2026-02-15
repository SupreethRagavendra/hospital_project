@props(['record', 'type' => 'record'])

<div id="printSection" class="d-none">
    <style>
        @media print {
            body * { visibility: hidden; }
            #printSection, #printSection * { visibility: visible; }
            #printSection {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white;
                z-index: 9999;
                display: block !important;
                font-family: 'Times New Roman', serif;
                color: black;
            }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
            @page { margin: 2cm; size: A4; }
        }
    </style>

    <div class="p-4">

        <div class="text-center border-bottom pb-4 mb-4">
            <h1 class="fw-bold text-uppercase mb-1" style="color: #000;">Secure EMR Hospital</h1>
            <p class="mb-0">123 Health Avenue, Medical District, City - 560001</p>
            <p class="mb-0">Phone: +91 98765 43210 | Email: contact@hospital.com</p>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <strong>Patient Name:</strong> {{ $record->patient->name ?? 'N/A' }}<br>
                <strong>Patient ID:</strong> PAT-{{ str_pad($record->patient->id ?? 0, 5, '0', STR_PAD_LEFT) }}<br>
                <strong>Age/Gender:</strong>
                {{ isset($record->patient->dob) ? \Carbon\Carbon::parse($record->patient->dob)->age : 'N/A' }} /
                {{ ucfirst($record->patient->gender ?? 'N/A') }}
            </div>
            <div class="col-6 text-end">
                <strong>Date:</strong> {{ \Carbon\Carbon::parse($record->created_at ?? now())->format('d M, Y h:i A') }}<br>
                <strong>Doctor:</strong> Dr. {{ $record->doctor->name ?? 'N/A' }}<br>
                <strong>Ref No:</strong> REC-{{ $record->id ?? 0 }}
            </div>
        </div>

        <hr>

        @if($type === 'record')
            <h3 class="text-center text-uppercase fw-bold mb-4" style="text-decoration: underline;">Medical Record Summary</h3>

            <div class="mb-4">
                <h5 class="fw-bold">Subjective / Symptoms</h5>
                <p>{{ $record->description ?? '' }}</p>
                <p><i>{{ $record->symptoms ?? '' }}</i></p>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold">Vitals</h5>
                <table class="table table-bordered table-sm w-50">
                    <tr><td>BP</td><td>{{ $record->blood_pressure ?? '-' }}</td></tr>
                    <tr><td>Temp</td><td>{{ $record->temperature ?? '-' }}</td></tr>
                    <tr><td>Weight</td><td>{{ $record->weight ?? '-' }}</td></tr>
                </table>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold">Diagnosis</h5>
                <p>{{ $record->diagnosis ?? '' }}</p>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold">Treatment Plan</h5>
                <p>{!! nl2br(e($record->treatment_plan ?? '')) !!}</p>
            </div>

            @if(isset($record->prescriptions) && $record->prescriptions->count() > 0)
            <div class="mb-4">
                <h5 class="fw-bold">Prescriptions</h5>
                <ul>
                @foreach($record->prescriptions as $p)
                    <li>{{ $p->medication_name }} - {{ $p->dosage }} ({{ $p->frequency }}) for {{ $p->duration }}</li>
                @endforeach
                </ul>
            </div>
            @endif

        @elseif($type === 'prescription')
            <div class="display-1 text-primary fw-bold mb-4">℞</div>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Medicine</th>
                        <th>Dosage</th>
                        <th>Frequency</th>
                        <th>Duration</th>
                        <th>Route</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record as $pres)
                    <tr>
                        <td class="fw-bold">{{ $pres->medication_name }}</td>
                        <td>{{ $pres->dosage }}</td>
                        <td>{{ $pres->frequency }}</td>
                        <td>{{ $pres->duration }}</td>
                        <td>{{ $pres->route }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 p-3 border rounded">
                <strong>Instructions:</strong>
                <p>{{ $record->first()->instructions ?? 'Take as prescribed.' }}</p>
            </div>
        @endif

        <div class="row mt-5 pt-5">
            <div class="col-6 offset-6 text-center">
                <div class="border-top border-dark pt-2">
                    <p class="fw-bold mb-0">Dr. {{ $record->doctor->name ?? 'Signature' }}</p>
                    <small>Authorized Medical Officer</small>
                </div>
            </div>
        </div>

        <div class="fixed-bottom text-center small text-muted border-top p-2">
            Generated by Secure EMR System on {{ now()->toDateTimeString() }}
        </div>
    </div>
</div>