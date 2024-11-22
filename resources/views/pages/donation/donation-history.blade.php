<?php $page = 'donation-history'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Donation History
                @endslot
                @slot('li_1')
                    All Campaign Donations
                @endslot
            @endcomponent

            <!-- /product list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                        <div class="search-path">
                            <div class="d-flex align-items-center">
                                <a class="btn btn-filter" id="filter_search">
                                    <i data-feather="filter" class="filter-icon"></i>
                                    <span><img src="{{ URL::asset('/build/img/icons/closes.svg') }}" alt="img"></span>
                                </a>

                            </div>

                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="user" class="info-img"></i>
                                        <select class="select">
                                            <option>Choose Customer Name</option>
                                            <option>Macbook pro</option>
                                            <option>Orange</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="stop-circle" class="info-img"></i>
                                        <select class="select">
                                            <option>Choose Status</option>
                                            <option>Computers</option>
                                            <option>Fruits</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="file-text" class="info-img"></i>
                                        <input type="text" placeholder="Enter Reference" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="stop-circle" class="info-img"></i>
                                        <select class="select">
                                            <option>Choose Payment Status</option>
                                            <option>Computers</option>
                                            <option>Fruits</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <a class="btn btn-filters ms-auto"> <i data-feather="search"
                                                class="feather-search"></i> Search </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table" id="donationHistoryTable">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Donation Name</th>
                                    <th>Donor Name</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Volunteer</th>
                                </tr>
                            </thead>
                            <tbody class="sales-list">
                                @foreach ($data as $row)
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox" value="{{ $row->id }}">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td class="productimgname">
                                            <div class="view-product me-2">
                                                <a href="javascript:void(0);">
                                                    @if (count($row->campaign->image) > 0)
                                                            <img src="{{ URL::asset('/storage/campaign_pictures/resized_'.$row->campaign->image[0]->picture_path) }}" alt="product">
                                                        @else
                                                            <img src="{{ URL::asset('/build/img/avatar/avatar-1.jpg') }}" alt="product">
                                                        @endif
                                                </a>
                                            </div>
                                            <a href="javascript:void(0);">{{ $row->campaign->name }}</a>
                                        </td>
                                        <td>
                                            {{ $row->donation->donatur_name }}
                                        </td>
                                        <td>
                                            @currency($row->amount)
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($row->donation->trans_date)) }}
                                        </td>
                                        <td>
                                            @if ($row->donation->via_transfer == 1)
                                                <span class="badge badge-md bg-info">Transfer</span>
                                            @else
                                                <span class="badge badge-md bg-secondary">Cash</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $row->donation->status }}
                                        </td>
                                        <td>
                                            @if (isset($row->donation->volunteer))
                                                {{ $row->donation->volunteer->name }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>
@endsection
@component('pages.donation.form-modal')

@endcomponent
