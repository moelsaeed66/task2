<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SanctumController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\TwoFactorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('posts/trash',[PostController::class,'trashed'])->name('posts.trash');
    Route::put('posts/{post}/restore',[PostController::class,'restore'])->name('posts.restore');
    Route::get('posts/soft-delete/{id}',[PostController::class,'softDelete'])->name('posts.soft-delete');
    Route::apiResource('posts', PostController::class);
    Route::apiResource('tags', TagController::class);
    Route::get('/stats/{key}', StatisticsController::class);
});



Route::post('register',[SanctumController::class,'register']);
Route::post('login',[SanctumController::class,'login']);
Route::post('verify',[SanctumController::class,'verify'])->middleware('auth:sanctum');
Route::post('logout',[SanctumController::class,'logout'])->middleware('auth:sanctum');
