<?php $page = 'forgot-password-verification'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="account-content">
        <div class="login-wrapper login-new">
            <div class="login-content user-login">
                <div class="login-logo">
                    <img src="{{ URL::asset('/build/img/logo.png') }}" alt="img">
                    <a href="{{ url('index') }}" class="login-logo logo-white">
                        <img src="{{ URL::asset('/build/img/logo-white.png') }}" alt="">
                    </a>
                </div>
                <div class="login-userset">
                    <a href="{{ route('index') }}" class="login-logo logo-white">
                        <img src="{{ URL::asset('/build/img/logo-white.png') }}" alt="">
                    </a>
                    <div class="login-userheading text-center">
                        <h3>Cek Kotak masuk email kamu</h3>
                        <h4 class="verfy-mail-content">Kami telah mengirimkan pesan ke email anda : {{ $email }}. Silahkan ikuti petunjuk berikutnya pada email.</h4>
                    </div>
                    <div class="form-login">
                        <a class="btn btn-login" href="{{ route('index') }}">Lewati Dulu</a>
                    </div>
                </div>

            </div>
            <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                <p>Copyright &copy; {{ date('Y') }} InsanTeknoSejahtera. All rights reserved</p>
            </div>
        </div>
    </div>
@endsection
