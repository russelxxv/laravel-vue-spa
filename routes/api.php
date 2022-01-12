<?php

use App\Http\Controllers\Api\BookableAvailabilityController;
use App\Models\Bookable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookableController;
use App\Http\Controllers\Api\BookablePriceController;
use App\Http\Controllers\Api\BookableReviewController;
use App\Http\Controllers\Api\BookingByReviewController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ReviewController;

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
Route::apiResource('bookables', BookableController::class);
Route::get('bookables/{id}/availability', BookableAvailabilityController::class)
    ->name('bookable.availability');
Route::get('bookables/{id}/reviews', BookableReviewController::class)
    ->name('bookable.reviews');
Route::get('bookables/{bookable}/price', BookablePriceController::class)
    ->name('bookable.price.show');
Route::apiResource('reviews', ReviewController::class)->only(['show', 'store']);
Route::get('/booking-by-review/{reviewkey}', BookingByReviewController::class)
    ->name('booking.by-review.show');

Route::post('/checkout', CheckoutController::class)->name('checkout');