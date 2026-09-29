<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    protected $redirectTo = '/';

    protected function redirectTo()
    {
        if(Auth::user() && Auth::user()->role_id == '1'){
            return redirect()->intended('admin/dashboard');
        }elseif(Auth::user() && Auth::user()->role_id == '2'){
            return redirect()->intended('user/dashboard-user');
        }
    }
    
    public function index()
    {
        return view('auth.login',[
            'title' => 'Login'
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials))
        {
            $request->session()->regenerate();
            return $this->redirectTo();
        }

        return back()->with('loginError', 'Login Failed!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
