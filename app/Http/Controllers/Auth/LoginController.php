<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    protected function authenticated(Request $request, $user){

        // Check if the user is authenticated
    if (Auth::check()) {
        if ($user->role_id) {
            // 1 => admin, 2 => seller
            switch ($user->role_id) {
                case 1:
                    return redirect()->route('admin');
                    break;
                case 2:
                    return redirect()->route('seller');
                    break;
                default:
                    Auth::logout();
                    return back()->with('warning', 'Some credentials are wrong, contact administrator.');
                    break;
            }
        } else {
            Auth::logout();
            return back()->with('warning', 'Credentials are not valid, contact administrator.');
        }
    } else {
        // User is not logged in, handle this case accordingly
        // For example, you can redirect them to the login page or return an error message
        return redirect()->route('login')->with('warning', 'You are not logged in.');
    }
}

public function __construct()
{
    $this->middleware('guest')->except('logout');
}

/* protected function credentials(\Illuminate\Http\Request $request)
{
    return ['email' => $request->email, 'password' => $request->password, 'status' => 0];
} */
}
