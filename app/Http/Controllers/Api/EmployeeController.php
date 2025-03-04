<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyId = $request->input('company_id');

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if (!$companyId) {
            return response()->json(['error' => 'Empty company_id'], 401);
        }

        $query = Employee::with(['position', 'user'])
            ->where('company_id', $companyId)
            ->whereHas('company', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });

        if ($request->has('name')) {
            $query->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->input('name') . '%')
                    ->orWhere('last_name', 'like', '%' . $request->input('name') . '%');
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('hire_date_from')) {
            $query->where('hire_date', '>=', $request->input('hire_date_from'));
        }

        if ($request->has('hire_date_to')) {
            $query->where('hire_date', '<=', $request->input('hire_date_to'));
        }

        $perPage = $request->input('per_page', 10);
        $employees = $query->latest()->paginate($perPage);

        return EmployeeResource::collection($employees);
    }

    public function store(StoreEmployeeRequest $request)
    {
        $user = Auth::user();

        $companyId = $request->input('company_id');
        $company = Company::where('id', $companyId)
            ->where('user_id', $user->id)
            ->first();

        if (!$company) {
            return response()->json(['error' => 'The company does not belong to the authenticated user.'], 403);
        }

        DB::beginTransaction();

        try {
            $newUser = User::create([
                'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role' => 'employee',
            ]);

            $employeeData = $request->validated();
            $employeeData['user_id'] = $newUser->id;

            $employee = Employee::create($employeeData);

            DB::commit();
            return new EmployeeResource($employee->load('user'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create employee and user.'], 500);
        }
    }

    public function show(Employee $employee)
    {
        return new EmployeeResource($employee->load('user', 'position'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $user = Auth::user();
        $companyId = $request->input('company_id');
        $company = Company::where('id', $companyId)
            ->where('user_id', $user->id)
            ->first();

        if (!$company) {
            return response()->json(['error' => 'The company does not belong to the authenticated user.'], 403);
        }

        if ($employee->company_id !== $companyId) {
            return response()->json(['error' => 'You cannot update the company for this employee.'], 403);
        }

        DB::beginTransaction();

        try {
            $data = $request->validated();
            $employee->update($data);

            $employeeUser = $employee->user;
            $employeeUser->update([
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'email' => $request->input('email'),
            ]);

            if ($request->has('password')) {
                $employeeUser->update([
                    'password' => Hash::make($request->input('password')),
                ]);
            }

            DB::commit();
            return new EmployeeResource($employee->load('user'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update employee and user.'], 500);
        }
    }

    public function destroy(Employee $employee)
    {
        DB::beginTransaction();

        try {
            $user = $employee->user;
            $employee->delete();
            $user->delete();

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete employee and user.'], 500);
        }
    }
}
