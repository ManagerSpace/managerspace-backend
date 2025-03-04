<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Expense;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCompanies = Company::count();
        $totalClients = Client::count();
        $totalInvoices = Invoice::count();
        $totalProducts = Product::count();

        $totalRevenue = Invoice::sum('total');
        $totalExpenses = Expense::sum('amount');
        $netIncome = $totalRevenue - $totalExpenses;

        $recentInvoices = Invoice::with('client')->latest()->take(5)->get();

        $topClients = Client::withCount('invoices')
            ->withSum('invoices', 'total')
            ->orderByDesc('invoices_sum_total')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalCompanies',
            'totalClients',
            'totalInvoices',
            'totalProducts',
            'totalRevenue',
            'totalExpenses',
            'netIncome',
            'recentInvoices',
            'topClients'
        ));
    }
}
