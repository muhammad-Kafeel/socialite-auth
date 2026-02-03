<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of all permissions
     */
    public function index()
    {
        $permissions = Permission::all();

        return response()->json([
            'success' => true,
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created permission
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name|max:255'
        ]);

        $permission = Permission::create(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully!',
            'permission' => $permission
        ], 201);
    }

    /**
     * Display the specified permission
     */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);

        return response()->json([
            'success' => true,
            'permission' => $permission
        ]);
    }

    /**
     * Update the specified permission
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id
        ]);

        $permission->update(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully!',
            'permission' => $permission
        ]);
    }

    /**
     * Remove the specified permission
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permissionName = $permission->name;
        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => "Permission '{$permissionName}' deleted successfully!"
        ]);
    }
}