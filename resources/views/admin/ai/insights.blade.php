@extends('admin.layout.app')

@section('title') AI Insights @endsection

@section('content')
<div class="ai-hero">
    <h3><i class="ik ik-cpu"></i> AI Insights &amp; Analytics</h3>
    <p>Executive reports, anomaly detection, payroll forecasting, organization-health analysis and document OCR — powered by OpenAI over your live data.</p>
</div>

@if(!$hasKey)
<div class="alert alert-warning">
    <strong>OpenAI not configured.</strong> Set the <code>OPENAI_API_KEY</code> environment variable (Railway secret) to enable AI features.
</div>
@endif

<div class="ai-tabs">
    <button class="ai-tab active" data-tab="report">📊 AI Report</button>
    <button class="ai-tab" data-tab="anomaly">🛡️ Anomaly / Fraud</button>
    <button class="ai-tab" data-tab="forecast">📈 Predictive Payroll</button>
    <button class="ai-tab" data-tab="health">🏥 Org Health</button>
    <button class="ai-tab" data-tab="ocr">📄 OCR Extraction</button>
</div>

{{-- AI Report --}}
<div class="ai-pane" id="pane-report">
    <div class="ai-card">
        <button class="btn btn-primary mb-3" id="btn-report"><i class="ik ik-refresh-cw"></i> Generate AI Report</button>
        <div id="report-metrics" class="ai-metric-grid mb-3"></div>
        <div id="report-out" class="ai-md"></div>
    </div>
</div>

{{-- Anomaly --}}
<div class="ai-pane" id="pane-anomaly" style="display:none">
    <div class="ai-card">
        <div class="d-flex align-items-center mb-3">
            <button class="btn btn-primary" id="btn-anomaly"><i class="ik ik-shield"></i> Scan for Anomalies</button>
            <div class="ml-3">
                <label class="mb-0 mr-2" style="font-size:13px;color:#6b7194;font-weight:700">Deviation threshold %</label>
                <input type="number" id="anomaly-dev" value="40" min="5" max="200" style="width:90px" class="form-control d-inline-block">
            </div>
        </div>
        <div id="anomaly-flags" class="mb-3"></div>
        <div id="anomaly-out" class="ai-md"></div>
    </div>
</div>

{{-- Forecast --}}
<div class="ai-pane" id="pane-forecast" style="display:none">
    <div class="ai-card">
        <div class="d-flex align-items-center mb-3">
            <button class="btn btn-primary" id="btn-forecast"><i class="ik ik-trending-up"></i> Forecast Payroll</button>
            <div class="ml-3">
                <label class="mb-0 mr-2" style="font-size:13px;color:#6b7194;font-weight:700">Periods ahead</label>
                <input type="number" id="forecast-periods" value="3" min="1" max="6" style="width:80px" class="form-control d-inline-block">
            </div>
        </div>
        <div id="forecast-metrics" class="ai-metric-grid mb-3"></div>
        <div id="forecast-out" class="ai-md"></div>
    </div>
</div>

{{-- Org Health --}}
<div class="ai-pane" id="pane-health" style="display:none">
    <div class="ai-card">
        <button class="btn btn-primary mb-3" id="btn-health"><i class="ik ik-activity"></i> Analyze Organization Health</button>
        <div id="health-metrics" class="ai-metric-grid mb-3"></div>
        <div id="health-out" class="ai-md"></div>
    </div>
</div>

{{-- OCR --}}
<div class="ai-pane" id="pane-ocr" style="display:none">
    <div class="ai-card">
        <p style="color:#6b7194">Upload a payslip, invoice, ID or timesheet <strong>image</strong> (JPG/PNG/WEBP, max 8MB). The AI extracts structured fields you can use to pre-fill forms.</p>
        <div class="ai-drop" id="ocr-drop">
            <i class="ik ik-upload-cloud" style="font-size:34px"></i>
            <div class="mt-2"><strong>Click or drop an image here</strong></div>
            <input type="file" id="ocr-file" accept="image/*" style="display:none">
        </div>
        <div id="ocr-preview" class="mt-3"></div>
        <div id="ocr-out" class="mt-3"></div>
    </div>
</div>
@endsection

@section('js')
<script>
window.AI_ROUTES = {
    insights: "{{ route('admin.ai.insights') }}",
    anomalies: "{{ route('admin.ai.anomalies') }}",
    predict: "{{ route('admin.ai.predict') }}",
    orgHealth: "{{ route('admin.ai.orgHealth') }}",
    ocr: "{{ route('admin.ai.ocr') }}",
    csrf: "{{ csrf_token() }}"
};
</script>
<script src="{{ asset('admin_assets/src/js/ai-insights.js') }}"></script>
@endsection
