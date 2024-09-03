<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//CRUD students
Route::resource('students',StudentController::class);    
//C attendance record
Route::resource('attendances',AttendanceController::class);

//RFID Reader
Route::get('read',[RfidController::class, 'index'])->name('rfid-reader.index'); 
Route::post('read/verify',[RfidController::class,'verify'])->name('rfid-reader.verify');