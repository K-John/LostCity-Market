<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UsernameController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('items/{item:slug}', [ItemController::class, 'show'])
    ->name('items.show');

Route::resource('listings', ListingController::class)
    ->names('listings');

Route::patch('listings/{listing}/bump', [ListingController::class, 'bump'])
    ->name('listings.bump');

Route::post('token', [TokenController::class, 'store'])
    ->name('tokens.store');

Route::get('token', [TokenController::class, 'download'])
    ->name('tokens.download');

Route::get('token/{listing}', [TokenController::class, 'show'])
    ->name('tokens.show');

Route::get('users/{username}', UsernameController::class)
    ->name('users');
