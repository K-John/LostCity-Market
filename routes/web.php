<?php

use App\Http\Controllers\Auth\DiscordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BanController;
use App\Http\Controllers\BumpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\UsernameController;
use App\Http\Middleware\AuthorizeAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('listings', ListingController::class)
        ->names('listings');

    Route::patch('bump', [BumpController::class, 'index'])
        ->name('listings.bump');

    Route::patch('/bump/{listing}', [BumpController::class, 'update'])
        ->name('listing.bump');

    Route::middleware(AuthorizeAdmin::class)->group(function () {
        Route::post('/ban/{username:username}', [BanController::class, 'store'])->name('ban.store');
        Route::delete('/ban/{username:username}', [BanController::class, 'destroy'])->name('ban.destroy');
    });
});

Route::get('items/{item:slug}', [ItemController::class, 'show'])
    ->name('items.show');

Route::resource('users', UsernameController::class)
    ->only(['show', 'update'])
    ->names('usernames');

Route::get('/auth/discord', [DiscordController::class, 'redirectToDiscord'])
    ->name('auth.discord');

Route::get('/auth/discord/callback', [DiscordController::class, 'handleDiscordCallback'])
    ->name('auth.discord.callback');

Route::get('login', [LoginController::class, 'index'])
    ->name('login');

Route::delete('logout', [LoginController::class, 'destroy'])
    ->name('logout');

Route::get('docs/adopt-legacy-accounts', function () {
    return inertia('docs/adopt/page');
})->name('docs.adopt');

Route::get('news/discord-enforcement', function () {
    return inertia('news/discord-enforce/page');
})->name('news.discord-enforce');
