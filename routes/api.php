<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;

// Register User
Route::post('/register', [UserController::class, 'register']);

// Login User
Route::post('/login', [UserController::class, 'login']);

// Logout User
// the auth:sanctum middleware is added to the /logout route to ensure that the user is authenticated before being able to log out.
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);

Route::get('/restaurants', [RestaurantController::class, 'index']);

Route::get('/restaurants/{id}', [RestaurantController::class, 'getStoreById']);