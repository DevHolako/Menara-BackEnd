<?php

namespace App\Http\Controllers\Api\eCommerce;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view client')->only('index', 'show');
        $this->middleware('permission:store client')->only('index', 'show', 'store');
        $this->middleware('permission:update client')->only('index', 'show', 'update');
        $this->middleware('permission:delete client')->only('index', 'show', 'destory', 'restore', 'forceDelete');
    }

    // Display a listing of the resource.
    public function index()
    {
        $clients = Client::all();
        if ($clients->isEmpty()) {
            return response()->json(["message" => "No clients were found"], 204);
        };
        return response()->json($clients);
    }

    // Store a newly created resource in storage.
    public function store(Request $req)
    {

        $fileds = $req->validate([
            "raison_social" => "required|string|unique:clients,raison_social",
            "ice" => "required|numeric|unique:clients,ice",
            "rc" => "required|numeric|unique:clients,rc",
            "type" => "required|string",
            "categorie" => "sometimes|string",

        ]);
        $client = Client::create($fileds);
        return response()->json(['message' => 'client created successfully', 'client' => $client]);
    }

    // Display the specified resource.
    public function show(string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'client not found'], 204);
        }

        return response()->json($client);
    }

    // Update the specified resource in storage.
    public function update(Request $req, string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'client not found'], 204);
        }

        $fileds = $req->validate([
            "raison_social" => "sometimes|string|unique:clients,raison_social," . $client->id,
            "ice" => "sometimes|numeric|unique:clients,ice," . $client->id,
            "rc" => "sometimes|numeric|unique:clients,rc," . $client->id,
            "type" => "sometimes|string",
            "categorie" => "sometimes|string",

        ]);

        $client->update($fileds);

        return response()->json(['message' => 'client updated successfully', 'client' => $client]);
    }

    //  Soft Delete the specified resource from storage.
    public function destroy(string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'client not found'], 204);
        }

        $client->delete();

        return response()->json(['message' => 'client soft-deleted successfully'], 201);
    }

    // Restoure the specified resource from storage.
    public function restore(string $id)
    {
        $client = Client::onlyTrashed()->find($id);
        if (!$client) {
            return response()->json(['message' => 'client not found'], 204);
        }

        $client->restore();
        return response()->json(['message' => 'client restored successfully'], 201);
    }

    // Remove premently the specified resource from storage.
    public function forceDelete(string $id)
    {
        $client = Client::onlyTrashed()->find($id);
        if (!$client) {
            return response()->json(['message' => 'client not found'], 204);
        };

        $client->forceDelete();
        return response()->json(['message' => 'client premently deleted successfully'], 201);
    }
}
