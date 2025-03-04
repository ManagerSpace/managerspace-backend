<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaxRequest;
use App\Http\Requests\UpdateTaxRequest;
use App\Http\Resources\TaxResource;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaxController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            $taxes = Tax::all();
        } else {
            $taxes = Tax::whereHas('invoiceItems.invoice.company', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        }
        return TaxResource::collection($taxes);
    }

    public function store(StoreTaxRequest $request)
    {
        $this->authorize('create', Tax::class);
        $validatedData = $request->validated();
        $tax = Tax::create($validatedData);
        return new TaxResource($tax);
    }

    public function show(Tax $tax)
    {
        $this->authorize('view', $tax);
        return new TaxResource($tax);
    }

    public function update(UpdateTaxRequest $request, Tax $tax)
    {
        $this->authorize('update', $tax);
        $validatedData = $request->validated();
        $tax->update($validatedData);
        return new TaxResource($tax);
    }

    public function destroy(Tax $tax)
    {
        $this->authorize('delete', $tax);
        $tax->delete();
        return response()->json(null, 204);
    }
}
