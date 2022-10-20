<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ProductController;

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

Route::get('iva', [InvoiceController::class, 'getIVA'])->name('api.iva.get');

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('auth.login');
    Route::post('register', [\App\Http\Controllers\AuthController::class, 'register'])->name('auth.register');


    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('auth.logout');
        Route::get('user', [\App\Http\Controllers\AuthController::class, 'user'])->name('user.get');

    });
});

Route::group([
    'prefix' => 'admin',
    'middleware' => 'auth:api'
], function () {
    Route::group([
        'prefix' => 'invoice',
    ], function(){
        Route::get('', [InvoiceController::class, 'get'])->name('api.invoice.get');
        Route::get('/{id}', [InvoiceController::class, 'getById'])->name('api.invoice.getById');
        Route::post('', [InvoiceController::class, 'store'])->name('api.invoice.store');
        Route::put('/update', [InvoiceController::class, 'update'])->name('api.invoice.update');
        Route::post('/delete', [InvoiceController::class, 'delete'])->name('api.invoice.delete');
    });

    Route::group([
        'prefix' => 'product',
    ], function(){
        Route::get('', [ProductController::class, 'get'])->name('api.product.get');
    });
});
