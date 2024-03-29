<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;

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

Route::get('base-address', [IndexController::class, 'baseAddress']);
Route::post('general-roles', [IndexController::class, 'updateGeneralRoles']);
Route::post('favorite-roles', [IndexController::class, 'updateFavoriteRoles']);
Route::get('town-coordinates', [IndexController::class, 'townCoordinates']);
Route::get('account/{id}/jobs', [IndexController::class, 'jobs']);
Route::post('role/id',[IndexController::class,'updateRoleID']);
Route::post('account/{id}/jobs',[IndexController::class,'updateAccountJob']);
