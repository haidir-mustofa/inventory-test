<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [InventoryController::class, 'index']);
Route::post('/produk', [InventoryController::class, 'storeProduct'])->name('product.store');
Route::get('/produk/{id}/edit', [InventoryController::class, 'editProduct'])->name('product.edit');
Route::put('/produk/{id}', [InventoryController::class, 'updateProduct'])->name('product.update');
Route::delete('/produk/{id}', [InventoryController::class, 'destroyProduct'])->name('product.destroy');
Route::post('/transaksi', [InventoryController::class, 'storeTransaction'])->name('transaction.store');
