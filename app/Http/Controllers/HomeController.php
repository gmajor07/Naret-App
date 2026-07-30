<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if ((int) auth()->user()->role_id === 1) {
            return redirect()->route('admin');
        }

        return redirect()->route('seller');
    }

    public function backupDatabase()
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}");

        if (! $database || ($database['driver'] ?? null) !== 'mysql') {
            abort(422, 'Database backup is currently supported for MySQL only.');
        }

        $dumpBinary = env(
            'DB_DUMP_BINARY',
            PHP_OS_FAMILY === 'Darwin' ? '/Applications/XAMPP/xamppfiles/bin/mysqldump' : 'mysqldump'
        );

        if (is_string($dumpBinary) && str_starts_with($dumpBinary, '/') && ! is_executable($dumpBinary)) {
            $dumpBinary = 'mysqldump';
        }

        $homeDirectory = getenv('HOME')
            ?: getenv('USERPROFILE')
            ?: (getenv('HOMEDRIVE') && getenv('HOMEPATH') ? getenv('HOMEDRIVE') . getenv('HOMEPATH') : null)
            ?: ($_SERVER['HOME'] ?? null);
        $defaultBackupDirectory = $homeDirectory
            ? $homeDirectory . DIRECTORY_SEPARATOR . 'Documents' . DIRECTORY_SEPARATOR . 'Naret Database Backups'
            : storage_path('app/backups');
        $backupDirectory = env('DB_BACKUP_PATH', $defaultBackupDirectory);

        if (! is_dir($backupDirectory) && ! mkdir($backupDirectory, 0750, true) && ! is_dir($backupDirectory)) {
            abort(500, 'Could not create the database backup folder.');
        }

        $filename = 'naret_database_backup_' . now()->format('Y-m-d_H-i-s') . '_' . uniqid() . '.sql';
        $backupPath = $backupDirectory . DIRECTORY_SEPARATOR . $filename;

        $command = [
            $dumpBinary,
            '--host=' . ($database['host'] ?? '127.0.0.1'),
            '--port=' . ($database['port'] ?? 3306),
            '--user=' . ($database['username'] ?? ''),
            '--single-transaction',
            '--triggers',
            $database['database'] ?? '',
        ];

        $handle = fopen($backupPath, 'wb');
        if ($handle === false) {
            @unlink($backupPath);
            abort(500, 'Could not open the database backup file.');
        }

        $process = new Process($command, base_path(), [
            'MYSQL_PWD' => (string) ($database['password'] ?? ''),
        ]);

        $exitCode = $process->run(function (string $type, string $buffer) use ($handle): void {
            if ($type === Process::OUT) {
                fwrite($handle, $buffer);
            }
        });
        fclose($handle);

        if ($exitCode !== 0) {
            @unlink($backupPath);
            abort(500, 'Database backup failed: ' . trim($process->getErrorOutput()));
        }

        return response()->download($backupPath, $filename, [
            'Content-Type' => 'application/sql',
        ])->deleteFileAfterSend(false);
    }


    public function admin(Request $request){

        $request->validate([
            'chart_year' => 'nullable|integer',
            'chart_from_date' => 'nullable|date',
            'chart_to_date' => 'nullable|date|after_or_equal:chart_from_date',
        ]);

        $now = Carbon::now();
        $currentYear = $now->year;
        $currentMonthStart = $now->copy()->startOfMonth();
        $currentMonthEnd = $now->copy()->endOfMonth();
        $previousMonthStart = $now->copy()->subMonth()->startOfMonth();
        $previousMonthEnd = $now->copy()->subMonth()->endOfMonth();
        $startOfYear = $now->copy()->startOfYear();
        $endOfYear = $now->copy()->endOfYear();

        $user_count = User::all()->count();
        $customer_count = Customer::count();
        $montly_revenue = Sale::where('approved_by','>',0)
            ->where('rejected', 0)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('total_amount');
        $order_placed  = Order::where('type_id', 1)->count();
        $full_paid = Order::where('type_id', 1)->where('status', 2)->count();
        $partial_paid = Order::where('type_id', 1)->where('status', 1)->count();
        $pending = Order::where('type_id', 1)->where('status', 0)->count();
        $cancelled = Order::where('type_id', 1)->where('status', 3)->count();
        $approveSales_count = Sale::where('approved_by',0)->where('rejected',0)->count();
        $margin = Product::where('stock_quantity','<', 50)->count();
        $monthly_expenses = Expense::whereBetween('date', [$currentMonthStart->toDateString(), $currentMonthEnd->toDateString()])->sum('amount');
        $total_expenses = $monthly_expenses;
        $withholding = Invoice::whereBetween('created_at', [$startOfYear, $endOfYear])->whereIn ('payment_status',[1,2])->sum('withholding_tax');

        $oldestSaleDate = Sale::where('approved_by', '>', 0)->where('rejected', 0)->min('created_at');
        $oldestExpenseDate = Expense::min('date');
        $oldestChartYear = collect([
            $oldestSaleDate ? Carbon::parse($oldestSaleDate)->year : null,
            $oldestExpenseDate ? Carbon::parse($oldestExpenseDate)->year : null,
            $currentYear,
        ])->filter()->min();
        $availableChartYears = range($currentYear, $oldestChartYear);
        $selectedChartYear = (int) $request->query('chart_year', $currentYear);
        if (! in_array($selectedChartYear, $availableChartYears, true)) {
            $selectedChartYear = $currentYear;
        }

        $chartFromDate = $request->query('chart_from_date');
        $chartToDate = $request->query('chart_to_date');
        $chartRangeMode = $chartFromDate && $chartToDate;
        if ($chartRangeMode) {
            $chartStartDate = Carbon::parse($chartFromDate)->startOfDay();
            $chartEndDate = Carbon::parse($chartToDate)->endOfDay();

            if ($chartEndDate->lt($chartStartDate)) {
                $chartRangeMode = false;
                $chartFromDate = null;
                $chartToDate = null;
            }
        }

        $chartLabels = [];
        $Current_salesData = [];
        $current_expenseData = [];

        if ($chartRangeMode) {
            $useDailyBuckets = $chartStartDate->diffInDays($chartEndDate) <= 31;
            $period = $useDailyBuckets
                ? CarbonPeriod::create($chartStartDate->copy()->startOfDay(), '1 day', $chartEndDate->copy()->startOfDay())
                : CarbonPeriod::create($chartStartDate->copy()->startOfMonth(), '1 month', $chartEndDate->copy()->startOfMonth());

            foreach ($period as $bucketDate) {
                $bucketStart = $useDailyBuckets ? $bucketDate->copy()->startOfDay() : $bucketDate->copy()->startOfMonth();
                $bucketEnd = $useDailyBuckets ? $bucketDate->copy()->endOfDay() : $bucketDate->copy()->endOfMonth();

                if ($bucketStart->lt($chartStartDate)) {
                    $bucketStart = $chartStartDate->copy();
                }

                if ($bucketEnd->gt($chartEndDate)) {
                    $bucketEnd = $chartEndDate->copy();
                }

                $chartLabels[] = $useDailyBuckets ? $bucketDate->format('d M Y') : $bucketDate->format('M Y');
                $Current_salesData[] = Sale::where('approved_by', '>', 0)
                    ->where('rejected', 0)
                    ->whereBetween('created_at', [$bucketStart, $bucketEnd])
                    ->sum('total_amount');
                $current_expenseData[] = Expense::whereBetween('date', [$bucketStart->toDateString(), $bucketEnd->toDateString()])
                    ->sum('amount');
            }
        } else {
            for ($i = 1; $i <= 12; $i++) {
                $chartLabels[] = Carbon::create($selectedChartYear, $i, 1)->format('F');
                $Current_salesData[] = Sale::where('approved_by', '>', 0)
                    ->where('rejected', 0)
                    ->whereYear('created_at', $selectedChartYear)
                    ->whereMonth('created_at', $i)
                    ->sum('total_amount');
                $current_expenseData[] = Expense::whereYear('date', $selectedChartYear)
                    ->whereMonth('date', $i)
                    ->sum('amount');
            }
        }

        $chartSubtitle = $chartRangeMode
            ? 'Sales and expenses overview from ' . $chartStartDate->format('d M Y') . ' to ' . $chartEndDate->format('d M Y') . '.'
            : 'Sales and expenses overview from January to December ' . $selectedChartYear . '.';

        $currentMonthSales = Sale::where('approved_by','>',0)
            ->where('rejected', 0)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('total_amount');
        $previousMonthSales = Sale::where('approved_by','>',0)
            ->where('rejected', 0)
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->sum('total_amount');
        //whereMonth('created_at','=', $currentMonth)->value('amount');

        if ($previousMonthSales > 0 && $currentMonthSales > 0) {
            $percentageIncrease = (($currentMonthSales - $previousMonthSales) / $previousMonthSales) * 100;
        } else {
            // Handle division by zero or no sales in the previous month
            $percentageIncrease = 0;
        }

        // Round the percentage to two decimal places
        $percentageIncrease = round($percentageIncrease, 2);

        return view('home.admin',compact('user_count','customer_count','montly_revenue','order_placed',
                    'full_paid','partial_paid','pending','cancelled','Current_salesData','current_expenseData',
                    'currentYear','approveSales_count','margin','percentageIncrease','monthly_expenses','withholding',
                    'total_expenses', 'availableChartYears', 'selectedChartYear', 'chartFromDate', 'chartToDate',
                    'chartLabels', 'chartSubtitle'));
    }



    public function seller(){

        $rejected = Sale::where('approved_by',0)->where('rejected',1)->count();
        $customer_count = Customer::count();
        $pending = Order::where('type_id', 1)->where('status', 0)->count();
        $full_paid = Order::where('type_id', 1)->where('status', 2)->count();
        $cancelled = Order::where('type_id', 1)->where('status', 3)->count();
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $total_expenses = Expense::whereBetween('date', [$currentMonthStart->toDateString(), $currentMonthEnd->toDateString()])->sum('amount');

        return view('home.seller', compact(
            'rejected',
            'customer_count',
            'pending',
            'full_paid',
            'cancelled',
            'total_expenses'
        ));
    }
}
