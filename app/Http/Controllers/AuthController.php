<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(Request $r)
    {
        $user = User::where('email',$r->email)->first();

        if(!$user || !Hash::check($r->password,$user->password)){
            return back()->with('error','Login gagal');
        }

        // CHECK if already active session - BLOCK new login
        if ($user->current_session_id !== null) {
            return back()->with('error', 'Akun ini sedang digunakan pada perangkat lain. Silakan gunakan perangkat yang sama atau hubungi admin.');
        }

        // Safe to login - no active session
        Auth::login($user);
        $r->session()->regenerate(true);
        $user->current_session_id = $r->session()->getId();
        $user->save();

        return redirect('/dashboard');
    }

    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            $user->current_session_id = null;
            $user->save();
        }
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
        return redirect('/');
    }
}

