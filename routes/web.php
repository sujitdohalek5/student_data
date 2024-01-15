<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentForm;
use App\Http\Controllers\api\StudentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [StudentForm::class, 'loadView']);

Route::get('student/storeData', [StudentController::class, 'storeData'])->name('student/storeData');