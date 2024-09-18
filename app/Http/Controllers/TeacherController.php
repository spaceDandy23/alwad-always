<?php

namespace App\Http\Controllers;

use App\Models\StudentSubject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(){
        $teacher = Auth::user();

        $subjectsWithStudents = $teacher->subjects->mapWithKeys(function ($subject) {
            return [$subject->id => [
                'subject' => $subject,
                'students' => $subject->students()->withPivot('present')->get()
            ]];
        });
    
        return view('teacher.class_views', ['subjectsWithStudents' => $subjectsWithStudents]);
    }

    public function editStatus(Request $request, $studentID){
        $student = StudentSubject::findOrFail($studentID); 
        $student->update(['present' => intval($request->present)]);
        return redirect()->route("class.index")->with("success","Student updated");
    }



}
