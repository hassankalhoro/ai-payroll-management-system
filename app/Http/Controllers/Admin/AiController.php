<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AiDataService;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Throwable;

/**
 * Endpoints powering the AI layer. All routes sit behind the `auth` middleware
 * (admin session). Responses are JSON consumed by the assistant UI.
 */
class AiController extends Controller
{
    /** @var AiDataService */
    protected $data;

    public function __construct(AiDataService $data)
    {
        $this->data = $data;
    }

    /**
     * Render the AI Insights page (tabs: report, anomalies, prediction,
     * org-health, OCR). Data is loaded via AJAX from the JSON endpoints.
     */
    public function insightsPage()
    {
        return view('admin.ai.insights', [
            'hasKey' => !empty(config('ai.key')),
        ]);
    }

    /**
     * Chat assistant: answers questions about the company's payroll data using a
     * curated snapshot as context (no free-form SQL is ever executed).
     */
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:4000']);

        try {
            $ai = new OpenAIService();

            $system = "You are the AI assistant embedded in a payroll & invoicing management system. "
                . "Answer using ONLY the company data snapshot provided. Be concise, specific and use figures. "
                . "If the data does not contain the answer, say so plainly instead of guessing. "
                . "Format responses in short Markdown (bullets, bold labels).\n\n"
                . "COMPANY DATA SNAPSHOT (JSON):\n"
                . json_encode([
                    'snapshot' => $this->data->snapshot(),
                    'monthly_economics' => $this->data->monthlyEconomics(6),
                    'attendance' => $this->data->attendanceAnalytics(6),
                ], JSON_PRETTY_PRINT);

            // Client sends prior turns as [{role, content}]; keep it bounded.
            $history = $request->input('history', []);
            $messages = [['role' => 'system', 'content' => $system]];
            if (is_array($history)) {
                foreach (array_slice($history, -8) as $m) {
                    if (isset($m['role'], $m['content']) && in_array($m['role'], ['user', 'assistant'], true)) {
                        $messages[] = ['role' => $m['role'], 'content' => (string) $m['content']];
                    }
                }
            }
            $messages[] = ['role' => 'user', 'content' => $request->input('message')];

            $reply = $ai->chat($messages, ['max_tokens' => 900]);

            return response()->json(['ok' => true, 'reply' => $reply]);
        } catch (Throwable $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Smart summary / AI report over the existing data.
     */
    public function insights(Request $request)
    {
        try {
            $ai = new OpenAIService();
            $context = [
                'snapshot' => $this->data->snapshot(),
                'monthly_economics' => $this->data->monthlyEconomics(12),
                'payroll_history' => $this->data->payrollHistory(12),
                'attendance' => $this->data->attendanceAnalytics(6),
            ];

            $reply = $ai->chat([
                ['role' => 'system', 'content' =>
                    'You are a payroll & business analyst. Produce a clear executive report in Markdown with these sections: '
                    . '**Overview**, **Payroll**, **Revenue & Invoicing**, **Attendance**, **Risks**, **Recommended Actions**. '
                    . 'Use the actual numbers from the JSON. Be concise and factual.'],
                ['role' => 'user', 'content' => "Company data:\n" . json_encode($context, JSON_PRETTY_PRINT)],
            ], ['max_tokens' => 1400]);

            return response()->json(['ok' => true, 'report' => $reply, 'context' => $context]);
        } catch (Throwable $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Anomaly / fraud detection: heuristic flags + AI explanation.
     */
    public function anomalies(Request $request)
    {
        try {
            $flags = $this->data->anomalies((float) $request->input('deviation', 40));

            $narrative = '';
            if (!empty($flags)) {
                $ai = new OpenAIService();
                $narrative = $ai->chat([
                    ['role' => 'system', 'content' =>
                        'You are a payroll fraud/risk analyst. Given detected anomaly flags (JSON), explain the most serious '
                        . 'risks first, why each is suspicious, and concrete verification steps. Markdown, concise.'],
                    ['role' => 'user', 'content' => json_encode($flags, JSON_PRETTY_PRINT)],
                ], ['max_tokens' => 1000]);
            } else {
                $narrative = 'No payroll anomalies were detected with the current thresholds. That is a good sign — '
                    . 'net pay, overtime and duplicate-run checks all look consistent.';
            }

            return response()->json(['ok' => true, 'flags' => $flags, 'analysis' => $narrative]);
        } catch (Throwable $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Predictive payroll: linear-trend forecast for the next periods + narrative.
     */
    public function predict(Request $request)
    {
        try {
            $periods = (int) $request->input('periods', 3);
            $periods = max(1, min(6, $periods));
            $history = $this->data->payrollHistory(12);

            $forecast = $this->forecast($history, $periods);

            $narrative = '';
            if (count($history) >= 2) {
                $ai = new OpenAIService();
                $narrative = $ai->chat([
                    ['role' => 'system', 'content' =>
                        'You are a workforce finance analyst. Given historical monthly payroll and a computed linear forecast, '
                        . 'explain the trend, the confidence/caveats, and budgeting advice. Markdown, concise.'],
                    ['role' => 'user', 'content' => json_encode([
                        'history' => $history,
                        'forecast' => $forecast,
                    ], JSON_PRETTY_PRINT)],
                ], ['max_tokens' => 900]);
            } else {
                $narrative = 'Not enough payroll history to forecast yet. Run payroll for at least 2-3 periods and this '
                    . 'will project future payroll cost.';
            }

            return response()->json(['ok' => true, 'history' => $history, 'forecast' => $forecast, 'analysis' => $narrative]);
        } catch (Throwable $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Organization health / economic direction (growing vs declining), informed
     * by revenue-vs-payroll margin and attendance.
     */
    public function orgHealth(Request $request)
    {
        try {
            $econ = $this->data->monthlyEconomics(12);
            $attendance = $this->data->attendanceAnalytics(6);
            $snapshot = $this->data->snapshot();

            // Compute simple signals: margin trend, collection rate, headcount.
            $signals = $this->healthSignals($econ);

            $ai = new OpenAIService();
            $assessment = $ai->chat([
                ['role' => 'system', 'content' =>
                    'You are a business health analyst for a services company. Using monthly economics (billed/collected/'
                    . 'outstanding/payroll/margin), computed signals and attendance data, judge whether the organization is '
                    . 'economically growing, stable, or declining. Give a clear verdict, the evidence, key risks, and 3-5 '
                    . 'recommended actions. Markdown with a bolded one-line VERDICT at the top.'],
                ['role' => 'user', 'content' => json_encode([
                    'monthly_economics' => $econ,
                    'signals' => $signals,
                    'attendance' => $attendance,
                    'snapshot' => $snapshot,
                ], JSON_PRETTY_PRINT)],
            ], ['max_tokens' => 1300]);

            return response()->json([
                'ok' => true,
                'assessment' => $assessment,
                'economics' => $econ,
                'signals' => $signals,
                'attendance' => $attendance,
            ]);
        } catch (Throwable $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * OCR: extract structured fields from an uploaded payslip/ID image using an
     * OpenAI vision model. Images only in v1.
     */
    public function ocr(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:jpg,jpeg,png,webp|max:8192',
        ]);

        try {
            $file = $request->file('document');
            $mime = $file->getMimeType();
            $dataUrl = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));

            $ai = new OpenAIService();
            $raw = $ai->vision($dataUrl,
                'Extract structured data from this document (it may be a payslip, invoice, ID, or timesheet). '
                . 'Return JSON with keys you can infer, for example: document_type, employee_name, employee_id, '
                . 'period_start, period_end, gross_pay, deductions (array of {name, amount}), net_pay, hours_worked, '
                . 'invoice_number, invoice_date, total_amount, currency, and a "confidence" (0-1) plus "raw_text". '
                . 'Only include fields you actually see. Do not invent values.',
                ['json' => true, 'max_tokens' => 1200]);

            $decoded = json_decode($raw, true);

            return response()->json(['ok' => true, 'extracted' => is_array($decoded) ? $decoded : ['raw_text' => $raw]]);
        } catch (Throwable $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // ---------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------

    /**
     * Least-squares linear forecast over payroll history.
     */
    protected function forecast(array $history, int $periods): array
    {
        $n = count($history);
        if ($n < 2) {
            return [];
        }

        $xs = $ys = [];
        foreach (array_values($history) as $i => $row) {
            $xs[] = $i;
            $ys[] = (float) ($row['net_pay'] ?? 0);
        }

        $meanX = array_sum($xs) / $n;
        $meanY = array_sum($ys) / $n;
        $num = $den = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $num += ($xs[$i] - $meanX) * ($ys[$i] - $meanY);
            $den += ($xs[$i] - $meanX) ** 2;
        }
        $slope = $den != 0.0 ? $num / $den : 0.0;
        $intercept = $meanY - $slope * $meanX;

        $last = $history[$n - 1];
        $lastPeriod = $last['period'] ?? date('Y-m');
        $out = [];
        for ($p = 1; $p <= $periods; $p++) {
            $value = $intercept + $slope * ($n - 1 + $p);
            $out[] = [
                'period' => $this->addMonths($lastPeriod, $p),
                'projected_net_pay' => round(max(0, $value), 2),
            ];
        }

        return [
            'method' => 'linear_regression',
            'slope_per_month' => round($slope, 2),
            'trend' => $slope > 1 ? 'rising' : ($slope < -1 ? 'falling' : 'flat'),
            'projections' => $out,
        ];
    }

    /**
     * Derive plain signals from monthly economics for the org-health prompt.
     */
    protected function healthSignals(array $econ): array
    {
        if (empty($econ)) {
            return ['note' => 'No economic history available yet.'];
        }

        $first = $econ[0];
        $last = $econ[count($econ) - 1];

        $totalBilled = array_sum(array_column($econ, 'billed'));
        $totalCollected = array_sum(array_column($econ, 'collected'));
        $totalOutstanding = array_sum(array_column($econ, 'outstanding'));
        $totalPayroll = array_sum(array_column($econ, 'payroll_cost'));

        return [
            'months_analyzed' => count($econ),
            'collection_rate_pct' => $totalBilled > 0 ? round(($totalCollected / $totalBilled) * 100, 1) : 0,
            'total_billed' => round($totalBilled, 2),
            'total_collected' => round($totalCollected, 2),
            'total_outstanding' => round($totalOutstanding, 2),
            'total_payroll_cost' => round($totalPayroll, 2),
            'overall_margin' => round($totalCollected - $totalPayroll, 2),
            'margin_first_month' => $first['margin'] ?? 0,
            'margin_last_month' => $last['margin'] ?? 0,
            'collected_first_month' => $first['collected'] ?? 0,
            'collected_last_month' => $last['collected'] ?? 0,
        ];
    }

    /**
     * Add N months to a 'YYYY-MM' string.
     */
    protected function addMonths(string $ym, int $months): string
    {
        $ts = strtotime($ym . '-01');
        if ($ts === false) {
            $ts = time();
        }
        return date('Y-m', strtotime("+{$months} month", $ts));
    }
}
