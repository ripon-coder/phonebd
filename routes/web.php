<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/brands', [App\Http\Controllers\BrandController::class, 'index'])->name('brands.index');
Route::get('/devices', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/category/{category}', [App\Http\Controllers\BlogController::class, 'category'])->name('blog.category');

Route::get('/{category_slug}/{product:slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');
Route::post('/products/{product}/reviews', [App\Http\Controllers\ProductController::class, 'storeReview'])->name('reviews.store');
Route::post('/products/{product}/camera-samples', [App\Http\Controllers\ProductController::class, 'storeCameraSample'])->name('camera-samples.store');

Route::get('/favorites', function () {
    return view('favorites.index');
})->name('favorites.index');

Route::post('/products/favorites-list', [App\Http\Controllers\ProductController::class, 'favoritesList'])->name('products.favorites_list');
Route::post('/products/favorites-check', [App\Http\Controllers\ProductController::class, 'checkFavorites'])->name('products.favorites_check');
