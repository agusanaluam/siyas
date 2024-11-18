<?php $page = 'reset-password-3'; ?>
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
                <form action="{{ route('reset-password.verify') }}" method="POST">
                    @csrf
                    <div class="login-userset">
                        <div class="login-userheading">
                            <h3>Reset password?</h3>
                            <h4>Enter New Password & Confirm Password to get inside</h4>
                        </div>
                        <div class="form-login">
                            <label>Email Address</label>
                            <div class="form-addons">
                                <input type="text" class="form-control" id="email" name="email" value="{{ $email }}">
                                <input type="hidden" class="form-control" id="token" name="token" value="{{ $token }}">
                                <img src="{{ URL::asset('/build/img/icons/mail.svg') }}" alt="img">
                            </div>
                        </div>
                        <div class="form-login">
                            <label>New Password</label>
                            <div class="pass-group">
                                <input type="password" class="pass-input" id="password" name="password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                            <div class="text-danger pt-2">
                                @error('password')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="form-login">
                            <label> New Confirm Passworrd</label>
                            <div class="pass-group">
                                <input type="password" class="pass-inputs" id="confirmpassword" name="confirmpassword" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">
                                <span class="fas toggle-passwords fa-eye-slash"></span>
                            </div>
                            <div class="text-danger pt-2">
                                @error('confirmpassword')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="form-login">
                            <button type="submit" class="btn btn-login">Change Password</button>
                        </div>
                        <div class="signinform text-center">
                            <h4>Return to <a href="{{ url('signin-3') }}" class="hover-a"> login </a></h4>
                        </div>
                    </div>
                </form>

            </div>
            <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                <p>Copyright &copy; {{ date('Y') }} InsanTeknoSejahtera. All rights reserved.</p>
            </div>
        </div>
    </div>
@endsection
