<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use Illuminate\Http\Request;

class RfidController extends Controller
{



    public function index(){
        return view('RFID-reader.verify_student');
    }

    public function showSubject($subjectID){
        $subject = Subject::findOrFail($subjectID);
        return view('RFID-reader.verify_student', compact('subject'));
    }
    public function verify(Request $request, $subjectID)
    {
        $validatedTag = $request->validate(['rfid_tag' => 'required|numeric']);
        $tag = $validatedTag['rfid_tag'];
    
        $subject = Subject::findOrFail($subjectID);
        
        $student = Student::whereHas('tag', function ($query) use ($tag) {
            $query->where('tag_number', $tag);
        })->first();
        if(!$student){
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ]);
        }
        $studentSubject = StudentSubject::where(function ($query) use($student,$subject){
            $query->where('student_id', $student->id)
            ->where('subject_id', $subject->id);
        })->first();
        if(!$studentSubject){
            return response()->json([
                'success' => false,
                'message' => 'Student not enrolled in this subject'
            ]);
        }
    
        return response()->json([
            'success' => true,
            'student' => [
                'rfid_tag' => $tag,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'grade' => $student->grade,
                'section' => $student->section,
            ],
            'message' => 'Student found',
        ]);


    }
    

}
