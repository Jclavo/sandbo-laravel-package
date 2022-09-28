<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Users
Route::resource('users', UserController::class);

// Profiles
Route::resource('profiles', ProfileController::class);
Route::get('profiles/activate/{profile_id}', [ProfileController::class, 'activate']);
Route::get('profiles/desactivate/{profile_id}', [ProfileController::class, 'desactivate']);