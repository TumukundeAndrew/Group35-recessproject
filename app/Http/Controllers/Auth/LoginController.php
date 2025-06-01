<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'role' => 'required|string|in:admin,supplier,vendor,manufacturer,wholesaler,retailer,customer',
        ], [
            'role.in' => 'The selected role is invalid.',
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        // Update user's role if it's different from the selected role
        if ($user->role !== $request->role) {
            $user->role = $request->role;
            $user->save();
        }
        
        return redirect()->route('dashboard', ['role' => $user->role]);
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard', ['role' => Auth::user()->role]);
        }
        return view('auth.login');
    }
}
