<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Client;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getStats(Request $request)
    {
        $company_id = $request->input('company_id');
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // Obtener el user_id asociado a la compañía
        $company = Company::findOrFail($company_id);
        $user_id = $company->user_id;

        $totalInvoices = Invoice::where('company_id', $company_id)->sum('total');
        $lastMonthInvoices = Invoice::where('company_id', $company_id)
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('total');

        $invoicesChange = $lastMonthInvoices > 0
            ? (($totalInvoices - $lastMonthInvoices) / $lastMonthInvoices) * 100
            : 100;

        // Filtrar clientes por user_id
        $totalClients = Client::where('user_id', $user_id)->count();
        $lastMonthClients = Client::where('user_id', $user_id)
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        $clientsChange = $lastMonthClients > 0
            ? (($totalClients - $lastMonthClients) / $lastMonthClients) * 100
            : 100;

        $totalExpenses = Expense::where('id_company', $company_id)->sum('amount');
        $lastMonthExpenses = Expense::where('id_company', $company_id)
            ->whereMonth('date', $lastMonth->month)
            ->whereYear('date', $lastMonth->year)
            ->sum('amount');

        $expensesChange = $lastMonthExpenses > 0
            ? (($totalExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100
            : 100;

        $performance = $totalInvoices > 0
            ? (($totalInvoices - $totalExpenses) / $totalInvoices) * 100
            : 0;

        $lastMonthPerformance = $lastMonthInvoices > 0
            ? (($lastMonthInvoices - $lastMonthExpenses) / $lastMonthInvoices) * 100
            : 0;

        $performanceChange = $lastMonthPerformance > 0
            ? $performance - $lastMonthPerformance
            : $performance;

        return response()->json([
            'metrics' => [
                [
                    'title' => 'Total Facturado',
                    'value' => number_format($totalInvoices, 2),
                    'change' => round($invoicesChange, 2),
                    'color' => 'red'
                ],
                [
                    'title' => 'Total Clientes',
                    'value' => $totalClients,
                    'change' => round($clientsChange, 2),
                    'color' => 'orange'
                ],
                [
                    'title' => 'Total Gastos',
                    'value' => number_format($totalExpenses, 2),
                    'change' => round($expensesChange, 2),
                    'color' => 'green'
                ],
                [
                    'title' => 'Rendimiento',
                    'value' => round($performance, 2) . '%',
                    'change' => round($performanceChange, 2),
                    'color' => 'blue'
                ]
            ]
        ]);
    }

    public function getChartData(Request $request)
    {
        $company_id = $request->input('company_id');
        $period = $request->get('period', 'month');
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);

        if ($period === 'month') {
            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = $startDate->copy()->endOfMonth()->endOfDay();

            $incomes = Income::select(
                DB::raw('DATE(date) as date'),
                DB::raw('SUM(amount) as total')
            )
                ->where('id_company', $company_id)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->groupBy(DB::raw('DATE(date)'))
                ->get()
                ->keyBy('date');

            $expenses = Expense::select(
                DB::raw('DATE(date) as date'),
                DB::raw('SUM(amount) as total')
            )
                ->where('id_company', $company_id)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->groupBy(DB::raw('DATE(date)'))
                ->get()
                ->keyBy('date');

            $totalIncome = 0;
            $totalExpense = 0;
            $chartData = collect();
            for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                $dateStr = $date->format('Y-m-d');
                $dateTimeStr = $date->format('Y-m-d 00:00:00');

                $income = isset($incomes[$dateTimeStr]) ? $incomes[$dateTimeStr]->total : 0;
                $expense = isset($expenses[$dateTimeStr]) ? $expenses[$dateTimeStr]->total : 0;

                $totalIncome += $income;
                $totalExpense += $expense;

                $chartData->push([
                    'date' => $dateStr,
                    'income' => $income,
                    'expense' => $expense
                ]);
            }

            return response()->json([
                'chartData' => $chartData,
                'summary' => [
                    'totalIncome' => $totalIncome,
                    'totalExpense' => $totalExpense,
                ]
            ]);

        } else {
            $incomes = Income::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(amount) as total')
            )
                ->where('id_company', $company_id)
                ->whereYear('date', $year)
                ->groupBy(DB::raw('MONTH(date)'))
                ->get()
                ->keyBy('month');

            $expenses = Expense::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(amount) as total')
            )
                ->where('id_company', $company_id)
                ->whereYear('date', $year)
                ->groupBy(DB::raw('MONTH(date)'))
                ->get()
                ->keyBy('month');

            $months = collect(range(1, 12))->map(function($month) use ($year, $incomes, $expenses) {
                $date = Carbon::create($year, $month, 1)->format('Y-m');
                return [
                    'date' => $date,
                    'income' => $incomes->get($month) ? floatval($incomes->get($month)->total) : 0,
                    'expense' => $expenses->get($month) ? floatval($expenses->get($month)->total) : 0
                ];
            });

            return response()->json([
                'dates' => $months,
                'incomes' => $incomes->map(function($item) {
                    return $item->total;
                })->toArray(),
                'expenses' => $expenses->map(function($item) {
                    return $item->total;
                })->toArray()
            ]);
        }
    }
}
