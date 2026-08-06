<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherChart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{

    public function dashboard()
    {
        return view('admin.dashboard.index');
    }

    public function users()
    {
        $users = User::get();
        $otherChart = OtherChart::all();
        return view('admin.users.index',compact('users','otherChart'));
    }
    public function userStore(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
        if($request->id){
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
        }else{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'chart_id' => $request->other_chart_id,
            ]);
        }

        return redirect()->back();
    }

    public function userEdit($id){
        $user = User::find($id);
        $otherChart = OtherChart::all();
        return view('admin.users.edit-user',compact('user','otherChart'));

    }

    public function userDelete($id){
        $user = User::find($id);
        $user->delete();
        return redirect()->back();
    }
}
