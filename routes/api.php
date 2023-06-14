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

Route::middleware(['web'])->group(function () { // TODO: 로그인한 유저만 가능하게 변경
  // Logout User
  Route::post('/logout', [UserController::class, 'logout'])->name('logout');
  // Get Authenticated User
  // Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
});

// Register User
Route::post('/register', [UserController::class, 'register']);

Route::middleware(['web'])->group(function () {
  // Login User
  Route::post('/login', [UserController::class, 'login']);
  // Get Login Status
  Route::get('/status', [UserController::class, 'getLoginStatus']); // TODO: 로그인한 유저만 가능하게 변경
});

Route::get('/allusers', [UserController::class, 'getAllUsers']);  // TODO: 관리자 권한 부여

// Get Entire List of Restaurants
Route::get('/restaurants', [RestaurantController::class, 'index']);
// Get Specific Restaurant
Route::get('/restaurants/{id}', [RestaurantController::class, 'getStoreById']);
Route::post('/restaurants', [RestaurantController::class, 'store']);
Route::put('/restaurants/{id}', [RestaurantController::class, 'update']);
Route::delete('/restaurants/{id}', [RestaurantController::class, 'destroy']);


// 사용자 인증
Route::middleware(['web', 'api'])->group(function() { // TODO: 로그인한 유저만 가능하게 변경
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
  // Route::middleware(['validate.empty'])->group(function() {
    // delete LocalSemester Comment
    Route::delete('/localsemestercomments/{id}', [LocalSemesterCommentsController::class, 'deleteComment']);
    // Delete Review
    Route::delete('/review/{id}', [ReviewController::class, 'deleteReview']);
  // });
});


// 값이 비었는지 확인
Route::middleware(['web', 'api', 'validate.empty'])->group(function() {
  // Get Review(for edit)
  Route::get('/review/{id}', [ReviewController::class, 'getReviewById']);
  // Get Restaurant Review
  Route::get('/restaurantreview/{restaurant_id}', [ReviewController::class, 'getRestaurantReviews']); 
});

Route::middleware(['web', 'api'])->group(function() { // TODO: 로그인한 유저만 가능하게 변경
  // Get LocalSemester Comments
  Route::get('/localsemestercomments', [LocalSemesterCommentsController::class, 'getComments']);
  // Get LocalSemester Article  
  Route::get('/localsemester', [LocalSemesterController::class, 'getArticle']);
});

// Teammates
Route::middleware(['web', 'api'])->group(function() {
  Route::get('/teammates', [TeammateController::class, 'index']);
  Route::get('/teammates/{id}', [TeammateController::class, 'show']);
  Route::post('/teammates', [TeammateController::class, 'store']);  // TODO: 관리자 권한 부여
  Route::match(['put', 'post'], '/teammates/{id}', [TeammateController::class, 'update'])->name('teammates.update');  // TODO: 관리자 권한 부여
  Route::delete('/teammates/{id}', [TeammateController::class, 'destroy']);
});

// Community
Route::middleware(['web', 'api'])->group(function() {
  Route::get('/community', [CommunityController::class, 'index']);
  Route::post('/community', [CommunityController::class, 'store']); // TODO: 로그인한 유저만 가능하게 변경
  Route::get('/community/{community}', [CommunityController::class, 'show']);
  // Route::put('/community/{community}', [CommunityController::class, 'update']);
  Route::match(['put', 'post'], '/community/{community}', [CommunityController::class, 'update'])->name('community.update');  // TODO: 로그인한 유저만 가능하게 변경
  Route::delete('/community/{community}', [CommunityController::class, 'destroy']); // TODO: 로그인한 유저만 가능하게 변경
});


// Comment
Route::middleware(['web', 'api'])->group(function() {
  // Route::apiResource('community/{community}/comments', CommentController::class)
  //     ->scoped(['community' => 'id']);
  Route::get('/post/{post}/comments', [CommentController::class, 'index']); 
  Route::post('/post/{post}/comments', [CommentController::class, 'store']);  // TODO: 로그인한 유저만 가능하게 변경
  Route::match(['put', 'post'], '/post/{post}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');  // TODO: 로그인한 유저만 가능하게 변경
  // Route::put('/post/{post}/comments/{comment}', [CommentController::class, 'update']);
  Route::delete('/post/{post}/comments/{comment}', [CommentController::class, 'destroy']);  // TODO: 로그인한 유저만 가능하게 변경
});