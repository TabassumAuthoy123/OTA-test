<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\FlightController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user/registration', [AuthenticationController::class, 'userRegistration'])->middleware('throttle:auth');
Route::post('user/verification', [AuthenticationController::class, 'userVerification'])->middleware('throttle:auth');
Route::post('user/login', [AuthenticationController::class, 'userLogin'])->middleware('throttle:login');
Route::post('/forgot/password', [AuthenticationController::class, 'forgotPassword'])->middleware('throttle:auth');
Route::post('/reset/password', [AuthenticationController::class, 'resetPassword'])->middleware('throttle:auth');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::post('/update-profile', [AuthenticationController::class, 'updateProfile']);
    Route::get('/submit/user/delete/request', [AuthenticationController::class, 'submitAccountDeleteRequest']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/book/my/flight', [FlightController::class, 'bookMyFlight']);
    Route::post('/flight/booking/payment', [FlightController::class, 'flightBookingPayment']);
    Route::get('/my/flight/bookings', [FlightController::class, 'myFlightBookings']);
    Route::post('/flight/booking/details', [FlightController::class, 'flightBookingDetails']);
    Route::post('/cancel/flight/booking', [FlightController::class, 'cancelFlightBooking']);
});



