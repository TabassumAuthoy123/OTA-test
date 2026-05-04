<?php

namespace App\Http\Controllers\B2c;

use App\Http\Controllers\Controller;
use App\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Helpers\EmailHelper;
use App\Mail\WelcomeEmail;
use App\Mail\OtpEmail;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect($this->redirectAfterLogin());
        }
        return view('b2c.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();
            return redirect($this->redirectAfterLogin());
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }

    private function redirectAfterLogin(): string
    {
        $type = Auth::user()->user_type;
        if ($type == UserType::B2C->value) return route('b2c.account');
        if ($type == UserType::B2B->value) return route('home');
        return url('/home');
    }

    // ── Register ──────────────────────────────────────────────────────────────

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect($this->redirectAfterLogin());
        }
        return view('b2c.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'phone'      => 'required|string|max:20',
            'gender'     => 'nullable|in:male,female,other',
            'password'   => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'      => trim($request->first_name . ' ' . $request->last_name),
            'email'     => $request->email,
            'phone'     => $request->phone,
            'gender'    => $request->gender,
            'password'  => Hash::make($request->password),
            'user_type' => UserType::B2C->value,
        ]);

        Auth::login($user);
        EmailHelper::send($user->email, new WelcomeEmail($user));

        return redirect()->route('b2c.home')
            ->with('success', 'Welcome! Your account has been created successfully.');
    }

    // ── Forgot Password (OTP flow) ────────────────────────────────────────────

    public function showForgotPassword()
    {
        return view('b2c.auth.forgot_password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)
            ->where('user_type', UserType::B2C->value)
            ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with that email.'])->withInput();
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->upsert(
            [['email' => $request->email, 'token' => $otp, 'created_at' => now()]],
            ['email'],
            ['token', 'created_at']
        );

        EmailHelper::send($request->email, new OtpEmail($user->name, $otp));

        return redirect()->route('b2c.verify.otp', ['email' => $request->email])
            ->with('otp_sent', true);
    }

    public function showVerifyOtp(Request $request)
    {
        $email = $request->query('email');
        if (!$email) return redirect()->route('b2c.password.request');
        return view('b2c.auth.verify_otp', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'otp'                   => 'required|digits:6',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $row = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$row || $row->token !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.'])->withInput();
        }

        if (Carbon::parse($row->created_at)->addMinutes(15)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['otp' => 'OTP expired. Please request a new one.'])->withInput();
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('b2c.login')
            ->with('status', 'Password reset successfully. You can now log in.');
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('b2c.home');
    }
}
