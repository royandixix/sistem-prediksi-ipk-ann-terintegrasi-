<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    $role = Auth::user()->role;

    if ($role instanceof \BackedEnum) {
        $role = $role->value;
    }

    return match (strtolower((string) $role)) {
        'admin' => redirect()->route('admin.dashboard'),
        'operator' => redirect()->route('operator.dashboard'),
        default => tap(redirect()->route('login'), function () use ($request) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }),
    };
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

require __DIR__.'/admin.php';
require __DIR__.'/operator.php';
