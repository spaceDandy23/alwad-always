<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use Illuminate\Http\Request;

class RfidController extends Controller
{




    public function showSubject(Request $request){


        if($request->isMethod('post')){
            $subject = Subject::findOrFail($request->subject_id);


            if(!$subject){
                return redirect()->route('rfid-reader');
            }
            $request->session()->put('subject', $subject);
            
            return redirect()->route('rfid-reader');
        }
        $subSession = session()->get('subject','');

        return view('RFID-reader.verify_student', compact('subSession'));
    }
    public function verify(Request $request)
    {
        $validatedTag = $request->validate(['rfid_tag' => 'required|numeric']);
        $tag = $validatedTag['rfid_tag'];
    
        $subject = Subject::findOrFail($request->subject_id);
        
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
                'student_id' => $student->id,
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
