<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< Updated upstream
use Illuminate\Support\Facades\Auth;
=======
use Illuminate\Support\Facades\Validator;
>>>>>>> Stashed changes

class AuthController extends Controller
{

    //
    public function show(){
        return view('auth.login');
    }

    public function login_auth(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();

            return redirect()->intended('/home');
        }

        return back()->with([
            'error' => 'The provided credentials do not match our records.'
        ]);
    }
<<<<<<< Updated upstream
        public function logout(Request $request){
            Auth::logout();
        
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        
            return redirect()->route('login.show');
    }
}
=======
     
}
>>>>>>> Stashed changes
