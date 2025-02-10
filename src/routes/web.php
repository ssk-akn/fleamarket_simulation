<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'detail']);
Route::post('/item/{item_id}/like', [LikeController::class, 'store']);
Route::post('/item/{item_id}/unlike', [LikeController::class, 'destroy']);

Route::middleware('auth')->group(function () {
    Route::get('/purchase/{id}',[ItemController::class, 'purchase']);
    Route::get('/purchase/address/{id}', [ItemController::class, 'address']);
    Route::get('/sell', [CategoryController::class, 'sell']);
    Route::get('/mypage', [UserController::class, 'mypage']);
    Route::get('/mypage?page=buy', [UserController::class, 'mypage']);
    Route::get('/mypage?page=sell', [UserController::class, 'mypage']);
    Route::get('/mypage/profile', [UserController::class, 'profile']);
});

