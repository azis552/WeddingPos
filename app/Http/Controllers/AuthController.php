<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function Register()
    {
        return view('register');
    }

    public function RegisterPost(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $simpan = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        if ($simpan) 
        {
            Profil::create([
                'users_id' => $simpan->id
            ]);
            return redirect()->route('login')->with('success', 'Register Berhasil');
        }
        else
        {
            return redirect()->route('register')->with('error', 'Register Gagal');
        }
    }

    public function LoginPost(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if(Auth::attempt($validate))
        {
            return redirect()->route('dashboard')->with('success', 'Login Berhasil');
        }
        else
        {
            return redirect()->route('login')->with('error', 'Login Gagal');
        }
    }

    public function Logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout Berhasil');
    }

}
