<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;

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


    public function admin(){

        $currentMonth = Carbon::now()->month;
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        $user_count = User::all()->count();
        $customer_count = Customer::count();
        $montly_revenue = Sale::whereMonth('created_at', $currentMonth)->where('approved_by','>',0)->sum('total_amount');
        $order_placed  = Order::count();
        $full_paid = Order::where('status', 2)->count();
        $partial_paid = Order::where('status', 1)->count();
        $pending = Order::where('status', 0)->count();
        $cancelled = Order::where('status', 3)->count();
        $approveSales_count = Sale::where('approved_by',0)->where('rejected',0)->count();
        $margin = Product::where('stock_quantity','<', 50)->count();
        $total_expenses = Expense::sum('amount');
        $withholding = Invoice::whereBetween('created_at', [$startOfYear, $endOfYear])->whereIn ('payment_status',[1,2])->sum('withholding_tax');

        //Getting salles data for sales chat per year

        $Current_salesData = [];
        $current_expenseData =[];
        $currentYear = date('Y');
        //$previousYear = date('Y') - 1;

/*
        //for sales
        for($i=1; $i<=12; $i++){
            $data = Sale::where('approved_by',1)->whereYear('created_at',$currentYear);
            $out = $data->whereMonth('created_at', $i)->sum('total_amount');
            array_push($Current_salesData,$out);
        }

         //for expenses
        for($i=1; $i<=12; $i++){
            $expens_data = Expense::whereYear('created_at',$currentYear);
            $expense_out = $expens_data->whereMonth('created_at', $i)->
            sum('amount');
            array_push($current_expenseData,$expense_out);
        }
*/

        for ($i = 1; $i <= 12; $i++) {
            // Sales data
            $monthlySales = Sale::where('approved_by', 1)
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->sum('total_amount');
            $Current_salesData[] = $monthlySales;
        
            // Expense data
            $monthlyExpenses = Expense::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->sum('amount');
            $current_expenseData[] = $monthlyExpenses;
        }
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthSales = Sale::whereBetween('created_at', [$currentMonth, $currentMonth->copy()->endOfMonth()])->sum('total_amount');
        $previousMonthSales = Sale::whereBetween('created_at', [$previousMonth, $previousMonth->copy()->endOfMonth()])->sum('total_amount');
        $monthly_expenses = Expense::whereBetween('date', [$currentMonth, $currentMonth->copy()->endOfMonth()])->sum('amount');
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
                    'total_expenses'));
    }



    public function seller(){

        $rejected = Sale::where('approved_by',0)->where('rejected',1)->count();
        $customer_count = Customer::count();
        $pending = Order::where('status', 0)->count();
        $full_paid = Order::where('status', 2)->count();
        $cancelled = Order::where('status', 3)->count();
        $total_expenses = Expense::sum('amount');

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
