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
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('drivers', DriverController::class);
    Route::apiResource('vehicles', VehicleController::class);

    // Requisition routes
    Route::get('requisitions', [RequisitionController::class,'index']);
    Route::get('requisitions/{id}', [RequisitionController::class,'show']);
    Route::post('requisitions', [RequisitionController::class,'store']);
    Route::put('requisitions/{id}', [RequisitionController::class,'update']);
    Route::delete('requisitions/{id}', [RequisitionController::class,'destroy']);

    // Approval endpoints
    Route::post('requisitions/{id}/approve/manager', [RequisitionController::class,'approveByManager']);
    Route::post('requisitions/{id}/approve/officer', [RequisitionController::class,'approveByOfficer']);
    Route::post('requisitions/{id}/assign', [RequisitionController::class,'assignVehicleAndDriver']);
    Route::post('requisitions/{id}/complete', [RequisitionController::class,'completeTrip']);

    // Notifications
    Route::get('notifications', [NotificationController::class,'index']);
    Route::post('notifications/mark-read/{id}', [NotificationController::class,'markRead']);
});

