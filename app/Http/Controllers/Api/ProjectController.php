<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\ProjectResource;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $companyId = $request->input('company_id');
        if (!$companyId) {
            return response()->json(['error' => 'Company ID is required'], 400);
        }

        $query = Project::where('company_id', $companyId)
            ->whereHas('company', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['company', 'client']);

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('client')) {
            $query->where('client_id', $request->input('client'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('start_date')) {
            $query->where('start_date', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $query->where('end_date', '<=', $request->input('end_date'));
        }

        if ($request->has('budget_min')) {
            $query->where('budget', '>=', $request->input('budget_min'));
        }

        if ($request->has('budget_max')) {
            $query->where('budget', '<=', $request->input('budget_max'));
        }

        $perPage = $request->input('per_page', 10);
        $projects = $query->latest()->paginate($perPage);

        return ProjectResource::collection($projects);
    }

    public function store(StoreProjectRequest $request)
    {
        $validatedData = $request->validated();
        $project = Project::create($validatedData);
        return new ProjectResource($project);
    }

    public function show(Project $project)
    {
        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validatedData = $request->validated();
        $project->update($validatedData);
        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(null, 204);
    }
}
