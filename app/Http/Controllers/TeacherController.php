<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;

class TeacherController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
    
        $subjectsWithStudents = $teacher->subjects->mapWithKeys(function ($subject) use ($teacher) {
            $students = $subject->students()->wherePivot('teacher_id', $teacher->id)->get();
            
            return [$subject->id => [
                'subject' => $subject,
                'students' => $students,
            ]];
        });
    
        return view('teacher.class_views', compact('subjectsWithStudents'));
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
                $student['present'] = $request->input('present')[$key]; 
                $studentSubject[$key] = $student; 
                $studentObj = Student::find($key);


                    
                Attendance::firstOrCreate([
                    'date' => now()->toDateString(),
                    'present' => $student['present'],
                    'student_id' => $student['id'],
                    'teacher_id' => Auth::user()->id,
                    'subject_id' => $subject->id,
                ]);

                if(!$student['present']){
                    $strikeIsThree = ($studentObj->strike === 3) ? true : false;
                    if(!$strikeIsThree){
                        Student::where('id', $key)->increment('strikes');
                    }
                }



            }
            Session::put($id, $studentSubject);

        }
    
        return response()->json([
            'success' => true,
            'student_subject' => $studentSubject,
        ]);
    }
    public function messageParent(){
        $studentsWithStrikes = Student::where('strikes', 3)->get();
        return view('teacher.message_parent', compact('studentsWithStrikes'));
    }
    public function addStudentClass(Request $request){
        $studentId = $request->input('student_id');
        $subjectId = $request->input('subject_id');

        StudentSubject::firstOrCreate([
            'student_id' => $studentId,
            'teacher_id' => Auth::user()->id,
            'subject_id' => $subjectId
        
        ]);

        return redirect()->route('class-index')->with('success', 'Student Added Successfully');

    }
    public function createClass(Request $request){

        if($request->isMethod('post')){

            $validatedData = $request->validate([
                'subject' => 'required|exists:subjects,id',
                'students' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);


            $subject = Subject::findOrFail($validatedData['subject']);
            
            $schedule = sprintf(
                "%02d:%02d %s - %02d:%02d %s",
                $validatedData['start_time']['hour'],
                $validatedData['start_time']['minute'],
                $validatedData['start_time']['ampm'],
                $validatedData['end_time']['hour'],
                $validatedData['end_time']['minute'],
                $validatedData['end_time']['ampm']
            );

            if(!SubjectTeacher::where('user_id', Auth::user()->id)
                                ->where('subject_id', $subject->id)->first()){
                SubjectTeacher::create([
                    'user_id' => Auth::user()->id,
                    'subject_id' => $subject->id,
                    'schedule' => $schedule
                ]);
            }
            foreach($validatedData['students'] as $student){

                StudentSubject::firstOrCreate([
                    'student_id' => $student['id'],
                    'subject_id' => $subject->id,
                    'teacher_id' => Auth::user()->id,
                ]);

            }
           

            return response()->json([
                'success' => true
            ]);

        }
        $subjects = Subject::all();
        $students = Student::all();
        return view('teacher.create_class',compact('subjects','students'));
    }
    



}
