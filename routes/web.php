<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/{category_slug}/{product:slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');
Route::post('/products/{product}/reviews', [App\Http\Controllers\ProductController::class, 'storeReview'])->name('reviews.store');
Route::get('/products/{product}/reviews', [App\Http\Controllers\ProductController::class, 'getReviews'])->name('reviews.get');

Route::get('/blog', function () {
    return 'Blog Placeholder';
})->name('blog.index');
