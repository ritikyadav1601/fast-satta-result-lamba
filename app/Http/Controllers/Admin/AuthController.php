<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('admin.auth.login');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            if($request->email == 'admin@fast-satta-result.com') {
                return redirect()->route('admin.dashboard');
            }else{
                return redirect()->route('other.game.result');
            }
        }
        return redirect()->route('admin.login')->with('error', 'Invalid credentials');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('admin.login');
    }

    public function changePassword()
    {
        return view('admin.auth.change-password');
    }

    public function changePasswordSubmit(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required'],
        ]);

        if(Hash::check($request->current_password, auth()->user()->password)){
            $user = auth()->user();
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('admin.change.password')->with('success', 'Password changed successfully');
        }
        return redirect()->route('admin.change.password')->with('error', 'Current password is incorrect');
    }
}
