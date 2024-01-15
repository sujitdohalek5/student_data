<?php

use App\Http\Controllers\api\StudentController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::match(['get', 'post'],'student/checkEmail', [StudentController::class, 'checkEmail'])->name('checkEmail');
Route::match(['get', 'post'],'student/checkOtp', [StudentController::class, 'checkOtp'])->name('checkOtp');
Route::match(['get', 'post'],'student/getToken', [StudentController::class, 'getToken'])->name('getToken');