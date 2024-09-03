<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Tag;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        return view("admin.student_list", compact("students"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'grade' => 'required|string|max:10',
            'section' => 'required|string|max:3',
            'rfid_tag' => 'required|string|unique:tags,tag_number', 
        ]);  
        $student = Student::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'middle_name' => $validatedData['middle_name'],
            'grade' => $validatedData['grade'],
            'section' => $validatedData['section'],
        ]);
        Tag::create([
            'tag_number' => $validatedData['rfid_tag'],
            'student_id' => $student->id, 
        ]);
        return redirect()->route('students.index')->with('success', 'Student registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'grade' => 'required|string|max:10',
            'section' => 'required|string|max:3',
            'rfid_tag' => 'required|string|unique:tags,tag_number,' . $student->tag->id,
        ]);

        $student->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'middle_name' => $validatedData['middle_name'],
            'grade' => $validatedData['grade'],
            'section' => $validatedData['section'],
        ]);
    
        $student->tag->update([
            'tag_number' => $validatedData['rfid_tag'],
        ]);
    
        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {

        $student->delete();
    
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
