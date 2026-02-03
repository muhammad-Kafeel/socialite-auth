<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login'); // redirect after logout
})->name('logout');

Route::middleware('auth')->get('/dashboard', function () {
    $user = Auth::user();           // get logged-in user
    $role = 'admin';                // the role you want to check
    $hasRole = $user->hasRole($role); // check if user has this role

    // Pass variables to Blade
    return view('dashboard', [
        'user' => $user,
        'role' => $role,
        'hasRole' => $hasRole,
    ]);
})->name('dashboard');

// Google OAuth Routes
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);



// Login page route (optional - for showing login form)
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::get('/register', function () {
    return view('register');
})->name('register');