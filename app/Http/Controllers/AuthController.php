<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $r)
    {
        $user = User::where('username',$r->username)->first();

        if(!$user || !Hash::check($r->password,$user->password)){
            return back()->with('error','Login gagal');
        }

        Auth::login($user);
        return redirect('/dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
