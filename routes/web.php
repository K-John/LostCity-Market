<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Auth\AccountController;
use App\Http\Controllers\Auth\DiscordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BanController;
use App\Http\Controllers\BumpController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ListingSaleController;
use App\Http\Controllers\PauseController;
use App\Http\Controllers\UnpauseController;
use App\Http\Controllers\UsernameController;
use App\Http\Middleware\AuthorizeAdmin;
use App\Http\Middleware\LocalOnly;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('listings', ListingController::class)
        ->names('listings');

    Route::get('listings/{listing}/delete', [ListingController::class, 'delete'])
        ->name('listings.delete');

    Route::prefix('listing/{listing}')->group(function () {
        Route::get('sell', [ListingSaleController::class, 'create'])->name('listing.sale.create');
        Route::post('sell', [ListingSaleController::class, 'store'])->name('listing.sale.store');

        Route::post('pause', [PauseController::class, 'store'])->name('listing.pause.store');
        Route::delete('unpause', [UnpauseController::class, 'destroy'])->name('listing.pause.destroy');
    });

    Route::post('listings/pause', [PauseController::class, 'index'])
        ->name('listings.pause.store');

    Route::post('listings/unpause', [UnpauseController::class, 'index'])
        ->name('listings.pause.destroy');

    Route::patch('bump', [BumpController::class, 'index'])
        ->name('listings.bump');

    Route::patch('/bump/{listing}', [BumpController::class, 'update'])
        ->name('listing.bump');

    Route::patch('usernames/update', [UsernameController::class, 'update'])
        ->name('usernames.update');

    Route::resource('favorites', FavoriteController::class)
        ->only(['index', 'store', 'destroy'])
        ->names('favorites');

    Route::get('account', AccountController::class)->name('account');

    Route::middleware(AuthorizeAdmin::class)->group(function () {
        Route::post('/ban/{username:username}', [BanController::class, 'store'])->name('ban.store');
        Route::delete('/ban/{username:username}', [BanController::class, 'destroy'])->name('ban.destroy');

        Route::resource('admin/banners', BannerController::class)
            ->names('admin.banners');
    });
});

Route::get('items/{item:slug}', [ItemController::class, 'show'])
    ->name('items.show');

Route::get('users/{username:username}', [UsernameController::class, 'show'])
    ->name('usernames.show');

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

Route::get('/dev/login', function () {
    $user = User::where('name', 'root')->first();
    Auth::login($user);

    return redirect(route('home'));
})->name('dev.login')->middleware(LocalOnly::class);
