<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('send_invitation', [AuthController::class , 'sendEmail']);
Route::post('register', [AuthController::class , 'registerUser'])->name('registerUser');
Route::post('login', [AuthController::class , 'loginUser'])->name('login');
Route::post('verify', [AuthController::class , 'verifyUser'])->name('verifyUser');
Route::post('update', [AuthController::class , 'updateUser'])->middleware('auth');
