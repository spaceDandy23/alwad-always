<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm(){

        return view("auth.login");

    }
    public function login(Request $request){    

        $validatedData = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ]);

        if (Auth::attempt($validatedData)){
            $user = Auth::user();
            
            if($user->role !=='admin' && $user->role !== 'teacher'){
                Auth::logout();
                throw ValidationException::withMessages(['email' =>'Invalid role']);
            }
            
            $request->session()->regenerate();
            return redirect()->route('rfid-reader.index');

            
        }
        throw ValidationException::withMessages(['email' => 'Credentials does not match records']);
    }
    public function logout(Request $request){   
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('rfid-reader.index');
    }
}
