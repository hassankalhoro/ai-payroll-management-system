<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\{Employee, Attendance, RunPayroll, Invoice, InvoiceItem, Overtime, CashAdvance, Position, Schedule, Deduction, Tenant};

/**
 * DemoDataSeeder — generates ~12 months of realistic sample data so the AI
 * features (chat, insights, anomaly/fraud detection, predictive payroll,
 * organization-health analysis, attendance analytics) have real material.
 *
 * Run ONLY on an empty/demo database:
 *     php artisan db:seed --class=DemoDataSeeder
 *
 * It refuses to run if employees already exist, to avoid polluting real data.
 * Data intentionally includes: a payroll growth trend, a pay-spike anomaly, a
 * duplicate payroll run, an overtime outlier, and a rising revenue trend with
 * some overdue invoices.
 */
class DemoDataSeeder extends Seeder
{
    protected $months = 12;      // history depth
    protected $employeeCount = 15;
    protected $customerCount = 8;

    protected $firstNames = ['Ahmed','Sara','Bilal','Ayesha','Omar','Fatima','Hassan','Zainab','Imran','Noor','Usman','Hina','Kamran','Maryam','Tariq','Rabia','Faisal','Sana','Adnan','Iqra'];
    protected $lastNames  = ['Khan','Malik','Butt','Chaudhry','Qureshi','Hussain','Sheikh','Abbasi','Raza','Siddiqui','Farooq','Javed','Iqbal','Mahmood','Baig'];
    protected $positionTitles = ['Software Engineer','Senior Developer','Accountant','HR Manager','Sales Executive','Support Agent','Project Manager','QA Engineer','DevOps Engineer','Marketing Lead','Payroll Clerk','Team Lead','Consultant','Office Admin','Intern'];

    public function run()
    {
        if (Employee::count() >= 3) {
            $this->command->warn('DemoDataSeeder skipped: employees already exist (' . Employee::count() . ').');
            $this->command->line('Run this only on an empty/demo database, e.g. after a fresh migrate.');
            return;
        }

        $this->command->info('Seeding demo data (this may take a few seconds)...');

        $positions = $this->seedPositions();
        $schedules = $this->seedSchedules();
        $this->seedDeductions();

        $employees = $this->seedEmployees($positions, $schedules);
        $this->seedAttendance($employees);
        $this->seedOvertimeAndAdvances($employees);
        $this->seedPayrollRuns($employees);

        $customers = $this->seedCustomers();
        $this->seedInvoices($customers);

        $this->command->info('Demo data seeded: ' . count($employees) . ' employees, ' . count($customers) . ' customers, ' . $this->months . ' months of history.');
    }

    // ------------------------------------------------------------------
    // Reference data
    // ------------------------------------------------------------------
    protected function seedPositions()
    {
        $positions = Position::all();
        if ($positions->isEmpty()) {
            foreach ($this->positionTitles as $title) {
                Position::create(['title' => $title, 'description' => $title . ' role']);
            }
            $positions = Position::all();
        }
        return $positions;
    }

    protected function seedSchedules()
    {
        $schedules = Schedule::all();
        if ($schedules->isEmpty()) {
            Schedule::create(['time_in' => '09:00:00', 'time_out' => '18:00:00']);
            Schedule::create(['time_in' => '08:00:00', 'time_out' => '17:00:00']);
            Schedule::create(['time_in' => '10:00:00', 'time_out' => '19:00:00']);
            $schedules = Schedule::all();
        }
        return $schedules;
    }

    protected function seedDeductions()
    {
        if (Deduction::count() === 0) {
            Deduction::create(['name' => 'Income Tax', 'amount' => 10, 'value_type' => 1, 'deductiontype' => 'tax', 'taxtype' => 'federal', 'description' => 'Federal income tax withholding']);
            Deduction::create(['name' => 'Social Security', 'amount' => 6.2, 'value_type' => 1, 'deductiontype' => 'tax', 'taxtype' => 'fica', 'description' => 'Social security']);
            Deduction::create(['name' => 'Health Insurance', 'amount' => 150, 'value_type' => 2, 'deductiontype' => 'benefit', 'description' => 'Monthly health premium']);
        }
    }

    // ------------------------------------------------------------------
    // Employees
    // ------------------------------------------------------------------
    protected function seedEmployees($positions, $schedules)
    {
        $employees = [];
        for ($i = 0; $i < $this->employeeCount; $i++) {
            $fn = $this->firstNames[$i % count($this->firstNames)];
            $ln = $this->lastNames[($i * 3) % count($this->lastNames)];
            $position = $positions->random();
            $schedule = $schedules->random();
            $salary = random_int(2800, 9000);
            $isSalary = $i % 3 !== 0; // two-thirds salaried

            $emp = Employee::create([
                'first_name' => $fn,
                'last_name' => $ln,
                'email' => Str::slug($fn . '.' . $ln . $i) . '@example.com',
                'work_email' => Str::slug($fn . '.' . $ln) . '@company.com',
                'phone' => '555-0' . str_pad((string) (100 + $i), 3, '0', STR_PAD_LEFT),
                'address' => (100 + $i) . ' Demo Street, Karachi',
                'gender' => ($i % 2 === 0) ? 'Male' : 'Female',
                'birthdate' => '199' . ($i % 9) . '-0' . (1 + ($i % 9)) . '-15',
                'position_id' => $position->id,
                'schedule_id' => $schedule->id,
                'pay_type' => $isSalary ? 'salary' : 'hourly',
                'salary' => $salary,
                'rate_per_hour' => round($salary / 176, 2),
                'ssn' => '000-00-' . str_pad((string) (1000 + $i), 4, '0', STR_PAD_LEFT),
                'hire_date' => Carbon::now()->subMonths($this->months + random_int(0, 24))->toDateString(),
                'hire_date_status' => 'active',
                'is_active' => 1,
            ]);
            $employees[] = $emp;
        }
        return $employees;
    }

    // ------------------------------------------------------------------
    // Attendance (bulk insert for speed; values pre-formatted)
    // ------------------------------------------------------------------
    protected function seedAttendance(array $employees)
    {
        $rows = [];
        $now = Carbon::now();
        $start = $now->copy()->subMonths($this->months)->startOfMonth();

        foreach ($employees as $idx => $emp) {
            $cursor = $start->copy();
            while ($cursor->lte($now)) {
                if ($cursor->isWeekday()) {
                    // ~85% present
                    if (random_int(1, 100) <= 85) {
                        $late = random_int(1, 100) <= 15;
                        $inHour = $late ? 9 : 8;
                        $inMin = $late ? random_int(10, 40) : random_int(0, 9);
                        $minutes = random_int(450, 540);
                        $rows[] = [
                            'employee_id' => $emp->id,
                            'date' => $cursor->toDateString(),
                            'time_in' => sprintf('%02d:%02d:00', $inHour + 1, $inMin),
                            'time_out' => sprintf('%02d:%02d:00', $inHour + 10, $inMin),
                            'num_hour' => (string) $minutes,
                            'ontime_status' => $late ? 0 : 1,
                            'created_at' => $cursor->toDateTimeString(),
                            'updated_at' => $cursor->toDateTimeString(),
                        ];
                    }
                }
                $cursor->addDay();
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('attendances')->insert($chunk);
        }
        $this->command->info('  attendance: ' . count($rows) . ' records');
    }

    // ------------------------------------------------------------------
    // Overtime + cash advances (includes one overtime outlier)
    // ------------------------------------------------------------------
    protected function seedOvertimeAndAdvances(array $employees)
    {
        $ot = 0; $ca = 0;
        $now = Carbon::now();
        foreach ($employees as $idx => $emp) {
            for ($m = $this->months; $m >= 0; $m--) {
                $date = $now->copy()->subMonths($m);
                // One employee is a deliberate overtime outlier
                $isOutlier = ($idx === 0);
                $chance = $isOutlier ? 90 : 25;
                if (random_int(1, 100) <= $chance) {
                    $hours = $isOutlier ? random_int(30, 60) : random_int(3, 15); // minutes*... stored as 'hour' (double)
                    Overtime::create([
                        'title' => 'Overtime ' . $date->format('M Y') . ' #' . ($ot + 1),
                        'rate_amount' => round($emp->rate_per_hour * 1.5, 2),
                        'hour' => $hours,
                        'employee_id' => $emp->id,
                        'date' => $date->copy()->day(random_int(1, 28))->toDateString(),
                        'description' => 'Approved overtime hours',
                    ]);
                    $ot++;
                }
                if (random_int(1, 100) <= 8) {
                    CashAdvance::create([
                        'title' => 'Advance ' . $date->format('M Y') . ' #' . ($ca + 1),
                        'rate_amount' => random_int(100, 600),
                        'employee_id' => $emp->id,
                        'date' => $date->copy()->day(random_int(1, 28))->toDateString(),
                    ]);
                    $ca++;
                }
            }
        }
        $this->command->info("  overtime: $ot, cash advances: $ca");
    }

    // ------------------------------------------------------------------
    // Payroll runs (monthly, with growth trend + anomalies)
    // ------------------------------------------------------------------
    protected function seedPayrollRuns(array $employees)
    {
        $now = Carbon::now();
        $rows = [];
        foreach ($employees as $idx => $emp) {
            $base = (float) ($emp->pay_type === 'salary' ? $emp->salary : $emp->salary);
            for ($m = $this->months - 1; $m >= 0; $m--) {
                $periodEnd = $now->copy()->subMonths($m)->endOfMonth();
                $periodStart = $periodEnd->copy()->startOfMonth();
                // gentle upward trend + noise
                $growth = 1 + (($this->months - $m) * 0.006);
                $noise = 0.94 + (random_int(0, 12) / 100);
                $net = round($base * $growth * $noise * 0.78, 2); // ~net after deductions

                // Deliberate anomaly: a large pay spike for employee #1, 3 months ago
                if ($idx === 1 && $m === 3) {
                    $net = round($net * 2.4, 2);
                }

                $rows[] = [
                    'employee_id' => $emp->id,
                    'unique_id' => 'PR-' . $emp->id . '-' . $periodEnd->format('Ym'),
                    'start_date' => $periodStart->toDateString(),
                    'end_date' => $periodEnd->toDateString(),
                    'net_pay' => $net,
                    'total_hours_payroll' => random_int(150, 180),
                    'created_at' => $periodEnd->toDateTimeString(),
                    'updated_at' => $periodEnd->toDateTimeString(),
                ];

                // Deliberate duplicate run for employee #2, 5 months ago (fraud signal)
                if ($idx === 2 && $m === 5) {
                    $rows[] = [
                        'employee_id' => $emp->id,
                        'unique_id' => 'PR-DUP-' . $emp->id . '-' . $periodEnd->format('Ym'),
                        'start_date' => $periodStart->toDateString(),
                        'end_date' => $periodEnd->toDateString(),
                        'net_pay' => $net,
                        'total_hours_payroll' => random_int(150, 180),
                        'created_at' => $periodEnd->toDateTimeString(),
                        'updated_at' => $periodEnd->toDateTimeString(),
                    ];
                }
            }
        }

        foreach (array_chunk($rows, 300) as $chunk) {
            DB::table('run_payrolls')->insert($chunk);
        }
        $this->command->info('  payroll runs: ' . count($rows));
    }

    // ------------------------------------------------------------------
    // Customers (tenants)
    // ------------------------------------------------------------------
    protected function seedCustomers()
    {
        $names = ['Acme Corp','Globex Ltd','Initech','Umbrella Co','Stark Industries','Wayne Enterprises','Vandelay','Hooli'];
        $customers = [];
        for ($i = 0; $i < $this->customerCount; $i++) {
            $customers[] = Tenant::create([
                'title' => $names[$i % count($names)] . ($i >= count($names) ? ' ' . $i : ''),
                'email' => 'billing' . $i . '@' . Str::slug($names[$i % count($names)]) . '.com',
                'address' => (200 + $i) . ' Client Ave, Karachi',
                'tax' => 5,
            ]);
        }
        return $customers;
    }

    // ------------------------------------------------------------------
    // Invoices (rising revenue trend; some unpaid/overdue) + line items
    // ------------------------------------------------------------------
    protected function seedInvoices(array $customers)
    {
        $now = Carbon::now();
        $count = 0;
        foreach ($customers as $cIdx => $cust) {
            for ($m = $this->months - 1; $m >= 0; $m--) {
                // Not every customer bills every month
                if (random_int(1, 100) <= 30) {
                    continue;
                }
                $invoiceDate = $now->copy()->subMonths($m)->day(random_int(1, 25));
                $dueDate = $invoiceDate->copy()->addDays(random_int(15, 30));

                // Revenue grows over time
                $growth = 1 + (($this->months - $m) * 0.03);
                $subtotal = round(random_int(1500, 9000) * $growth, 2);
                $tax = round($subtotal * 0.05, 2);
                $total = round($subtotal + $tax, 2);

                // Older invoices paid; recent ones unpaid; some overdue
                $isPaid = $m >= 3 ? (random_int(1, 100) <= 90) : (random_int(1, 100) <= 45);

                $invoice = Invoice::create([
                    'customer_id' => $cust->id,
                    'type' => 1,
                    'invoice_date' => $invoiceDate->toDateTimeString(),
                    'invoice_due_date' => $dueDate->toDateTimeString(),
                    'total_iamount' => $subtotal,
                    'total_tax' => $tax,
                    'total_famount' => $total,
                    'paidcheck' => $isPaid ? 1 : 2,
                    'paid_amount' => $isPaid ? $total : 0,
                    'remaining_total' => $isPaid ? 0 : $total,
                ]);
                $count++;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => 'Professional services - ' . $invoiceDate->format('F Y'),
                    'qty' => 1,
                    'rate' => $subtotal,
                    'amount' => $subtotal,
                    'tax' => $tax,
                    'tax_applied' => 1,
                ]);
            }
        }
        $this->command->info('  invoices: ' . $count);
    }
}
