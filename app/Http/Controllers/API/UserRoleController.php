<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    /**
     * Assign a role to a user
     */
    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::findOrFail($userId);
        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => "Role '{$request->role}' assigned to user successfully!",
            'user' => $user->load('roles')
        ]);
    }

    /**
     * Remove a role from a user
     */
    public function removeRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::findOrFail($userId);
        $user->removeRole($request->role);

        return response()->json([
            'success' => true,
            'message' => "Role '{$request->role}' removed from user successfully!",
            'user' => $user->load('roles')
        ]);
    }

    /**
     * Get all roles of a user
     */
    public function getUserRoles($userId)
    {
        $user = User::findOrFail($userId);
        
        return response()->json([
            'success' => true,
            'user' => $user->name,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name')
        ]);
    }

    /**
     * Check if user has a specific role
     */public function checkUserRole($userId, $role): View
{
    $user = User::findOrFail($userId);
    $hasRole = $user->hasRole($role);

    return view('dashboard', [
        'role' => $role,
        'hasRole' => $hasRole,
        'user' => $user
    ]);
}

    /**
     * Check if user has a specific permission
     */
    public function checkUserPermission($userId, $permission)
    {
        $user = User::findOrFail($userId);
        $hasPermission = $user->hasPermissionTo($permission);

        return response()->json([
            'success' => true,
            'user' => $user->name,
            'permission' => $permission,
            'has_permission' => $hasPermission
        ]);
    }
}