<?php

namespace App\Http\Controllers\Api\RolesAndPermissions;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Role\RoleCollection;
use App\Http\Resources\Api\Role\RoleResource;
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
            return response(["message" => "No roles were found"], 204);
        }
        return new RoleCollection($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string']);
        $role = Role::create($validated);
        return response(['message' => 'Role created successfully', 'role' => new RoleResource($role)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response(['message' => 'role not found'], 404);
        }

        return new RoleResource($role);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response(['message' => 'Role not found'], 404);
        }

        $fileds = $request->validate([
            'name' => "required|string|unique:roles,name",
            'guard_name' => "sometimes|string",
        ]);

        $role->update($fileds);

        return response(['message' => 'Role updated successfully', 'role' => new RoleResource($role)]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response(['message' => 'role not found'], 404);
        }

        $role->delete();

        return response(['message' => 'role deleted successfully']);

    }

    public function GivePermission(Request $request, string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response(['message' => 'Role not found'], 404);
        }
        $validated = $request->validate(['name' => 'string|required|exists:permissions,name']);

        if ($role->hasPermissionTo($validated['name'])) {
            return response(['message' => 'Permission has already been granted in that role'], 403);
        }

        $role->givePermissionTo($validated['name']);
        return response(['message' => 'Permission has been granted'], 200);

    }
    public function RevokePermission(Request $request, string $id)
    {

        $validated = $request->validate(['name' => 'string|required|exists:permissions,name']);

        $role = Role::find($id);

        if (!$role) {
            return response(['message' => 'Role not found'], 404);
        }

        if (!$role->hasPermissionTo($validated['name'])) {
            return response(['message' => 'Permission are not granted to this role'], 403);
        }
        $role->revokePermissionTo($validated['name']);
        return response(['message' => 'Permission has been reovked'], 200);

    }
}
