<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// product routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{category}/category', [ProductController::class, 'filterProductsByCategory']);
Route::get('/products/{brand}/brand', [ProductController::class, 'filterProductsByBrand']);
Route::get('/products/{color}/color', [ProductController::class, 'filterProductsByColor']);
Route::get('/products/{size}/size', [ProductController::class, 'filterProductsBySize']);
Route::get('/products/search/{term}', [ProductController::class, 'search']);

Route::get('/products/{product}/show', [ProductController::class, 'show']);

