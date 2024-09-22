<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;

class TeacherController extends Controller
{
    public function index(){
        $teacher = Auth::user();

        $subjectsWithStudents = $teacher->subjects->mapWithKeys(function ($subject) {
            return [$subject->id => [
                'subject' => $subject,
                'students' => $subject->students,
            ]];
        });
    
        return view('teacher.class_views', ['subjectsWithStudents' => $subjectsWithStudents]);
    }
    public function attendanceRecords(){
        return view('teacher.attendance_record');
    }
    public function storeAttendance(Request $request) {
        $subjectIds = $request->input('subjects');
    
        foreach ($subjectIds as $id) {
            $studentSubject = Session::get($id);
            $subject = Subject::findOrFail($id);
            
            foreach ($studentSubject as $key => $student) {
                $studentFound = Student::findOrFail($key);
                $student['present'] = $request->input('present')[$key]; 
                $studentSubject[$key] = $student; 
    
                Attendance::create([
                    'date' => now()->toDateString(),
                    'present' => $student['present'],
                    'student_id' => $studentFound->id,
                    'teacher_id' => Auth::user()->id,
                    'subject_id' => $subject->id,
                ]);
            }
            Session::put($id, $studentSubject);
        }
    
        return response()->json([
            'success' => true,
            'student_subject' => $studentSubject,
        ]);
    }
    
    public function editStatus(Request $request, $studentID){
        $student = StudentSubject::findOrFail($studentID); 
        $student->update(['present' => intval($request->present)]);
        return redirect()->route("class.index")->with("success","Student updated");
    }



}
