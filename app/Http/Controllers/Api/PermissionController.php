<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        if (empty($permissions)) {
            return response()->json(["message" => "No permissions were found"], 204);
        }

        return response()->json(["permissions" => $permissions]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|unique:permissions,name']);
        $perm = Permission::create($validated);
        return response()->json(['message' => 'permission created successfully', 'permission' => $perm], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $perm = Permission::find($id);

        if (!$perm) {
            return response()->json(['message' => 'permission not found'], 404);
        }

        return response()->json(['permission' => $perm], 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $perm = Permission::find($id);

        if (!$perm) {
            return response()->json(['message' => 'permission not found'], 404);
        }

        $fileds = $request->validate([
            'name' => "required|string|unique:premissions,name",
            'guard_name' => "sometimes|string",
        ]);

        $perm->update($fileds);

        return response()->json(['message' => 'permission updated successfully', 'permission' => $perm]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $perm = Permission::find($id);

        if (!$perm) {
            return response()->json(['message' => 'role not found'], 404);
        }

        $perm->delete();

        return response()->json(['message' => 'premissions deleted successfully']);

    }
}
