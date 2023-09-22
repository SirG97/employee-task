<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/employee',[EmployeeController::class, 'allEmployees']);

Route::post('/employee',[EmployeeController::class, 'importEmployees']);

Route::get('/employee/{id}',[EmployeeController::class, 'getEmployee']);

Route::delete('/employee',[EmployeeController::class, 'deleteEmployee']);
