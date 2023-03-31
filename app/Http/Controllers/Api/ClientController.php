<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view clinets')->only('index');
        $this->middleware('permission:view client')->only('show');
        $this->middleware('permission:store client')->only('store');
        $this->middleware('permission:update client')->only('update');
        $this->middleware('permission:soft-delete client')->only('destory');
        $this->middleware('permission:restore client')->only('restore');
        $this->middleware('permission:force-delete client')->only('forceDelete');
    }

    // Display a listing of the resource.
    public function index()
    {
        $clients = Client::all();
        if (!$clients) {
            return response()->json(["message" => "No clients were found"], 404);
        };
        return response()->json(['clients' => $clients]);
    }

    // Store a newly created resource in storage.
    public function store(Request $req)
    {

        $fileds = $req->vlaidat([
            "raison_social" => "required|numeric|unique:clients,raison_social",
            "ice" => "required|numeric|unique:clients,ice",
            "rc" => "required|numeric|unique:clients,rc",
            "type" => "required|string",
        ]);

        $client = Client::create($fileds);
        return response()->json(['message' => 'client created successfully', 'client' => $client]);
    }

    // Display the specified resource.
    public function show(string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'client not found'], 404);
        }

        return response()->json(['client' => $client]);

    }

    // Update the specified resource in storage.
    public function update(Request $req, string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'client not found'], 404);
        }

        $fileds = $req->validate([
            "raison_social" => "sometimes|numeric|unique:clients,raison_social",
            "ice" => "sometimes|numeric|unique:clients,ice",
            "rc" => "sometimes|numeric|unique:clients,rc",
            "type" => "sometimes|string",
        ]);

        $client->update($fileds);

        return response()->json(['message' => 'client updated successfully', 'client' => $client]);

    }

    //  Soft Delete the specified resource from storage.
    public function destroy(string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'client not found'], 404);
        }

        $client->delete();

        return response()->json(['message' => 'client soft-deleted successfully'], 201);
    }

    // Restoure the specified resource from storage.
    public function restore(string $id)
    {
        $client = Client::onlyTrashed()->find($id);
        if (!$client) {
            return response()->json(['message' => 'client not found'], 404);
        }

        $client->restore();
        return response()->json(['message' => 'client restored successfully'], 201);

    }

    // Remove premently the specified resource from storage.
    public function forceDelete(string $id)
    {
        $client = Client::onlyTrashed()->find($id);
        if (!$client) {
            return response()->json(['message' => 'client not found'], 404);
        };

        $client->forceDelete();
        return response()->json(['message' => 'client premently deleted successfully'], 201);

    }

}
