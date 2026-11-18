<?php

use App\Http\Controllers\Api\DoctorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::domain("{subdomain}.localhost")->middleware(["organization", "auth", "organization.membership"])->group(function () {
    Route::get("/doctors", [DoctorController::class, "index"]);
});
