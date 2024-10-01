<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::paginate(5); 
        return view("admin.subject-record.subjects_list", compact("subjects"));
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
        $validatedData = $request->validate(
                        [
                            'name' => 'required|string|max:255|unique:subjects,name'
                            ]);

        Subject::create($validatedData);

        return redirect()->route("subjects.index")->with("success","Subject added");
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate(
                [
                'name' => 'required|string|max:255|unique:subjects,name, ' . $subject->id,
                ]);

        $subject->update($request->all());
        return redirect()->route("subjects.index")->with("success","Subject updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route("subjects.index")->with("success","Subject deleted");
    }
}
