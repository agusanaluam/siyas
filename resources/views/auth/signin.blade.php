<?php $page = 'login'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="account-content">
        <div class="login-wrapper login-new">
            <div class="container">
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
                    <form action="{{ route('login.user') }}" method="POST">
                        @csrf
                        <div class="login-userset">
                            <div class="login-userheading">
                                <h3>Sign In</h3>
                                <h4>Access the Volunteer panel using your email and passcode.</h4>
                            </div>
                            <div class="form-login">
                                <label class="form-label">Email Address</label>
                                <div class="form-addons">
                                    <input type="text" class="form- control" id="email" name="email" placeholder="user@email.com">
                                    <img src="{{ URL::asset('/build/img/icons/mail.svg') }}" alt="img">
                                </div>
                                <div class="text-danger pt-2">
                                    @error('0')
                                        {{ $message }}
                                    @enderror
                                    @error('email')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div class="form-login">
                                <label class="form-label">Password</label>
                                <div class="pass-group">
                                    <input type="password" class="pass-input form-control" id="password" name="password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">
                                    <span class="fas toggle-password fa-eye-slash"></span>
                                </div>
                                <div class="text-danger pt-2">
                                    @error('0')
                                        {{ $message }}
                                    @enderror
                                    @error('password')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div class="form-login authentication-check">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="custom-control custom-checkbox">
                                            <label class="checkboxs ps-4 mb-0 pb-0 line-height-1">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>Remember me
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <a class="forgot-link" href="{{ route('forgot-password') }}">Forgot Password?</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-login">
                                <button class="btn btn-login" type="submit">Sign In</button>
                            </div>
                            <div class="signinform">
                                <h4>New on our platform?<a href="{{ route('register') }}" class="hover-a"> Create an
                                        account</a></h4>
                            </div>
                            {{-- <div class="form-setlogin or-text">
                                <h4>OR</h4>
                            </div>
                            <div class="form-sociallink">
                                <ul class="d-flex">
                                    <li>
                                        <a href="javascript:void(0);" class="facebook-logo">
                                            <img src="{{ URL::asset('/build/img/icons/facebook-logo.svg') }}"
                                                alt="Facebook">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <img src="{{ URL::asset('/build/img/icons/google.png') }}" alt="Google">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="apple-logo">
                                            <img src="{{ URL::asset('/build/img/icons/apple-logo.svg') }}" alt="Apple">
                                        </a>
                                    </li>
                                </ul>
                            </div> --}}
                        </div>
                    </form>

                </div>
                <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                    <p>Copyright &copy; {{ date('Y') }} InsanTeknoSejahtera. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
