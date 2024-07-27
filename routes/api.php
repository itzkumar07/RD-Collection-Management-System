<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\ContactEnquiryController;
use App\Http\Controllers\API\NewsletterEmailController;
use App\Http\Controllers\API\ServiceQuoteController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\TestimonialController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('service', [ServiceController::class,'getAllServices']);
Route::get('service/{slug}', [ServiceController::class,'getIndividualService']);
Route::post('newsletter-email', [NewsletterEmailController::class,'handleCreateNewsletterEmail']);
Route::post('contact-enquiry', [ContactEnquiryController::class,'handleCreateContactEnquiry']);
Route::get('testimonial', [TestimonialController::class,'getAllTestimonials']);
Route::get('brand', [BrandController::class,'getAllBrands']);

Route::middleware('guest')->group(function () {
    Route::post('send-otp', [AuthController::class,'handleSendOtp']);
    Route::post('login', [AuthController::class,'handleLogin']);
    Route::post('register', [AuthController::class,'handleRegister']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('user', [UserController::class,'getUser']);

    Route::post('service-quote', [ServiceQuoteController::class, 'handleCreateServiceQuote']);
    Route::get('service-quote', [ServiceQuoteController::class, 'handleGetServiceQuote']);
    Route::get('service-quote/{uuid}', [ServiceQuoteController::class, 'handleGetIndividualServiceQuote']);
});