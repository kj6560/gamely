<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Site\AuthController;
use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class,'index'])->name('home');
Route::get('/game/{id}', [SiteController::class,'game'])->name('game');
Route::get('/login',[AuthController::class,'login'])->name('login');
Route::post('/placeBet',[SiteController::class,'placeBet'])->name('placeBet')->middleware('auth:web');

Route::get('/accountLedger',[SiteController::class,'accountLedger'])->name('accountLedger')->middleware('auth:web');
Route::post('/loginAuthentication',[AuthController::class,'loginAuthentication'])->name('loginAuthentication');
Route::get('/register',[AuthController::class,'register'])->name('register');
Route::post('/registerUser',[AuthController::class,'registerUser'])->name('registerUser');

//admin panel
Route::middleware('auth:web')->get('/dashboard',[AdminController::class,'index'])->name('dashboard');
Route::middleware('auth:web')->get('/dashboard/listGames',[GameController::class,'listGames'])->name('listGames');
Route::middleware('auth:web')->get('/dashboard/createGame',[GameController::class,'createGame'])->name('createGame');
Route::middleware('auth:web')->get('/dashboard/deleteGame/{id}',[GameController::class,'deleteGame'])->name('deleteGame');
Route::middleware('auth:web')->get('/dashboard/editGame/{id}',[GameController::class,'editGame'])->name('editGame');
Route::middleware('auth:web')->post('/dashboard/storeGame',[GameController::class,'storeGame'])->name('storeGame');