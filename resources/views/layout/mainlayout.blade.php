<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="InsanTeknoSejahtera - SIYAS Admin">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CahayaAyahBunda :: Dashboard</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('/build/img/favicon.png')}}">

    @include('layout.partials.head')
</head>
@if (Route::is(['chat']))

    <body class="main-chat-blk">
@endif
@if (!Route::is(['chat', 'under-maintenance', 'coming-soon', 'error-404', 'error-500','two-step-verification-3','two-step-verification-2','two-step-verification','email-verification-send','email-verification-2','email-verification','reset-password.verify','reset-password-2','reset-password','forgot-password.verify','forgot-password-2','forgot-password','register-3','register-2','register','login','signin-2','signin','success','success-2','success-3']))

    <body>
@endif
@if (Route::is(['under-maintenance', 'coming-soon', 'error-404', 'error-500']))

    <body class="error-page">
@endif
@if (Route::is(['two-step-verification-3','two-step-verification-2','two-step-verification','email-verification-send','email-verification-2','email-verification','reset-password.verify','reset-password-2','reset-password','forgot-password.verify','forgot-password-2','forgot-password','register-3','register-2','register','login','signin-2','signin','success','success-2','success-3']))

    <body class="account-page">
@endif
@component('components.loader')
@endcomponent
<!-- Main Wrapper -->
@if (!Route::is(['lock-screen']))
    <div class="main-wrapper">
@endif
@if (Route::is(['lock-screen']))
    <div class="main-wrapper login-body">
@endif
@if (!Route::is(['under-maintenance', 'coming-soon','error-404','error-500','two-step-verification-3','two-step-verification-2','two-step-verification','email-verification-send','email-verification-2','email-verification','reset-password.verify','reset-password-2','reset-password','forgot-password.verify','forgot-password-2','forgot-password','register-3','register-2','register','login','signin-2','signin','success','success-2','success-3','lock-screen']))
    @include('layout.partials.header')
@endif
@if (!Route::is(['donation.create','pos', 'under-maintenance', 'coming-soon','error-404','error-500','two-step-verification-3','two-step-verification-2','two-step-verification','email-verification-send','email-verification-2','email-verification','reset-password.verify','reset-password-2','reset-password','forgot-password.verify','forgot-password-2','forgot-password','register-3','register-2','register','login','signin-2','signin','success','success-2','success-3','lock-screen']))
    @include('layout.partials.sidebar')
    @include('layout.partials.collapsed-sidebar')
    @include('layout.partials.horizontal-sidebar')
@endif

@yield('content')
@component('components.toast')
@endcomponent
</div>
<!-- /Main Wrapper -->
<script>
    const BASE_URL = "{{ url('/') }}";
</script>
{{-- @include('layout.partials.theme-settings') --}}
@include('layout.partials.footer-scripts')
@if (Auth::check())
@vite('resources/js/app.js')
@endif
</body>

</html>
