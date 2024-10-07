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
        Route::post('import', [StudentController::class, 'importCSV'])->name('import-csv');
        Route::post('search', [StudentController::class, 'search'])->name('search')->middleware('teacher');
        Route::match(['get', 'post'], 'register',[StudentController::class, 'register'])->name('register-tag');

        //CRUD subjects
        Route::resource('subjects',SubjectController::class); 
        //CRUD teachers
        Route::resource('users', UserController::class);

    });
    Route::middleware(['auth', 'teacher'])->group(function(){
        //RFID logic
        Route::match(['get', 'post'], 'read', [RfidController::class, 'showSubject'])->name('rfid-reader');
        Route::post('read/subject',[RfidController::class,'verify'])->name('rfid-reader.verify');

        //Teacher User and Add student
        Route::get('class',[TeacherController::class,'index'])->name('class-index');
        Route::post('class/add', [TeacherController::class,'addStudentClass'])->name('add-student');

        //Attendance logic
        Route::get('attendance', [TeacherController::class, 'attendanceRecords'])->name('attendance');
        Route::post('attendance/store', [TeacherController::class, 'storeAttendance'])->name('store-attendance');
        Route::get('message',[TeacherController::class,'messageParent'])->name('students-strikes');

        //Create class 
        Route::match(['post', 'get'], 'create', [TeacherController::class, 'createClass'])->name('create-class');

    });
    //Admin and Teacher
    Route::middleware(['auth'])->group(function () {
        Route::post('search', [StudentController::class, 'search'])->name('search');
    });



    //Authentication
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
    Route::post('login', [AuthController::class, 'login'])->name('login.auth'); //use match ??
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

});