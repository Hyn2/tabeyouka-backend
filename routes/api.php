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

// Get Authenticated User
Route::middleware(['web'])->group(function () { // TODO: 로그인한 유저만 가능하게 변경
  // Logout User
  Route::post('/logout', [UserController::class, 'logout'])->name('logout');
  // Get Authenticated User
  // Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
});

// Register User
Route::post('/register', [UserController::class, 'register']);

// Login User
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

// Get Entire List of Reviews
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
  // delete LocalSemester Comment
  Route::delete('/localsemestercomments/{id}', [LocalSemesterCommentsController::class, 'deleteComment']);
  // Delete Review
  Route::delete('/review/{id}', [ReviewController::class, 'deleteReview']);
});


// Get Entire List of Reviews
Route::middleware(['web', 'api', 'validate.empty'])->group(function() {
  // Get Review(for edit)
  Route::get('/review/{id}', [ReviewController::class, 'getReviewById']);
  // Get Restaurant Review
  Route::get('/restaurantreview/{restaurant_id}', [ReviewController::class, 'getRestaurantReviews']); 
});

// Get Entire List of LocalSemester
Route::middleware(['web', 'api'])->group(function() { // TODO: 로그인한 유저만 가능하게 변경
  // Get LocalSemester Comments
  Route::get('/localsemestercomments', [LocalSemesterCommentsController::class, 'getComments']);
  // Get LocalSemester Article  
  Route::get('/localsemester', [LocalSemesterController::class, 'getArticle']);
});

// Get Entire List of Teammates
Route::middleware(['web', 'api'])->group(function() {
  // Get Teammates
  Route::get('/teammates', [TeammateController::class, 'index']);
  // Get Specific Teammate
  Route::get('/teammates/{id}', [TeammateController::class, 'show']);
  // Create Teammate
  Route::post('/teammates', [TeammateController::class, 'store']);  // TODO: 관리자 권한 부여
  // Update Teammate
  Route::match(['put', 'post'], '/teammates/{id}', [TeammateController::class, 'update'])->name('teammates.update');  // TODO: 관리자 권한 부여
  // Delete Teammate
  Route::delete('/teammates/{id}', [TeammateController::class, 'destroy']);
});

// Get Entire List of Community
Route::middleware(['web', 'api'])->group(function() {
  // Get Community
  Route::get('/community', [CommunityController::class, 'index']);
  // Create Community
  Route::post('/community', [CommunityController::class, 'store']); // TODO: 로그인한 유저만 가능하게 변경
  // Get Specific Community
  Route::get('/community/{community}', [CommunityController::class, 'show']);
  // Update Community
  Route::match(['put', 'post'], '/community/{community}', [CommunityController::class, 'update'])->name('community.update');  // TODO: 로그인한 유저만 가능하게 변경
  // Delete Community
  Route::delete('/community/{community}', [CommunityController::class, 'destroy']); // TODO: 로그인한 유저만 가능하게 변경
});


// Get Entire List of Comments
Route::middleware(['web', 'api'])->group(function() {
  // Get Comments
  Route::get('/post/{post}/comments', [CommentController::class, 'index']); 
  // Create Comment
  Route::post('/post/{post}/comments', [CommentController::class, 'store']);  // TODO: 로그인한 유저만 가능하게 변경
  // Update Comment
  Route::match(['put', 'post'], '/post/{post}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');  // TODO: 로그인한 유저만 가능하게 변경
  // Delete Comment
  Route::delete('/post/{post}/comments/{comment}', [CommentController::class, 'destroy']);  // TODO: 로그인한 유저만 가능하게 변경
});