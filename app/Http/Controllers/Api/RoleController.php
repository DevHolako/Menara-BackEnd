<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::whereNotIn('name', ['Owner'])->get();
        if (empty($roles)) {
            return response()->json(["message" => "No roles were found"], 204);
        }
        return response()->json(["roles" => $roles], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string']);
        $role = Role::create($validated);
        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'role not found'], 404);
        }

        return response()->json(['role' => $role], 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $fileds = $request->validate([
            'name' => "required|string|unique:roles,name",
            'guard_name' => "sometimes|string",
        ]);

        $role->update($fileds);

        return response()->json(['message' => 'Role updated successfully', 'role' => $role]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'role not found'], 404);
        }

        $role->delete();

        return response()->json(['message' => 'role deleted successfully']);

    }

    public function GivePermission(Request $request, string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $validated = $request->validate(['name' => 'string|required|exists:permissions,name']);

        $check = $role->hasPermissionTo($validated['name']);

        return $check;

        //     if ($role->hasPermissionTo($validated['name'], "api")) {
        //         return response()->json(['message' => 'Permission has already been granted in that role'], 403);
        //     }

        //     $role->givePermissionTo($validated['name']);
        //
    }
}
