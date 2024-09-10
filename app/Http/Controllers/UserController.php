<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all(); 
        $roles = ['admin','teacher'];
        return view('admin.teacher-record.teachers_list',compact('users','roles'));  
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,',
            'password'=> 'required|string|min:8',
        ]);
        User::create([
                        'name' => $validatedData['name'],
                        'email' => $validatedData['email'],
                        'password' => Hash::make($validatedData['password']),   
                    ]);
        return redirect()->route('users.index')->with(['success' => 'User added']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    { 
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email'=> 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'=> 'required|string|min:8',
        ]);

        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password'])
        ]);
        return redirect()->route('users.index')->with(['success' => 'User Edited']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with(['success' => 'User Deleted']);
    }
}
