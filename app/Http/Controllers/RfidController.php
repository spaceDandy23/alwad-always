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

            Session::put('subject', $subject);

            foreach ($subject->students as $s) {
                $studentsWithPresent[$s->id] = [
                    'id' => $s->id,
                    'present' => false,
                    'first_name' => $s->first_name,
                    'last_name' => $s->last_name,
                    'grade' => $s->grade,
                    'section' => $s->section,
                    'rfid_tag' =>$s->tag->tag_number,
                ];
            }

            Session::put($subject->id, $studentsWithPresent );
            
            
            return redirect()->route('rfid-reader');
        }

        $subSession = Session::get('subject', ''); 
        $subSessionStudents = $subSession->students;

        return view('RFID-reader.verify_student', compact('subSession', 'subSessionStudents'));
    }



    public function verify(Request $request)
    {
        $validatedTag = $request->validate([
            'rfid_tag' => 'required|numeric|exists:tags,tag_number', 
        ]);
        $tag = $validatedTag['rfid_tag'];
        $subject = Subject::findOrFail($request->subject_id);




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

        $studentsAttended = Session::get($subject->id, []);
        foreach ($subject->students as $s) {
            $studentsAttended[$s->id] = [
                'id' => $s->id,
                'present' => ($s->id === $student->id) ? true : ($studentsAttended[$s->id]['present'] ?? false),
                'first_name' => $s->first_name,
                'last_name' => $s->last_name,
                'grade' => $s->grade,
                'section' => $s->section,
                'rfid_tag' =>$s->tag->tag_number,
            ];
        }
    
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

}
