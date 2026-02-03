<?php

use App\Http\Controllers\Api\UserRoleController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\API\AuthController;

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Protected routes - require authentication
Route::middleware('auth:sanctum')->group(function () {

    // User Role Management Routes
    Route::prefix('users')->group(function () {
        Route::post('{userId}/assign-role', [UserRoleController::class, 'assignRole']);
        Route::post('{userId}/remove-role', [UserRoleController::class, 'removeRole']);
        Route::get('{userId}/roles', [UserRoleController::class, 'getUserRoles']);
        Route::get('{userId}/check-role/{role}', [UserRoleController::class, 'checkUserRole']);
        Route::get('{userId}/check-permission/{permission}', [UserRoleController::class, 'checkUserPermission']);
    });

    // Role Management Routes (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::post('roles/{id}/assign-permissions', [RoleController::class, 'assignPermissions']);
        Route::post('roles/{id}/remove-permissions', [RoleController::class, 'removePermissions']);
    });

    // Permission Management Routes (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('permissions', PermissionController::class);
    });
});