<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\StripeController;
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

Route::post('apply/coupon', [CouponController::class, 'applyCoupon']);

Route::post('store/order', [StripeController::class, 'storeUserOrders']);
Route::post('pay/order', [StripeController::class, 'payOrdersByStripe']);

//Route::get('/reviews/{productId}', [App\Http\Controllers\ReviewController::class, 'index']);
// Route::get('reviews/{productId}', function($productId) {
//     \Illuminate\Support\Facades\Log::info('Reviews route hit with productId: ' . $productId);
//     return app()->make(ReviewController::class)->index($productId);
// })->name('reviews.index')->middleware('log.requests');



