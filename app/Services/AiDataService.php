<?php

namespace App\Services;

use App\{Employee, Attendance, RunPayroll, Invoice, Overtime, CashAdvance, Position};
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Gathers payroll/attendance/finance metrics from the database and shapes them
 * into safe, structured context for the AI layer. All queries are aggregate and
 * read-only; nothing here accepts raw user SQL. Every method degrades to an
 * empty result on error so a missing/renamed column never 500s a page.
 */
class AiDataService
{
    /**
     * High-level snapshot used for the chat assistant context and summaries.
     */
    public function snapshot(): array
    {
        $snap = [];

        $snap['employees'] = [
            'total' => $this->safe(fn() => Employee::count()),
            'active' => $this->safe(fn() => Employee::where('is_active', 1)->count()),
            'inactive' => $this->safe(fn() => Employee::where('is_active', 0)->count()),
            'positions' => $this->safe(fn() => Position::count()),
        ];

        $snap['payroll'] = [
            'runs' => $this->safe(fn() => RunPayroll::count()),
            'total_net_pay' => round((float) $this->safe(fn() => RunPayroll::sum('net_pay')), 2),
            'last_run' => $this->safe(fn() => optional(RunPayroll::latest('start_date')->first())->start_date),
        ];

        $snap['invoices'] = [
            'total' => $this->safe(fn() => Invoice::count()),
            'paid_total' => round((float) $this->safe(fn() => Invoice::where('paidcheck', 1)->sum('total_famount')), 2),
            'unpaid_total' => round((float) $this->safe(fn() => Invoice::where('paidcheck', 2)->sum('remaining_total')), 2),
            'unpaid_count' => $this->safe(fn() => Invoice::where('paidcheck', 2)->count()),
            'overdue_count' => $this->safe(fn() => Invoice::where('paidcheck', 2)
                ->whereDate('invoice_due_date', '<', now())->count()),
        ];

        $snap['attendance'] = [
            'records' => $this->safe(fn() => Attendance::count()),
            'total_hours' => round((float) $this->safe(fn() => Attendance::sum('num_hour')), 2),
            'this_month_hours' => round((float) $this->safe(fn() => Attendance::whereBetween(
                'date',
                [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()]
            )->sum('num_hour')), 2),
        ];

        $snap['overtime'] = [
            'entries' => $this->safe(fn() => Overtime::count()),
            'total_hours' => round((float) $this->safe(fn() => Overtime::sum('hour')), 2),
            'total_cost' => round((float) $this->safe(function () {
                $row = DB::table('overtimes')
                    ->selectRaw('SUM(rate_amount * hour / 60) as c')
                    ->first();
                return $row ? $row->c : 0;
            }), 2),
        ];

        $snap['cash_advances'] = [
            'entries' => $this->safe(fn() => CashAdvance::count()),
            'total' => round((float) $this->safe(fn() => CashAdvance::sum('rate_amount')), 2),
        ];

        return $snap;
    }

    /**
     * Monthly payroll cost history (for prediction + trend analysis).
     * Returns [['period' => 'YYYY-MM', 'net_pay' => float, 'runs' => int], ...]
     */
    public function payrollHistory(int $months = 12): array
    {
        return $this->safe(function () use ($months) {
            $rows = DB::table('run_payrolls')
                ->selectRaw("DATE_FORMAT(COALESCE(start_date, created_at), '%Y-%m') as period")
                ->selectRaw('SUM(net_pay) as net_pay')
                ->selectRaw('COUNT(*) as runs')
                ->whereNotNull('net_pay')
                ->groupBy('period')
                ->orderBy('period', 'desc')
                ->limit($months)
                ->get();

            return $rows->map(fn($r) => [
                'period' => $r->period,
                'net_pay' => round((float) $r->net_pay, 2),
                'runs' => (int) $r->runs,
            ])->reverse()->values()->all();
        }, []);
    }

    /**
     * Revenue vs payroll cost per month (organization health / economics).
     */
    public function monthlyEconomics(int $months = 12): array
    {
        $revenue = $this->safe(function () use ($months) {
            return DB::table('invoices')
                ->selectRaw("DATE_FORMAT(invoice_date, '%Y-%m') as period")
                ->selectRaw("SUM(CASE WHEN paidcheck = 1 THEN total_famount ELSE 0 END) as collected")
                ->selectRaw("SUM(CASE WHEN paidcheck = 2 THEN remaining_total ELSE 0 END) as outstanding")
                ->selectRaw("SUM(total_famount) as billed")
                ->groupBy('period')->orderBy('period', 'desc')->limit($months)->get()
                ->keyBy('period');
        }, collect());

        $cost = $this->safe(function () use ($months) {
            return DB::table('run_payrolls')
                ->selectRaw("DATE_FORMAT(COALESCE(start_date, created_at), '%Y-%m') as period")
                ->selectRaw('SUM(net_pay) as payroll')
                ->groupBy('period')->orderBy('period', 'desc')->limit($months)->get()
                ->keyBy('period');
        }, collect());

        $periods = collect($revenue->keys())->merge($cost->keys())->unique()->sort()->values()
            ->reverse()->take($months)->reverse()->values();

        return $periods->map(function ($p) use ($revenue, $cost) {
            $rev = $revenue->get($p);
            $cst = $cost->get($p);
            $collected = $rev ? round((float) $rev->collected, 2) : 0.0;
            $billed = $rev ? round((float) $rev->billed, 2) : 0.0;
            $payroll = $cst ? round((float) $cst->payroll, 2) : 0.0;
            return [
                'period' => $p,
                'billed' => $billed,
                'collected' => $collected,
                'outstanding' => $rev ? round((float) $rev->outstanding, 2) : 0.0,
                'payroll_cost' => $payroll,
                'margin' => round($collected - $payroll, 2),
            ];
        })->all();
    }

    /**
     * Attendance analytics, including on-time breakdown and recent monthly hours.
     */
    public function attendanceAnalytics(int $months = 6): array
    {
        $byStatus = $this->safe(function () {
            return DB::table('attendances')
                ->selectRaw('ontime_status, COUNT(*) as c')
                ->groupBy('ontime_status')->get()
                ->mapWithKeys(fn($r) => [(string) ($r->ontime_status ?? 'unknown') => (int) $r->c])->all();
        }, []);

        $monthly = $this->safe(function () use ($months) {
            return DB::table('attendances')
                ->selectRaw("DATE_FORMAT(date, '%Y-%m') as period")
                ->selectRaw('SUM(num_hour) as hours')
                ->selectRaw('COUNT(*) as records')
                ->groupBy('period')->orderBy('period', 'desc')->limit($months)->get()
                ->map(fn($r) => [
                    'period' => $r->period,
                    'hours' => round((float) $r->hours, 2),
                    'records' => (int) $r->records,
                ])->reverse()->values()->all();
        }, []);

        return ['by_status' => $byStatus, 'monthly' => $monthly];
    }

    /**
     * Anomaly / fraud heuristics computed in PHP:
     *  - net pay deviating from an employee's own history
     *  - overtime spikes
     *  - possible duplicate payroll runs (same employee + period + amount)
     */
    public function anomalies(float $deviationPct = 40.0): array
    {
        $flags = [];

        // Duplicate payroll runs
        $dupes = $this->safe(function () {
            return DB::table('run_payrolls')
                ->selectRaw('employee_id, start_date, end_date, net_pay, COUNT(*) as c')
                ->groupBy('employee_id', 'start_date', 'end_date', 'net_pay')
                ->havingRaw('COUNT(*) > 1')
                ->limit(50)->get();
        }, collect());

        foreach ($dupes as $d) {
            $flags[] = [
                'type' => 'duplicate_payroll',
                'severity' => 'high',
                'employee_id' => $d->employee_id,
                'detail' => "Possible duplicate payroll run: {$d->c} identical runs for period {$d->start_date} to {$d->end_date} at net pay {$d->net_pay}.",
            ];
        }

        // Per-employee net pay deviation from their own average
        $stats = $this->safe(function () {
            return DB::table('run_payrolls')
                ->selectRaw('employee_id')
                ->selectRaw('COUNT(*) as c')
                ->selectRaw('AVG(net_pay) as avg_pay')
                ->selectRaw('MAX(net_pay) as max_pay')
                ->selectRaw('MIN(net_pay) as min_pay')
                ->groupBy('employee_id')->havingRaw('COUNT(*) >= 3')->get();
        }, collect());

        foreach ($stats as $s) {
            $avg = (float) $s->avg_pay;
            if ($avg <= 0) {
                continue;
            }
            $dev = (($s->max_pay - $avg) / $avg) * 100;
            if ($dev >= $deviationPct) {
                $flags[] = [
                    'type' => 'pay_deviation',
                    'severity' => $dev >= 80 ? 'high' : 'medium',
                    'employee_id' => $s->employee_id,
                    'detail' => sprintf(
                        'Employee #%s max net pay (%s) is %.0f%% above their average (%s) across %d runs.',
                        $s->employee_id, round($s->max_pay, 2), $dev, round($avg, 2), $s->c
                    ),
                ];
            }
        }

        // Overtime spikes: employees whose overtime cost is far above peers this period
        $ot = $this->safe(function () {
            return DB::table('overtimes')
                ->selectRaw('employee_id')
                ->selectRaw('SUM(rate_amount * hour / 60) as ot_cost')
                ->selectRaw('SUM(hour) as ot_hours')
                ->groupBy('employee_id')
                ->orderByDesc('ot_cost')
                ->limit(10)->get();
        }, collect());

        if ($ot->count() > 1) {
            $costs = $ot->pluck('ot_cost')->map(fn($c) => (float) $c)->all();
            $mean = array_sum($costs) / count($costs);
            foreach ($ot as $o) {
                if ($mean > 0 && ((float) $o->ot_cost) > ($mean * 2)) {
                    $flags[] = [
                        'type' => 'overtime_spike',
                        'severity' => 'medium',
                        'employee_id' => $o->employee_id,
                        'detail' => sprintf(
                            'Employee #%s overtime cost (%s) is more than 2x the peer average (%s); %s hours logged.',
                            $o->employee_id, round($o->ot_cost, 2), round($mean, 2), round($o->ot_hours, 1)
                        ),
                    ];
                }
            }
        }

        return $flags;
    }

    /**
     * Run a callback, returning $default on any DB/other error.
     */
    protected function safe(callable $fn, $default = 0)
    {
        try {
            $result = $fn();
            return $result === null ? $default : $result;
        } catch (Throwable $e) {
            report($e);
            return $default;
        }
    }
}
