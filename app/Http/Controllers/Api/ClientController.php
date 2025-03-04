<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            $clients = Client::where('user_id', $user->id)->get();
        } else {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return ClientResource::collection($clients);
    }

    public function store(StoreClientRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = Auth::id();
        $client = Client::create($validatedData);
        return new ClientResource($client);
    }

    public function show(Client $client)
    {
        $this->authorize('view', $client);
        return new ClientResource($client);
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $this->authorize('update', $client);
        $validatedData = $request->validated();
        $client->update($validatedData);
        return new ClientResource($client);
    }

    public function destroy(Client $client)
    {
        $user = Auth::user();
        if ($user->cannot('delete', $client)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $client->delete();
        return response()->json(null, 204);
    }
}
