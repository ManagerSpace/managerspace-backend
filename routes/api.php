<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ExpenseCategoryController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\IncomeCategoryController;
use App\Http\Controllers\Api\IncomeController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaxController;
use App\Http\Controllers\Api\TimesheetController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->middleware('api');
Route::post('register', [AuthController::class, 'register'])->middleware('api');
Route::middleware(['web'])->group(function () {
    Route::get('login/google', [AuthController::class, 'redirectToGoogle']);
    Route::get('login/google/callback', [AuthController::class, 'handleGoogleCallback']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'generatePdf'])->name('api.invoices.pdf');
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('taxes', TaxController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('incomes', IncomeController::class);
    Route::apiResource('expenses', ExpenseController::class);
    Route::apiResource('income-categories', IncomeCategoryController::class);
    Route::apiResource('expense-categories', ExpenseCategoryController::class);
    Route::apiResource('product-categories', ProductCategoryController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::get('/timesheet/last-status', [TimesheetController::class, 'lastStatus']);
    Route::post('/timesheet/check-in', [TimesheetController::class, 'checkIn']);
    Route::post('/timesheet/check-out', [TimesheetController::class, 'checkOut']);
    Route::get('/timesheet/entries', [TimesheetController::class, 'getEntries']);
    Route::get('/timesheet/month-entries/{year}/{month}', [TimesheetController::class, 'getMonthEntries']);
    Route::get('/timesheet/employees-month-entries/{year}/{month}', [TimesheetController::class, 'getEmployeesMonthEntries']);
    Route::get('/timesheet/employee-day-entries', [TimesheetController::class, 'getEmployeeDayEntries']);
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);
    Route::put('/user/password', [UserController::class, 'updatePassword']);
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/dashboard/chart', [DashboardController::class, 'getChartData']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
