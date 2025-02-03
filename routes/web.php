<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('items/{item:slug}', [ItemController::class, 'show'])
    ->name('items.show');

Route::resource('listings', ListingController::class)
    ->names('listings');
