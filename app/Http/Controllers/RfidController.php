<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class RfidController extends Controller
{
    //

    public function index(){
        $emptyHtml = $this->generateHtmlStudent('','','','','');
        return view('RFID-reader.verify_student', compact('emptyHtml'));
    }


    public function verify(Request $request){
        $validatedData = $request->validate([
            'rfid_tag' => 'required|string|max:255',
        ]);

        $student = Student::whereHas('tag', function($query) use ($validatedData) {
            $query->where('tag_number', $validatedData['rfid_tag']);
        })->first();

        if(!$student){
            return back()->with('error','Student Not Found');
        }
        $studentCard = $this->generateHtmlStudent($student->tag->tag_number,$student->id,$student->first_name,
        $student->last_name,$student->grade);
        return back()->with([
            'success' => 'Student found',
            'student' => $studentCard
        ]);
    }


    public function generateHtmlStudent($RFIDTag, $StudentID, $FirstName, $LastName, $Grade)
    {

        $route = route('rfid-reader.verify');  
        $method = 'post';  
        return "<div class='card'>
                    <div class='card-body'>
                        <h5 class='card-title'>Student Details</h5>
                        <p class='card-text'>RFID Tag: $RFIDTag</p>
                        <p class='card-text'>Student ID: $StudentID</p>
                        <p class='card-text'>First Name: $FirstName</p>
                        <p class='card-text'>Last Name: $LastName</p>
                        <p class='card-text'>Grade: $Grade</p>
                        <form action='{$route}' method='{$method}' id = 'hidden-form'>
                            ".csrf_field()."  
                            <label for='rfid_tag' class='form-label'>RFID Tag</label>
                            <input type='number' name='rfid_tag' class='form-control mb-2' id = 'rfid_field' required>
                            <button id = 'submit-btn' class='btn btn-primary'>Verify</button>
                        </form>
                    </div>
                </div>";
    }
}
