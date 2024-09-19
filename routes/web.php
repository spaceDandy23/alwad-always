<?php

// use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


 
//CRUD attendance record
// Route::resource('attendances',AttendanceController::class);



Route::middleware('no-cache')->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        //CRUD students
        Route::resource('students',StudentController::class);  
        //CRUD subjects
        Route::resource('subjects',SubjectController::class); 
        //CRUD teachers
        Route::resource('users', UserController::class);
    });
    Route::middleware(['auth', 'teacher'])->group(function(){
        //RFID logic
        Route::match(['get', 'post'], '/read', [RfidController::class, 'showSubject'])->name('rfid-reader');
        Route::post('read/subject',[RfidController::class,'verify'])->name('rfid-reader.verify');

        //Teacher User
        Route::get('class',[TeacherController::class,'index'])->name('class.index');
        Route::put('class/{studentId}',[TeacherController::class,'editStatus'])->name('class.update');


    });



    //Authentication
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
    Route::post('/login', [AuthController::class, 'login'])->name('login.auth');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});