<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();
        return view('users.index', compact('users','roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function resetPassword(Request $request,string $id)
    {
        $validatedData = $request->validate([
            'password' => [
                'required',
                'string',
                'min:8', // At least 8 characters
                'regex:/^(?=.*[0-9])(?=.*[\W_]).{8,}$/', // At least one number and one special character
            ],
        ], [
            'password.min' => 'The new password must be at least 8 characters long.',
            'password.regex' => 'The new password must include at least one number and one special character.',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request['password']);
        $user->save();

        return redirect()->route('users.index')->with('success','Password Reseted.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required',
            // 'department_id' => 'required',
        ]);

        $firstName = ucwords($request['first_name']);
        $lastName = ucwords($request['last_name']);
        $passcode = strtoupper($request['last_name']).'@123';

        $user = new User();
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $request['email'];
        $user->password = Hash::make($passcode);
        $user->role_id = $request['role_id'];
        $user->status = 1;
        $user->save();

      //attch to pivot table
        $user->roles()->sync([$user->role_id]);

        return redirect()->route('users.index')->with('success','User added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function update(Request $request,string $id)
    {
        $user = User::findOrFail($id);
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id), // Ignore the current user's email
            ],
            'role_id' => 'required',
            // 'department_id' => 'required',
        ]);
        $firstName = ucwords($request['first_name']);
        $lastName = ucwords($request['last_name']);

        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $request['email'];
        $user->role_id = $request['role_id'];
        $user->save();

        $user->roles()->sync([$user->role_id]);

        return redirect()->route('users.index')->with('success','User Updated.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function deactivate(string $id)
    {
        $user = User::findOrFail($id);
        $user->status=0;
        $user->save();

        return redirect()->route('users.index')->with('success','User Deactivated.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function activate(string $id)
    {
        $user = User::findOrFail($id);
        $user->status=1;
        $user->save();

        return redirect()->route('users.index')->with('success','User Activated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ((int) auth()->id() === (int) $user->id) {
            return redirect()->route('users.index')->with('success', 'You cannot delete your own account.');
        }

        if ($user->isAdmin() && User::where('role_id', 1)->count() <= 1) {
            return redirect()->route('users.index')->with('success', 'You cannot delete the last administrator.');
        }

        $user->roles()->detach();
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
