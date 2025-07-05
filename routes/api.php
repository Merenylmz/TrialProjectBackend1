<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsValidToken;
use Illuminate\Http\Request;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Mail\DailyReportMail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix("/blogs")->group(function(){
    Route::get("/", [BlogController::class, "getAllBlogs"]);
    Route::get("/{id}", [BlogController::class, "getBlogById"]);
    Route::post("/create",[BlogController::class, "addBlog"])->middleware(IsValidToken::class);
    Route::delete("/delete/{id}", [BlogController::class, "deleteBlog"])->middleware(IsValidToken::class);
    Route::put("/edit/{id}", [BlogController::class, "editBlog"])->middleware(IsValidToken::class);
});

Route::prefix("/categories")->group(function(){
    Route::get("/withblogs/{id}", [CategoryController::class, "getCategoryWithBlogs"]);
    Route::get("/", [CategoryController::class, "getAllCategories"]);
    Route::get("/{id}", [CategoryController::class, "getCategoryById"]);
    Route::post("/create", [CategoryController::class, "addCategory"])->middleware(IsValidToken::class);
    Route::delete("/delete/{id}", [CategoryController::class, "deleteCategory"])->middleware(IsValidToken::class);
});

Route::prefix("/auth")->group(function(){
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/forgotpassword", [AuthController::class, "forgotPassword"]);
    Route::post("/newpassword", [AuthController::class, "newPassword"]);
    Route::put("/editprofile", [AuthController::class, "editProfile"]);
});

