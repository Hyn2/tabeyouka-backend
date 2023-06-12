<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TeammateController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LocalSemesterController;
use App\Http\Controllers\LocalSemesterCommentsController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::group(['middleware' => ['web']], function () {
  // Register User
  Route::post('/register', [UserController::class, 'register']);
  // Login User
  Route::post('/login', [UserController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {
  // Logout User
  Route::post('/logout', [UserController::class, 'logout'])->name('logout');
  // Get Authenticated User
  Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
  // Get Login Status
  Route::get('/get-login-status', [UserController::class, 'getLoginStatus']);
});

Route::get('/allusers', [UserController::class, 'getAllUsers']);

// Get Entire List of Restaurants
Route::get('/restaurants', [RestaurantController::class, 'index']);
// Get Specific Restaurant
Route::get('/restaurants/{id}', [RestaurantController::class, 'getStoreById']);
Route::post('/restaurants', [RestaurantController::class, 'store']);
Route::put('/restaurants/{id}', [RestaurantController::class, 'update']);
Route::delete('/restaurants/{id}', [RestaurantController::class, 'destroy']);


// 사용자 인증
Route::middleware(['auth'])->group(function() {
  // Add Review
  Route::post('/review', [ReviewController::class, 'addReview']);
  // Edit Review
  Route::patch('/review', [ReviewController::class, 'editReview']);
  // post LocalSemester Comment
  Route::post('/localsemestercomments', [LocalSemesterCommentsController::class, 'addComment']);
  // edit LocalSemester Comment
  Route::patch('/localsemestercomments', [LocalSemesterCommentsController::class, 'editComment']);
  // Edit LocalSemester Article
  Route::put('/localsemester', [LocalSemesterController::class, 'editArticle']);
  // 값이 비었는지 확인
  Route::middleware(['validate.empty'])->group(function() {
    // delete LocalSemester Comment
    Route::delete('/localsemestercomments/{id}', [LocalSemesterCommentsController::class, 'deleteComment']);
    // Delete Review
    Route::delete('/review/{id}', [ReviewController::class, 'deleteReview']);
  });
});


// 값이 비었는지 확인
Route::middleware(['validate.empty'])->group(function() {
  // Get Review(for edit)
  Route::get('/review/{id}', [ReviewController::class, 'getReviewById']);
  // Get Restaurant Review
  Route::get('/restaurantreview/{restaurant_id}', [ReviewController::class, 'getRestaurantReviews']); 
});


// Get LocalSemester Comments  
Route::get('/localsemestercomments', [LocalSemesterCommentsController::class, 'getComments']);
// Get LocalSemester Article  
Route::get('/localsemester', [LocalSemesterController::class, 'getArticle']);


// Teammates
Route::get('/teammates', [TeammateController::class, 'index']);
Route::get('/teammates/{id}', [TeammateController::class, 'show']);
Route::post('/teammates', [TeammateController::class, 'store']);
Route::put('/teammates/{id}', [TeammateController::class, 'update']);
Route::delete('/teammates/{id}', [TeammateController::class, 'destroy']);


// Community
Route::middleware(['auth'])->group(function() {
  Route::apiResource('community', CommunityController::class);
});


// Comment
Route::middleware(['auth'])->group(function() {
  Route::apiResource('community/{community}/comments', CommentController::class)
      ->scoped(['community' => 'id']);
});