<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChatController;

Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/home', [IndexController::class, 'index'])->name('login');
Route::get('/dashboard', [IndexController::class, 'dashboard'])->name('dashboard');

Route::get('/login', [LoginController::class, 'login']);
Route::get('/authorize', [LoginController::class, 'callback']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/chat', [ChatController::class, 'chat']);