<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;

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
Route::get('/item/{item_id}', [ItemController::class, 'detail'])->name('item.detail');

Route::middleware('auth')->group(function () {
    Route::post('/item/{item_id}/like', [LikeController::class, 'store'])->name('item.store');
    Route::post('/item/{item_id}/unlike', [LikeController::class, 'destroy'])->name('item.destroy');
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store']);
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'getPurchase'])->name('purchase.get');
    Route::post('/purchase/update-payment', [PurchaseController::class, 'updatePayment'])->name('purchase.payment');
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'getAddress']);
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress']);
    Route::post('/purchase', [PurchaseController::class, 'store']);

    Route::get('/mypage', [UserController::class, 'getMypage']);
    Route::get('/mypage/profile', [UserController::class, 'getProfile']);//
    Route::post('/mypage/profile', [UserController::class, 'update']);//
    Route::get('/sell', [SellController::class, 'getSell']);
    Route::post('/sell',[SellController::class, 'store']);
});

