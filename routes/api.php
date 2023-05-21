<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TeammatesController;

// Register User
Route::post('/register', [UserController::class, 'register']);
// Login User
Route::post('/login', [UserController::class, 'login']);
// Logout User
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);  // the auth:sanctum middleware is added to the /logout route to ensure that the user is authenticated before being able to log out.

// Get Entire List of Restaurants
Route::get('/restaurants', [RestaurantController::class, 'index']);
// Get Specific Restaurant
Route::get('/restaurants/{id}', [RestaurantController::class, 'getStoreById']);

// Add Review
Route::post('/review/{request}', [ReviewController::class, 'addReview']);
// Delete Review
Route::delete('/review/{id}', [ReviewController::class, 'deleteReview']);
// Get Review(for edit)
Route::get('/review/{id}', [ReviewController::class, 'getReviewById']);
// Edit Review
Route::put('/review/{request}', [ReviewController::class, 'editReview']);

// Get teammates
Route::get('teammates', [TeammatesController::class, 'index']);
