<?php

use App\Http\Controllers\TikTokAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('/auth/tiktok/redirect', [TikTokAuthController::class, 'redirect']);
    Route::get('/auth/tiktok/callback', [TikTokAuthController::class, 'callback']);
    Route::get('/tiktok/user', [TikTokAuthController::class, 'getUserInfo']);

    Route::get('/accounts/tiktok', function () {
        $user = Auth::user();

        $stats = [
            'connected' => $user && $user->tiktok_token ? 1 : 0,
            'pending' => 0,
            'total' => 1,
        ];

        $accounts = [
            [
                'name' => $user ? $user->name : 'Account',
                'connected' => (bool) ($user && $user->tiktok_token),
                'type' => 'Page',
                'followers' => 0,
                'engagement' => 0,
                'last_activity' => '10 hours ago',
                'avatar' => null,
            ],
        ];

        return Inertia::render('accounts/tiktok', [
            'stats' => $stats,
            'accounts' => $accounts,
        ]);
    })->name('accounts.tiktok');
});



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
