<?php

use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\PartOneController;
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
Route::get('/getCountOfNumbers',[PartOneController::class, 'getCountOfNumbers']);
Route::get('/getIndexOfString',[PartOneController::class, 'getIndexOfString']);
Route::get('/calcSteps',[PartOneController::class, 'steps']);
Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(LogoutController::class)->group(function(){
    Route::get('logout', 'logout')->middleware('auth:sanctum');
});
Route::controller(UsersController::class)->group(function(){
    Route::get('getUserByID', 'getUserByID')->middleware('auth:sanctum');
    Route::get('getAllUsers', 'getAllUsers')->middleware('auth:sanctum');
    Route::post('updateUserData', 'updateUserData')->middleware('auth:sanctum');
    Route::post('deleteUserById', 'deleteUserById')->middleware('auth:sanctum');
});

