<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RfidController extends Controller
{
    public function showSubject(Request $request)
    {
        if ($request->isMethod('post')) {
            $subject = Subject::findOrFail($request->subject_id);

            if (!$subject) {
                return redirect()->route('rfid-reader');
            }


            Session::put('subject', $subject);
            
            return redirect()->route('rfid-reader');
        }

        $subSession = Session::get('subject', ''); 

        return view('RFID-reader.verify_student', compact('subSession'));
    }

    public function verify(Request $request)
    {
        $validatedTag = $request->validate(['rfid_tag' => 'required|numeric']);
        $tag = $validatedTag['rfid_tag'];
        $subject = Subject::findOrFail($request->subject_id);


        foreach (Session::get($subject->id, []) as $s) {
            if ($tag === $s['rfid_tag']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student already attended',
                ]);
            }
        }

        $student = Student::whereHas('tag', function ($query) use ($tag) {
            $query->where('tag_number', $tag);
        })->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
            ]);
        }

        $studentSubject = StudentSubject::where(function ($query) use ($student, $subject) {
            $query->where('student_id', $student->id)
                ->where('subject_id', $subject->id);
        })->first();

        if (!$studentSubject) {
            return response()->json([
                'success' => false,
                'message' => 'Student not enrolled in this subject',
            ]);
        }

        $student->setAttribute('rfid_tag', $tag);
        $student->setAttribute('present', true);

        $this->setPresent($student, $subject, Auth::user());
        

        $studentsAttended = Session::get($subject->id, []);
        array_push($studentsAttended, $student);
        Session::put($subject->id, $studentsAttended);

        return response()->json([
            'success' => true,
            'student' => [
                'student_id' => $student->id,
                'rfid_tag' => $tag,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'grade' => $student->grade,
                'section' => $student->section,
            ],
            'studentsAttended' => Session::get($subject->id),
            'message' => 'Student found',
        ]);
    }

    public function setPresent($student, $subject, $teacher){
        // Attendance::create(
        //     ['present' => $student['present'],
        //                 'student_id' => $student->id,
        //                 'subject_id' => $subject->id,
        //                 'teacher_id' => $teacher->id,
        //                 'date' => '2002-06-12',
        //         ]);
        

    }
}
