<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//CRUD students
Route::resource('students',StudentController::class);    
//CRUD attendance record
// Route::resource('attendances',AttendanceController::class);
//CRUD schedule
Route::resource('subjects',SubjectController::class);
//RFID Reader
Route::get('read',[RfidController::class, 'index'])->name('rfid-reader.index'); 
Route::post('read/verify',[RfidController::class,'verify'])->name('rfid-reader.verify');