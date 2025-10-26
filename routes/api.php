<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\RequisitionController;
use App\Http\Controllers\Api\NotificationController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});





Route::middleware('auth:sanctum')->group(function () {
    // Register API resources only if the corresponding controller classes exist.
    if (class_exists(\App\Http\Controllers\Api\EmployeeController::class)) {
        Route::apiResource('employees', \App\Http\Controllers\Api\EmployeeController::class);
    }

    if (class_exists(\App\Http\Controllers\Api\DriverController::class)) {
        Route::apiResource('drivers', \App\Http\Controllers\Api\DriverController::class);
    }

    if (class_exists(\App\Http\Controllers\Api\VehicleController::class)) {
        Route::apiResource('vehicles', \App\Http\Controllers\Api\VehicleController::class);
    }

    // Requisition routes (guarded)
    if (class_exists(\App\Http\Controllers\Api\RequisitionController::class)) {
        Route::get('requisitions', [\App\Http\Controllers\Api\RequisitionController::class,'index']);
        Route::get('requisitions/{id}', [\App\Http\Controllers\Api\RequisitionController::class,'show']);
        Route::post('requisitions', [\App\Http\Controllers\Api\RequisitionController::class,'store']);
        Route::put('requisitions/{id}', [\App\Http\Controllers\Api\RequisitionController::class,'update']);
        Route::delete('requisitions/{id}', [\App\Http\Controllers\Api\RequisitionController::class,'destroy']);

        // Approval endpoints
        Route::post('requisitions/{id}/approve/manager', [\App\Http\Controllers\Api\RequisitionController::class,'approveByManager']);
        Route::post('requisitions/{id}/approve/officer', [\App\Http\Controllers\Api\RequisitionController::class,'approveByOfficer']);
        Route::post('requisitions/{id}/assign', [\App\Http\Controllers\Api\RequisitionController::class,'assignVehicleAndDriver']);
        Route::post('requisitions/{id}/complete', [\App\Http\Controllers\Api\RequisitionController::class,'completeTrip']);
    }

    // Notifications (guarded)
    if (class_exists(\App\Http\Controllers\Api\NotificationController::class)) {
        Route::get('notifications', [\App\Http\Controllers\Api\NotificationController::class,'index']);
        Route::post('notifications/mark-read/{id}', [\App\Http\Controllers\Api\NotificationController::class,'markRead']);
    }
});

