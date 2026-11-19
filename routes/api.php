<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorAvailabilityController;
use App\Http\Controllers\Api\DoctorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post("/login", [AuthController::class, "login"]);
Route::domain('{subdomain}.localhost')
    ->middleware([
        'organization',
        'auth:sanctum',
        'organization.membership',
    ])
    ->group(function () {
        Route::get("/doctors", [DoctorController::class, "index"]);
        Route::post('/doctors', [DoctorController::class, 'store'])
            ->middleware('organization.admin');
        Route::put('/doctors/{doctorOrganizationId}', [DoctorController::class, 'update'])
            ->middleware('organization.admin');
        Route::get(
        '/doctors/{doctorOrganizationId}/availabilities',
            [DoctorAvailabilityController::class, 'index']
        );
        Route::post("/doctors/{doctorOrganizationId}/availabilities", [DoctorAvailabilityController::class, "store"])->middleware('organization.admin');
    Route::put("/doctors/{doctorOrganizationId}/availabilities/{availabilityId}", [DoctorAvailabilityController::class, "update"])->middleware('organization.admin');
    });