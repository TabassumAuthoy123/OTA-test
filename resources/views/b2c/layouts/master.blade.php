<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Book Flights at Best Price') - {{ config('app.name', 'SkyTrip') }}</title>
    <meta name="description"
        content="@yield('meta_description', 'Book domestic and international flights at the best price. Instant confirmation, secure payment, 24/7 support.')" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ url('assets') }}/img/favicon.svg" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="{{ url('assets') }}/nanopkg-assets/vendor/fontawesome-free-6.3.0-web/css/all.min.css"
        rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="{{ url('assets') }}/admin-assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- B2C Stylesheet -->
    <link href="{{ url('assets') }}/b2c/css/b2c.css?v=2" rel="stylesheet" />
    <!-- FaithTrip Brand -->
    <link href="{{ url('assets') }}/b2c/css/faithtrip.css?v={{ time() }}" rel="stylesheet" />

    <!-- Toastr -->
    <link href="{{ url('assets') }}/nanopkg-assets/vendor/toastr/build/toastr.min.css" rel="stylesheet" />

    @yield('styles')
</head>

<body class="b2c-body">
    <!-- Navbar -->
    @include('b2c.layouts.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('b2c.layouts.footer')

    <!-- Right-side floating social icons -->
    @php
        $floatSocials = \Illuminate\Support\Facades\DB::table('social_media_links')
            ->whereIn('name', ['Facebook', 'Twitter', 'Instagram', 'YouTube'])
            ->orderBy('name')->get();
    @endphp
    @if($floatSocials->count())
    <div id="ftFloatSocial" style="position:fixed;right:0;top:50%;transform:translateY(-50%);z-index:999;display:flex;flex-direction:column;gap:0;">
        @foreach($floatSocials as $fs)
        @php
            $fsN = strtolower($fs->name);
            $fsIcon  = $fsN === 'facebook' ? 'fa-facebook-f' : ($fsN === 'twitter' ? 'fa-twitter' : ($fsN === 'instagram' ? 'fa-instagram' : 'fa-youtube'));
            $fsBg    = $fsN === 'facebook' ? '#1877F2' : ($fsN === 'twitter' ? '#1DA1F2' : ($fsN === 'instagram' ? '#E1306C' : '#FF0000'));
        @endphp
        <a href="{{ $fs->link ?? '#' }}" target="_blank" title="{{ $fs->name }}"
           style="width:38px;height:38px;background:{{ $fsBg }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:.88rem;text-decoration:none;transition:width .2s,opacity .2s;opacity:.85;border-radius:4px 0 0 4px;margin-bottom:2px;">
            <i class="fab {{ $fsIcon }}"></i>
        </a>
        @endforeach
    </div>
    <style>
    #ftFloatSocial a:hover { opacity: 1; width: 44px; }
    @media (max-width:576px) { #ftFloatSocial { display: none !important; } }
    </style>
    @endif

    <!-- Scripts -->
    <script src="{{ url('assets') }}/admin-assets/vendor/jQuery/jquery.min.js"></script>
    <script src="{{ url('assets') }}/admin-assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('assets') }}/nanopkg-assets/vendor/toastr/build/toastr.min.js"></script>

    <script>
        // CSRF setup for AJAX
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
    </script>

    @yield('scripts')
</body>

</html>