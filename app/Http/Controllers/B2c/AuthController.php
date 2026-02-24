<?php

namespace App\Http\Controllers\B2c;

use App\Http\Controllers\Controller;
use App\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show customer login form
     */
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->user_type == UserType::B2C->value) {
            return redirect()->route('b2c.account');
        }
        return view('b2c.auth.login');
    }

    /**
     * Process customer login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (
            Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
                'user_type' => UserType::B2C->value,
            ], $request->remember)
        ) {
            $request->session()->regenerate();
            return redirect()->intended(route('b2c.account'));
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput();
    }

    /**
     * Show customer registration form
     */
    public function showRegister()
    {
        if (Auth::check() && Auth::user()->user_type == UserType::B2C->value) {
            return redirect()->route('b2c.account');
        }
        return view('b2c.auth.register');
    }

    /**
     * Process customer registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'user_type' => UserType::B2C->value,
        ]);

        Auth::login($user);

        return redirect()->route('b2c.home')
            ->with('success', 'Welcome! Your account has been created successfully.');
    }

    /**
     * Logout customer
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('b2c.home');
    }
}
