<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Http\Resources\IncomeResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $companyId = $request->input('company_id');
        $query = Income::where('id_company', $companyId)
            ->whereHas('company', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('category', 'company');

        if ($request->has('description')) {
            $query->where('description', 'like', '%' . $request->input('description') . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->has('date_from')) {
            $query->where('date', '>=', $request->input('date_from'));
        }

        if ($request->has('date_to')) {
            $query->where('date', '<=', $request->input('date_to'));
        }

        if ($request->has('amount_min')) {
            $query->where('amount', '>=', $request->input('amount_min'));
        }

        if ($request->has('amount_max')) {
            $query->where('amount', '<=', $request->input('amount_max'));
        }

        $perPage = $request->input('per_page', 10);
        $incomes = $query->latest()->paginate($perPage);

        return IncomeResource::collection($incomes);
    }

    public function store(StoreIncomeRequest $request)
    {
        $income = Income::create($request->validated());
        return new IncomeResource($income);
    }

    public function show(Income $income)
    {
        return new IncomeResource($income);
    }

    public function update(UpdateIncomeRequest $request, Income $income)
    {
        $data = $request->validated();
        $income->update($data);
        return new IncomeResource($income);
    }

    public function destroy(Income $income)
    {
        $income->delete();
        return response()->json(null, 204);
    }
}
