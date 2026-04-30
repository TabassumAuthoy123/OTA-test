@extends('b2c.layouts.master')

@section('title', 'Sign In')

@section('styles')
    <style>
        .b2c-auth-page {
            min-height: 100vh;
            background: var(--b2c-gradient-hero);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 20px 60px;
        }

        .b2c-auth-card {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--b2c-radius-xl);
            padding: 48px 40px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .b2c-auth-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .b2c-auth-logo i {
            font-size: 2rem;
            color: var(--b2c-cta);
            margin-bottom: 8px;
        }

        .b2c-auth-logo h2 {
            font-family: var(--font-heading);
            font-size: 1.5rem;
            color: #fff;
            font-weight: 700;
        }

        .b2c-auth-logo p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .b2c-auth-field {
            margin-bottom: 20px;
        }

        .b2c-auth-field label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--b2c-accent-light);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .b2c-auth-field input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--b2c-radius-md);
            color: #fff;
            font-size: 0.95rem;
            font-family: var(--font-body);
            transition: var(--b2c-transition);
            outline: none;
        }

        .b2c-auth-field input:focus {
            border-color: var(--b2c-accent);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
        }

        .b2c-auth-field input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .b2c-pw-wrap {
            position: relative;
        }
        .b2c-pw-wrap input {
            padding-right: 44px !important;
        }
        .b2c-pw-eye {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255,255,255,.45);
            font-size: 15px;
            padding: 2px;
            transition: color .15s;
            line-height: 1;
        }
        .b2c-pw-eye:hover { color: rgba(255,255,255,.85); }

        .b2c-auth-remember {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .b2c-auth-remember label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .b2c-auth-submit {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: var(--b2c-radius-md);
            background: linear-gradient(135deg, var(--b2c-cta), #F97316);
            color: var(--b2c-primary);
            font-family: var(--font-heading);
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--b2c-transition);
        }

        .b2c-auth-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px var(--b2c-cta-glow);
        }

        .b2c-auth-links {
            text-align: center;
            margin-top: 24px;
        }

        .b2c-auth-links a {
            color: var(--b2c-accent-light);
            font-size: 0.9rem;
            text-decoration: none;
        }

        .b2c-auth-links a:hover {
            text-decoration: underline;
        }

        .b2c-auth-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #FCA5A5;
            padding: 12px 16px;
            border-radius: var(--b2c-radius-sm);
            font-size: 0.85rem;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="b2c-auth-page">
        <div class="b2c-auth-card">
            <div class="b2c-auth-logo">
                <i class="fas fa-plane-departure"></i>
                <h2>Welcome Back</h2>
                <p>Sign in to manage your bookings</p>
            </div>

            @if($errors->any())
                <div class="b2c-auth-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('b2c.login.submit') }}">
                @csrf

                <div class="b2c-auth-field">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required
                        autofocus>
                </div>

                <div class="b2c-auth-field">
                    <label>Password</label>
                    <div class="b2c-pw-wrap">
                        <input type="password" name="password" id="b2cPwInput" placeholder="Enter your password" required>
                        <button type="button" class="b2c-pw-eye" onclick="toggleB2cPw()" id="b2cPwEye" title="Show/hide password">
                            <i class="fas fa-eye" id="b2cPwIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="b2c-auth-remember">
                    <label>
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                </div>

                <button type="submit" class="b2c-auth-submit">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="b2c-auth-links">
                Don't have an account? <a href="{{ route('b2c.register') }}">Create Account</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function toggleB2cPw() {
    var inp  = document.getElementById('b2cPwInput');
    var icon = document.getElementById('b2cPwIcon');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>
@endsection