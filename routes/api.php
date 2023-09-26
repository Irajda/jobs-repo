<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobController;
use Illuminate\Support\Facades\Route;
use App\Constant\PermissionConstant as Permission;

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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'auth.permission'])->group(function () {
    Route::prefix('job')->group(function () {
        Route::get('/get', [JobController::class, 'index'])->setPermission(Permission::JOB_VIEW);
        Route::post('/create', [JobController::class, 'store'])->setPermission(Permission::JOB_CREATE);
        Route::post('/assign/{job}', [JobController::class, 'assignJob'])->setPermission(Permission::ASSIGN_JOB);
        Route::put('/updateAssignedJob/{job}', [JobController::class, 'updateAssignmentJob'])->setPermission(Permission::UPDATE_ASSIGN_JOB);
        Route::delete('/delete/{job}', [JobController::class, 'delete'])->setPermission(Permission::JOB_DELETE);
    });
});
