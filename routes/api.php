<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SubscriptionController;

//websites
Route::get('/websites', [WebsiteController::class, 'index']);
Route::post('/websites', [WebsiteController::class, 'store']);
//post 
Route::get('/websites/{website}/posts', [PostController::class, 'index']);
Route::get('/websites/{website}/posts/{post}', [PostController::class, 'store']);
Route::post('/websites/{website}/posts', [PostController::class, 'store']);
//subscription
Route::post('/websites/{website}/subscribe', [SubscriptionController::class, 'subscribe']);