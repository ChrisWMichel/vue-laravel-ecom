<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/cart', function () {
    return Inertia::render('cart/Cart');
})->name('cart');

Route::get('/checkout', function () {
    return Inertia::render('checkout/checkout');
})->name('checkout');

Route::get('/admin/login',[AdminController::class,"login"])->name("admin.login");
Route::post('admin/auth',[AdminController::class,"auth"])->name("admin.auth");


Route::prefix("admin")->middleware("admin")->group(function() {
    Route::get('dashboard',[AdminController::class,"index"])->name("admin.index");
    Route::post('logout',[AdminController::class,"logout"])->name("admin.logout");
    //categories routes

    Route::resource("categories",CategoryController::class,[
        'names' => [
            'index' => 'admin.categories.index',
            'create' => 'admin.categories.create',
            'store' => 'admin.categories.store',
            'edit' => 'admin.categories.edit',
            'update' => 'admin.categories.update',
            'destroy' => 'admin.categories.destroy',
        ]
    ]);
    Route::resource("brands",BrandController::class,[
        'names' => [
            'index' => 'admin.brands.index',
            'create' => 'admin.brands.create',
            'store' => 'admin.brands.store',
            'edit' => 'admin.brands.edit',
            'update' => 'admin.brands.update',
            'destroy' => 'admin.brands.destroy',
        ]
    ]);
    Route::resource("colors",ColorController::class,[
        'names' => [
            'index' => 'admin.colors.index',
            'create' => 'admin.colors.create',
            'store' => 'admin.colors.store',
            'edit' => 'admin.colors.edit',
            'update' => 'admin.colors.update',
            'destroy' => 'admin.colors.destroy',
        ]
    ]);
    Route::resource("sizes",SizeController::class,[
        'names' => [
            'index' => 'admin.sizes.index',
            'create' => 'admin.sizes.create',
            'store' => 'admin.sizes.store',
            'edit' => 'admin.sizes.edit',
            'update' => 'admin.sizes.update',
            'destroy' => 'admin.sizes.destroy',
        ]
    ]);
    Route::resource("products",ProductController::class,[
        'names' => [
            'index' => 'admin.products.index',
            'create' => 'admin.products.create',
            'store' => 'admin.products.store',
            'edit' => 'admin.products.edit',
            'update' => 'admin.products.update',
            'destroy' => 'admin.products.destroy',
        ]
    ]);
    Route::resource("coupons",CouponController::class,[
        'names' => [
            'index' => 'admin.coupons.index',
            'create' => 'admin.coupons.create',
            'store' => 'admin.coupons.store',
            'edit' => 'admin.coupons.edit',
            'update' => 'admin.coupons.update',
            'destroy' => 'admin.coupons.destroy',
        ]
    ]);

    Route::get('orders',[OrderController::class,"index"])->name("admin.orders.index");
    Route::get('update/{order}/order',[OrderController::class,"updateDeliveredAtDate"])->name("admin.orders.update");
    Route::delete('delete/{order}/order',[OrderController::class,"delete"])->name("admin.orders.delete");

    // Routes for image handling
    Route::delete('images/{product}/{imageField}', [ImageController::class, 'deleteImage'])->name('admin.images.delete');
    Route::post('images/{product}/{imageField}', [ImageController::class, 'updateImage'])->name('admin.images.update');

    Route::get('reviews',[AdminReviewController::class,"index"])->name("admin.reviews.index");
    Route::post('reviews/{review}/toggle/{status}',[AdminReviewController::class,"toggleApproveStatus"])->name("admin.reviews.toggle");
    Route::delete('delete/{review}/review',[AdminReviewController::class,"index"])->name("admin.reviews.delete");

    Route::get('users',[UserController::class,"index"])->name("admin.users.index");
    Route::delete('delete/{user}/user',[UserController::class,"delete"])->name("admin.user.delete");
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile-address', [ProfileController::class, 'updateAddress'])->name('update.address');
    Route::post('/profile-image', [ProfileController::class, 'updateImage'])->name('update.profile.image');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
});

Route::get('/success/payment/{hash}', function ($hash) {
    
    return Inertia::render('payments/successPayment', ['hash' => $hash]);
})->name('success.payment');

Route::get('reviews/{productId}', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('store/review', [ReviewController::class, 'store'])->name('store.review');
Route::put('update/review/{id}', [ReviewController::class, 'update'])->name('update.review');
Route::delete('delete/review/{id}', [ReviewController::class, 'destroy'])->name('delete.review');

// Route::get('reviews/{productId}', function($productId) {
//     \Illuminate\Support\Facades\Log::info('Reviews route hit with productId: ' . $productId);
//     return app()->make(ReviewController::class)->index($productId);
// })->name('reviews.index')->middleware('log.requests');

Route::get('/debug-env', function () {
    return [
        'FILESYSTEM_DISK' => env('FILESYSTEM_DISK'),
        'AWS_BUCKET' => env('AWS_BUCKET'),
        'AWS_DEFAULT_REGION' => env('AWS_DEFAULT_REGION'),
        'AWS_URL' => env('AWS_URL'),
    ];
})->middleware('admin'); 

Route::get('/test-s3', function () {
    try {
        $result = Storage::disk('s3')->put('test.txt', 'Hello World');
        return [
            'success' => $result,
            'message' => 'S3 test successful',
            'disk' => config('filesystems.default'),
            'bucket' => env('AWS_BUCKET'),
        ];
    } catch (\Exception $e) {
        return [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ];
    }
})->middleware('admin'); 

require __DIR__.'/auth.php';
