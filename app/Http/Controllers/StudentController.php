<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Tag;
use Illuminate\Http\Request;
use Schema;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::paginate(20);
        return view("admin.student-record.student_list", compact("students"));
    }
    public function importCSV(Request $request){
        $file = $request->file('csv_file');

        $path = $file->storeAs('public/csv', $file->getClientOriginalName());


        if(($handle = fopen(storage_path('app/'. $path), 'r')) !== false){
            fgetcsv($handle);
            while(($data = fgetcsv($handle, 1000, ",")) !== false){

                Student::create([
                    'first_name' => $data[1],
                    'last_name' => $data[2],
                    'middle_name' => $data[3],
                    'grade' => intval($data[4]),
                    'section' => intval($data[5]),
                ]);

            }
            fclose($handle);
        }
        return back()->with('success', 'Added Successfully');
    }
    public function search(Request $request){

        $query = $request->input('search');

        $columns = Schema::getColumnListing('students');
        $studentQuery = Student::query();

        
        if(ctype_digit($query)){
            $integerQuery = intVal($query);
            $studentQuery->where('grade', 'LIKE', $integerQuery)
            ->orWhere('section', 'LIKE', $integerQuery);
        }
        else{
            foreach($columns as $column){
                $studentQuery->orWhere($column, 'LIKE', "%{$query}%");
            }
        }

        if($studentQuery->get()->isEmpty()){
            return response()->json(['success' => false]);
        }


        return response()->json([
            'success' => true,
            'results' => $studentQuery->get(),
        ]);


    }


    public function register(Request $request){
        if($request->isMethod('post')){

            $request->validate([
                'rfid_tag' => 'required|numeric|unique:tags,tag_number',  
            ]);
            $studentId = Student::findOrFail($request->student_id);

            if ($studentId->tag) {
                return back()->with('error', 'The student already has an RFID tag assigned.');
            }
            Tag::create([
                'tag_number' => $request->rfid_tag,
                'student_id' => $studentId->id,
            ]);
            return redirect()->route('register-tag')->with('success', 'Student Registered');

        }
        return view('admin.register_student');
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
