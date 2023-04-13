<?php

namespace App\Http\Controllers\Api\RolesAndPermissions;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Permission\PermissionCollection;
use App\Http\Resources\Api\Permission\PermissionResource;
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
            return response(["message" => "No permissions were found"], 204);
        }

        return new PermissionCollection($permissions);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate(['name' => 'required|string|unique:permissions,name']);

        // Define the permission based on a lot of permissions
        $permissions = [
            "view users",
            "view user",
            "store user",
            "update user",
        ];

        $permTest = Permission::create(['name' => 'UserMangment']);
        $permTest->syncPermissions($permissions);

        $perm = Permission::create($validated);

        return response(['message' => 'permission created successfully', 'permission' => new PermissionResource($perm)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $perm = Permission::find($id);

        if (!$perm) {
            return response(['message' => 'permission not found'], 404);
        }

        return new PermissionResource($perm);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $perm = Permission::find($id);

        if (!$perm) {
            return response(['message' => 'permission not found'], 404);
        }

        $fileds = $request->validate([
            'name' => "required|string|unique:premissions,name",
            'guard_name' => "sometimes|string",
        ]);

        $perm->update($fileds);
        return response(['message' => 'permission updated successfully', 'permission' => new PermissionResource($perm)]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $perm = Permission::find($id);

        if (!$perm) {
            return response(['message' => 'role not found'], 404);
        }

        $perm->delete();

        return response(['message' => 'premissions deleted successfully']);

    }
}
