<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Sign In') — FaithTrip</title>

    <link rel="shortcut icon" href="{{ url('assets') }}/img/favicon.svg"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ url('assets') }}/nanopkg-assets/vendor/fontawesome-free-6.3.0-web/css/all.min.css" rel="stylesheet"/>
    <link href="{{ url('assets') }}/admin-assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="{{ url('assets') }}/nanopkg-assets/vendor/toastr/build/toastr.min.css" rel="stylesheet"/>

    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', sans-serif;
        background: #f0f2f5;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px 16px;
    }

    /* ── Auth Card ── */
    .ft-auth-card {
        width: 100%;
        max-width: 900px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,.15);
        display: flex;
        min-height: 540px;
    }

    /* ── Left Panel ── */
    .ft-auth-left {
        flex: 1.1;
        position: relative;
        background: linear-gradient(160deg, #0D1B5E 0%, #1A3A8F 50%, #0a2d6e 100%);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 32px 36px 28px;
        overflow: hidden;
        min-width: 0;
    }
    .ft-auth-left::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=800&q=80') center/cover no-repeat;
        opacity: .28;
    }
    .ft-auth-left > * { position: relative; z-index: 1; }

    .ft-auth-left-logo {
        display: flex;
        align-items: center;
        gap: 9px;
        text-decoration: none;
    }
    .ft-auth-left-logo span {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: 1.4rem;
        line-height: 1;
    }
    .ft-auth-left-logo .faith { color: #fff; }
    .ft-auth-left-logo .trip  { color: #F5A623; }

    .ft-auth-left-body { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 28px 0 20px; }
    .ft-auth-left-body h2 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.85rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.25;
        margin-bottom: 12px;
    }
    .ft-auth-left-body h2 span { color: #F5A623; }
    .ft-auth-left-body p {
        color: rgba(255,255,255,.72);
        font-size: .9rem;
        line-height: 1.65;
        max-width: 320px;
    }

    .ft-auth-contact { display: flex; flex-direction: column; gap: 12px; }
    .ft-auth-contact-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .ft-auth-contact-item .label {
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        color: rgba(255,255,255,.5);
        text-transform: uppercase;
    }
    .ft-auth-contact-item .value {
        font-size: .92rem;
        font-weight: 700;
        color: #fff;
        text-decoration: none;
    }
    .ft-auth-contact-item .value:hover { color: #F5A623; }

    /* ── Right Panel ── */
    .ft-auth-right {
        flex: 1;
        background: #fff;
        display: flex;
        flex-direction: column;
        padding: 28px 40px 24px;
        min-width: 0;
    }
    .ft-auth-right-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
    }
    .ft-auth-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #555;
        font-size: .85rem;
        font-weight: 600;
        text-decoration: none;
        transition: color .15s;
    }
    .ft-auth-back-btn:hover { color: #0D1B5E; }
    .ft-auth-secure-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: .75rem;
        font-weight: 700;
        color: #16a34a;
    }

    .ft-auth-right-body { flex: 1; display: flex; flex-direction: column; justify-content: center; }
    .ft-auth-right-body h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.6rem;
        font-weight: 800;
        color: #111;
        margin-bottom: 6px;
    }
    .ft-auth-right-body .sub {
        color: #777;
        font-size: .88rem;
        margin-bottom: 28px;
    }

    /* Form fields */
    .ft-auth-form-group { margin-bottom: 18px; }
    .ft-auth-form-group label {
        display: block;
        font-size: .82rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
    }
    .ft-auth-form-group input {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid #e5e7eb;
        border-radius: 9px;
        font-size: .9rem;
        font-family: 'Inter', sans-serif;
        color: #111;
        background: #fafafa;
        transition: border-color .15s, box-shadow .15s;
        outline: none;
    }
    .ft-auth-form-group input:focus {
        border-color: #0D1B5E;
        box-shadow: 0 0 0 3px rgba(13,27,94,.08);
        background: #fff;
    }
    .ft-auth-form-group input::placeholder { color: #aaa; }

    /* Password wrapper */
    .ft-pw-wrap { position: relative; }
    .ft-pw-wrap input { padding-right: 44px; }
    .ft-pw-eye {
        position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
        background: none; border: none; cursor: pointer; color: #aaa; font-size: 14px; padding: 2px; transition: color .15s;
    }
    .ft-pw-eye:hover { color: #0D1B5E; }

    /* Forgot password row */
    .ft-auth-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
    }
    .ft-auth-row label {
        display: flex; align-items: center; gap: 6px;
        font-size: .83rem; color: #555; cursor: pointer;
    }
    .ft-auth-row label input { accent-color: #0D1B5E; }
    .ft-forgot-link { font-size: .83rem; font-weight: 600; color: #0D1B5E; text-decoration: none; }
    .ft-forgot-link:hover { color: #F5A623; }

    /* Submit button */
    .ft-auth-submit {
        width: 100%;
        padding: 13px;
        background: #0D1B5E;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s;
        letter-spacing: .3px;
    }
    .ft-auth-submit:hover { background: #1A3A8F; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(13,27,94,.25); }

    /* Error */
    .ft-auth-error {
        background: #fef2f2; border: 1px solid #fecaca;
        color: #dc2626; padding: 10px 14px; border-radius: 8px;
        font-size: .84rem; margin-bottom: 16px;
    }

    /* Register link */
    .ft-auth-switch {
        text-align: center;
        margin-top: 20px;
        font-size: .87rem;
        color: #777;
    }
    .ft-auth-switch a {
        color: #0D1B5E;
        font-weight: 700;
        text-decoration: none;
    }
    .ft-auth-switch a:hover { color: #F5A623; }

    /* Bottom copyright */
    .ft-auth-foot {
        text-align: center;
        margin-top: 20px;
        font-size: .78rem;
        color: #aaa;
    }

    /* Responsive */
    @media (max-width: 700px) {
        .ft-auth-left { display: none; }
        .ft-auth-card { max-width: 440px; border-radius: 16px; }
        .ft-auth-right { padding: 28px 24px 24px; }
        .ft-auth-right-body h3 { font-size: 1.35rem; }
    }
    </style>

    @yield('styles')
</head>
<body>
    @yield('content')

    <script src="{{ url('assets') }}/admin-assets/vendor/jQuery/jquery.min.js"></script>
    <script src="{{ url('assets') }}/nanopkg-assets/vendor/toastr/build/toastr.min.js"></script>
    @yield('scripts')
</body>
</html>
