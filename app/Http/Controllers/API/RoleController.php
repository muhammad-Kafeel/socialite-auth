<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of all roles
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();

        return response()->json([
            'success' => true,
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->givePermissionTo($request->permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully!',
            'role' => $role->load('permissions')
        ], 201);
    }

    /**
     * Display the specified role
     */
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return response()->json([
            'success' => true,
            'role' => $role
        ]);
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully!',
            'role' => $role->load('permissions')
        ]);
    }

    /**
     * Remove the specified role
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $roleName = $role->name;
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => "Role '{$roleName}' deleted successfully!"
        ]);
    }

    /**
     * Assign permissions to a role
     */
    public function assignPermissions(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role = Role::findOrFail($id);
        $role->givePermissionTo($request->permissions);

        return response()->json([
            'success' => true,
            'message' => 'Permissions assigned to role successfully!',
            'role' => $role->load('permissions')
        ]);
    }

    /**
     * Remove permissions from a role
     */
    public function removePermissions(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role = Role::findOrFail($id);
        $role->revokePermissionTo($request->permissions);

        return response()->json([
            'success' => true,
            'message' => 'Permissions removed from role successfully!',
            'role' => $role->load('permissions')
        ]);
    }
}