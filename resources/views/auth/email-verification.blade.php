<?php $page = 'email-verification'; ?>
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
                @if (session('success'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="feather-check-circle flex-shrink-0 me-2"></i>
                            <div>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="feather-alert-octagon flex-shrink-0 me-2"></i>
                            <div>
                                    {{ $errors }}
                            </div>
                        </div>
                    @endif
                <div class="login-userset">
                    <a href="{{ route('index') }}" class="login-logo logo-white">
                        <img src="{{ URL::asset('/build/img/logo-white.png') }}" alt="">
                    </a>
                    <div class="login-userheading text-center">
                        <h3>Verifikasi Email Kamu</h3>
                        <h4 class="verfy-mail-content">Kami telah mengirimkan email verifikasi ke email anda : {{ $email }}. Silahkan ikuti petunjuk berikutnya pada email.</h4>
                    </div>
                    <div class="signinform text-center">
                        <h4>Tidak menerima pesan? <a href="javascript:void(0);" class="hover-a resend" onclick="$('#resend-verify-form').submit()">Kirim ulang Link</a>
                        </h4>
                    </div>
                    <div class="form-login">
                        <a class="btn btn-login" href="{{ route('index') }}">Lewati Dulu</a>
                    </div>
                </div>
                <form action="{{ route('email-verification.resend') }}" method="POST" id="resend-verify-form" style="display: none;">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                </form>

            </div>
            <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                <p>Copyright &copy; {{ date('Y') }} InsanTeknoSejahtera. All rights reserved</p>
            </div>
        </div>
    </div>
@endsection
