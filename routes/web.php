<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn () => redirect()->route(auth()->check() ? 'dashboard' : 'login'));

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('dashboard', 'pages.dashboard')->name('dashboard');

    Volt::route('feeds', 'pages.feeds.index')->name('feeds');
    Volt::route('feeds/add', 'pages.feeds.add')->name('feeds.add');
    Volt::route('feeds/{feed}', 'pages.feeds.view')->name('feeds.view');

    Volt::route('tags', 'pages.tags.index')->name('tags');
});

require __DIR__.'/auth.php';
