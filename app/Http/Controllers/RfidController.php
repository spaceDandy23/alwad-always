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


    public function verify(Request $request,$subjectID){
        $tag = $request->rfid_tag;

        $subject = Subject::findOrFail($subjectID);
        $student = Student::whereHas('tag', function ($query) use ($tag){
            return $query->where('tag_number', $tag);
        })->first();
        
        if (!$student) {
            return redirect()->route('rfid-reader-subject.index', $subjectID)
                             ->with('error', 'Student not found or RFID tag is incorrect.');
        }
        
        $studentSubject = StudentSubject::where(function ($query) use($student,$subject){
            $query->where('student_id', $student->id)
            ->where('subject_id', $subject->id);
        })->first();
  
        
        if(!$studentSubject->update(['present' => true])){
            return redirect()->route('rfid-reader-subject.index', $subjectID)
                                        ->with('Unexpected Error');

        }
        return redirect()->route('rfid-reader-subject.index', $subjectID)->with(['success' => $student]);


    }

}
