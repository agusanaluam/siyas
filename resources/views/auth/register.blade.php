<?php $page = 'register'; ?>
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
                <form action="{{ route('register.user') }}" method="POST">
                    @csrf
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
                                    {{ $errors[0] }}
                            </div>
                        </div>
                    @endif
                    <div class="login-userset">
                        <div class="login-userheading">
                            <h3>Register</h3>
                            <h4>Create New Dreamspos Account</h4>
                        </div>
                        <div class="form-login">
                            <label>Name</label>
                            <div class="form-addons">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nama Lengkap">
                                <img src="{{ URL::asset('/build/img/icons/user-icon.svg') }}" alt="img">
                            </div>
                            <div class="text-danger pt-2">
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="form-login">
                            <label>Group / Communiry</label>
                            <div class="form-addons">
                                <select id="group" name="group" class="form-control select select2-hidden-accessible" required>
                                    <option value="" selected disabled hidden>--Pilih--</option>
                                    @foreach ($group as $grup)
                                        <option value="{{ $grup->id }}">{{ $grup->name }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger pt-2">
                                    @error('group')
                                        {{$message}}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-login">
                            <label>Email Address</label>
                            <div class="form-addons">
                                <input type="text" class="form-control" id="email" name="email" placeholder="user@email.com">
                                <img src="{{ URL::asset('/build/img/icons/mail.svg') }}" alt="img">
                            </div>
                            <div class="text-danger pt-2">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="form-login">
                            <label>Password</label>
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
                            <label>Confirm Passworrd</label>
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
                        <div class="form-login authentication-check">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="custom-control custom-checkbox justify-content-start">
                                        <div class="custom-control custom-checkbox">
                                            <label class="checkboxs ps-4 mb-0 pb-0 line-height-1">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>I agree to the <a href="#"
                                                    class="hover-a">Terms & Privacy</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-login">
                            <button type="submit" class="btn btn-login">Sign Up</button>
                        </div>
                        <div class="signinform">
                            <h4>Already have an account ? <a href="{{ route('login') }}" class="hover-a">Sign In
                                    Instead</a></h4>
                        </div>
                        <div class="form-setlogin or-text">
                            <h4>OR</h4>
                        </div>
                        <div class="form-sociallink">
                            <ul class="d-flex">
                                <li>
                                    <a href="javascript:void(0);" class="facebook-logo">
                                        <img src="{{ URL::asset('/build/img/icons/facebook-logo.svg') }}" alt="Facebook">
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
                        </div>
                    </div>
                </form>
            </div>
            <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                <p>Copyright &copy; 2023 DreamsPOS. All rights reserved.</p>
            </div>
        </div>
    </div>
@endsection
