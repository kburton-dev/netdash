<?php

use App\Livewire\Dashboard;
use App\Livewire\Favourites;
use Illuminate\Routing\Router;
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

Route::middleware(['auth', 'verified'])->group(function (Router $router) {
    $router->get('dashboard', Dashboard::class)->name('dashboard');
    $router->get('favourites', Favourites::class)->name('favourites');

    Volt::route('feeds', 'pages.feeds.index')->name('feeds');
    Volt::route('feeds/add', 'pages.feeds.add')->name('feeds.add');
    Volt::route('feeds/{feed}', 'pages.feeds.view')->name('feeds.view');

    Volt::route('tags', 'pages.tags.index')->name('tags');
});

require __DIR__.'/auth.php';
