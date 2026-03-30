<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User;

class ChangePasswordController extends Controller
{
    public function index()
    {

      return view('change-password');
    }

    public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'password'         => 'required|string|min:6|confirmed',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

  /*   $user->update([
        'password' => Hash::make($request->password),
    ]); */

    $user->password = Hash::make($request->password);
    $user->save();


    Auth::logout();

    return redirect()->route('login')->with('success', 'Password changed successfully. Please log in again.');
}


}
