<?php $page = 'profile'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Profile</h4>
                    <h6>User Profile</h6>
                </div>
            </div>
            <!-- /product list -->
            <div class="card">
                <div class="card-body">
                    <form id="formProfile" enctype="multipart/form-data">
                        @csrf
                        <div class="profile-set">
                            <div class="profile-head">

                            </div>
                            <div class="profile-top">
                                <div class="profile-content">
                                    <div class="profile-contentimg">
                                        <img src="{{ (isset($user->profile_picture))? URL::asset('storage/'.$user->profile_picture) : URL::asset('build/img/avatar/avatar-1.jpg') }}" alt="img"
                                            id="blah">
                                        <div class="profileupload">
                                            <input type="file" id="imgInp" class="form-control" name="profile_picture">
                                            <a href="javascript:void(0);"><img
                                                    src="{{ URL::asset('/build/img/icons/edit-set.svg') }}" alt="img"></a>
                                        </div>
                                    </div>
                                    <div class="profile-contentname">
                                        <h2>{{ $user->name }} | {{ $user->level }}</h2>
                                        <h4>Updates Your Photo and Personal Details.</h4>
                                    </div>
                                </div>
                                <!-- <div class="ms-auto">
                                    <a href="javascript:void(0);" class="btn btn-submit me-2">Save</a>
                                    <a href="javascript:void(0);" class="btn btn-cancel">Cancel</a>
                                </div> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-blocks">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ $user->email }}" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">NIK KTP*</label>
                                    <input type="text" class="form-control" name="nik" value="{{ $user->nik }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Nama Lengkap*</label>
                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Nomor Telepon*</label>
                                    <input type="text" class="form-control" name="phone_number" value="{{ $user->phone_number }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sex" value="L" id="flexRadioDefault1" @if($user->sex == 'L') checked="true" @endif>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Laki-laki
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sex" value="P" id="flexRadioDefault2" @if($user->sex == 'P') checked="true" @endif>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Perempuan
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="text" class="datetimepicker form-control" name="birth_date" placeholder="Choose" value="{{ $user->birth_date }}" />
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Provinsi</label>
                                    <select id="provinsi" name="provinsi" class="form-select">
                                        @foreach($provinsi as $prov)
                                            <option value="{{ $prov->code }}">{{ $prov->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Kota/ Kab.</label>
                                    <select id="kota" name="kota" class="form-select">
                                        @if($user->kota != "")
                                            <option >{{ $user->kota }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Kecamatan</label>
                                    <select id="kecamatan" name="kecamatan" class="form-select">
                                        @if($user->kecamatan != "")
                                            <option>{{ $user->kecamatan }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Kelurahan</label>
                                    <select id="desa" name="address_code" class="form-select">
                                        @if($user->desa != "")
                                            <option value="{{ $user->address_code }}" >{{ $user->desa }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Alamat</label>
                                    <input type="text" name="address" class="form-control" value="{{ $user->address }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <a href="javascript:void(0);" id="submitUpdate" class="btn btn-submit me-2">Update</a>
                                <a href="javascript:void(0);" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>

@endsection
