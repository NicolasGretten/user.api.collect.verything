<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::controller(UserController::class)->group(function () {
    Route::get("users/", 'list');
    Route::post("users/", 'create');
    Route::patch("users/{id}", 'update');
    Route::delete("users/{id}", 'delete');
    Route::get("users/{id}", 'retrieve');
});
