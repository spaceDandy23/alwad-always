<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $attendances = Attendance::all();


    //     return view("admin.attendance-record.attendance_list", compact("attendances"));
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Attendance $attendance)
    // {
        
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Attendance $attendance)
    // {
        
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, Attendance $attendance)
    // {
    //     $attendance->update($request->all());
    //     return redirect()->route("attendances.index")->with("success","Student updated");
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Attendance $attendance)
    // {
    //     $attendance->delete();
    //     return redirect()->route("attendances.index")->with("success","Student deleted");
    // }
}
