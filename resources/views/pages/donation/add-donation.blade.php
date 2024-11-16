<?php $page = 'add-donation'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper pos-pg-wrapper ms-0">
        <div class="content pos-design p-0">
            <div class="btn-row d-sm-flex align-items-center">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-xs-3"><span class="me-1 d-flex align-items-center"><i data-feather="arrow-left"
                            class="feather-16"></i></span>Back to Dashboard</a>
                <a href="javascript:void(0);" class="btn btn-info" onclick="location.reload()"><span class="me-1 d-flex align-items-center"><i
                            data-feather="rotate-cw" class="feather-16"></i></span>Reset</a>
                <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recents"><span
                        class="me-1 d-flex align-items-center"><i data-feather="refresh-ccw"
                            class="feather-16"></i></span>Transaction</a>
            </div>

            <div class="row align-items-start pos-wrapper">
                <div class="col-md-12 col-lg-8">
                    <div class="pos-categories tabs_wrapper">
                        <h5>Campaigns</h5>
                        <p>Select From Below Campaign</p>

                        <div class="pos-products">
                            <div class="tabs_container">
                                <div class="tab_content active" data-tab="all">
                                    <div class="row">
                                        @foreach ($campaign as $cmpg)
                                        <div class="col-sm-2 col-md-6 col-lg-3 col-xl-3">
                                            <div class="product-info default-cover card" data-campaign="{{ json_encode($cmpg) }}">
                                                <a href="javascript:void(0);" class="img-bg" id="{{ $cmpg->id }}">
                                                    <img src="{{ URL::asset('storage/campaign_pictures/resized_'.$cmpg->image[0]->picture_path) }}"
                                                        alt="Products">
                                                    <span><i data-feather="check" class="feather-16"></i></span>
                                                </a>
                                                <hr/>
                                                <h6 class="cat-name"><a href="javascript:void(0);">{{ $cmpg->category->name }}</a></h6>
                                                <h6 class="product-name"><a href="javascript:void(0);">{{ $cmpg->name }}</a>
                                                </h6>
                                                <div class="justify-content-between price">
                                                    <span>Target Donasi :</span>
                                                    <p>@currency($cmpg->target_amount)</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4 ps-0">
                    <form id="donationForm">
                        @csrf
                        @method('POST')
                        <aside class="product-order-list">
                            <div class="head d-flex align-items-center justify-content-between w-100">
                                <div class="">
                                    <h5>Donation List</h5>
                                    <span>Transaction ID : {{ $liq }}</span>
                                    <input type="hidden" name="liq_number" value="{{ $liq }}" />
                                </div>
                            </div>
                            <div class="customer-info block-section">
                                <h6>Donatur Information</h6>
                                <div class="input-block">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="donatur_name" placeholder="Donatur Name" required/>
                                </div>
                                <div class="input-block">
                                    <label>Phone number</label>
                                    <input type="text" class="form-control" name="donatur_phone" placeholder="081xxxxxx" required/>
                                </div>
                                <div class="input-block">
                                    <label>Address</label>
                                    <textarea class="form-control" name="donatur_address" placeholder="Donatur address"></textarea>
                                </div>
                            </div>

                            <div class="product-added block-section">
                                <div class="head-text d-flex align-items-center justify-content-between">
                                    <h6 class="d-flex align-items-center mb-0">Campaign Selected<span class="count" id="count-selected">0</span></h6>
                                    <a href="javascript:void(0);" class="d-flex align-items-center text-danger" id="clear-cart"><span
                                            class="me-1"><i data-feather="x" class="feather-16"></i></span>Clear all</a>
                                </div>
                                <div class="product-wrap" id="campaign-list">

                                </div>
                            </div>
                            <div class="block-section">
                                <div class="customer-info">
                                    <label>Reference Code (optional)</label>
                                    <div class="input-block d-flex align-item-center">
                                        <div class="flex-grow-1">
                                            <input class="form-control" type="text" name="reference_code" placeholder="Diisi kode pembayaran">
                                            <input type="file" style="display: none;" id="reference_picture" name="reference_picture" />
                                        </div>
                                        <a href="javascript:void(0);" class="btn btn-primary btn-icon" onclick="$('#reference_picture').click();" ><i data-feather="upload" class="feather-16"></i></a>
                                    </div>
                                    <div class="input-block">
                                        <label>Description</label>
                                        <textarea class="form-control" name="description" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="order-total">
                                    <table class="table table-responsive table-borderless">
                                        <tr>
                                            <td class="text-center">Total Donation:</td>
                                        </tr>
                                        <tr>
                                            <input type="hidden" name="total_amount" value="" />
                                            <td class="text-center"><h3 id="total-amount">Rp 0,00</h3></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="block-section settings-page-wrap">
                                <h6>Payment Method (choose one)</h6>
                                <input type="hidden" name="payment_method" value="" required/>
                                <div class="appearance-settings">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="theme-type-images align-items-center mb-4">
                                                <div class="theme-image payment_method" id="cash">
                                                    <div class="theme-image-set">
                                                        <img src="{{ URL::asset('/build/img/icons/cash-pay.svg')}}" alt="Payment Method">
                                                    </div>
                                                    <span>Cash</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="theme-type-images align-items-center mb-4">
                                                <div class="theme-image payment_method" id="transfer">
                                                    <div class="theme-image-set">
                                                        <img src="{{ URL::asset('/build/img/icons/credit-card.svg')}}" alt="Payment Method">
                                                    </div>
                                                    <span>Transfer</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid btn-block">
                                <a class="btn btn-secondary" id="btnSubmit" href="javascript:void(0);">
                                    Save Donation
                                </a>
                            </div>
                        </aside>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
