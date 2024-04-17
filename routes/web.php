<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/buy-credits/webhook', [\App\Http\Controllers\CreditController::class, 'webhook'])->name('credit.webhook');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/feature-one', [\App\Http\Controllers\FeatureOneController::class, 'index'])->name('feature-one.index');
    Route::post('/feature-one-sumbit', [\App\Http\Controllers\FeatureOneController::class, 'calculator'])->name('feature-one.calculator');


    Route::get('/buy-credits', [\App\Http\Controllers\CreditController::class, 'index'])->name('credit.index');
    Route::get('/buy-credits/success', [\App\Http\Controllers\CreditController::class, 'success'])->name('credit.success');
    Route::get('/buy-credits/cancel', [\App\Http\Controllers\CreditController::class, 'cancel'])->name('credit.cancel');
    Route::post('/buy-credits/{package}', [\App\Http\Controllers\CreditController::class, 'buyCredits'])->name('credit.buy');

});

require __DIR__.'/auth.php';
