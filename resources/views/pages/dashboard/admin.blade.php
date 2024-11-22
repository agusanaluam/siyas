<?php $page = 'index'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-widget dash1 w-100">
                        <div class="dash-widgetimg">
                            <span><img src="{{ URL::asset('/build/img/icons/dash2.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>Rp <span class="counters" data-count="{{ $statistic['totalPenerimaan'] }}">@currency($statistic['totalPenerimaan'])</span></h5>
                            <h6>Total Receipt Donation</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-widget w-100">
                        <div class="dash-widgetimg">
                            <span><img src="{{ URL::asset('/build/img/icons/dash1.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>Rp <span class="counters" data-count="{{ $statistic['totalPenerimaanRekening'] }}">@currency($statistic['totalPenerimaanRekening'])</span></h5>
                            <h6>Total Receipt Transfer</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-widget dash2 w-100">
                        <div class="dash-widgetimg">
                            <span><img src="{{ URL::asset('/build/img/icons/dash3.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>Rp <span class="counters" data-count="{{ $statistic['potensiPenerimaanCash'] }}">@currency($statistic['potensiPenerimaanCash'])</span></h5>
                            <h6>Cash Receipt Potential</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-widget dash3 w-100">
                        <div class="dash-widgetimg">
                            <span><img src="{{ URL::asset('/build/img/icons/dash4.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>Rp <span class="counters" data-count="{{ $statistic['potensiPenerimaanRekening'] }}">@currency($statistic['potensiPenerimaanRekening'])</span></h5>
                            <h6>Unverified Transfer</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count">
                        <div class="dash-counts">
                            <h4>{{ $statistic['jmlUniqDonatur'] }}</h4>
                            <h5>Donor</h5>
                        </div>
                        <div class="dash-imgs">
                            <i data-feather="user"></i>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count das1">
                        <div class="dash-counts">
                            <h4>{{ $statistic['jmlKupon'] }}</h4>
                            <h5>Coupon</h5>
                        </div>
                        <div class="dash-imgs">
                            <i data-feather="file"></i>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count das2">
                        <div class="dash-counts">
                            <h4>{{ $statistic['jmlProgramAktif'] }}</h4>
                            <h5>Active Campaign</h5>
                        </div>
                        <div class="dash-imgs">
                            <img src="{{ URL::asset('/build/img/icons/file-text-icon-01.svg') }}" class="img-fluid"
                                alt="icon">
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count das3">
                        <div class="dash-counts">
                            <h4>{{ $statistic['jmlRelawan'] }}</h4>
                            <h5>Volunteer</h5>
                        </div>
                        <div class="dash-imgs">
                            <i data-feather="user-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Button trigger modal -->

            <div class="row">
                <div class="col-xl-4 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill default-cover mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Donation Progress</h4>
                            <div class="view-all-link">
                                <a href="{{ route('donation.list') }}" class="view-all d-flex align-items-center">
                                    View All<span class="ps-2 d-flex align-items-center"><i data-feather="arrow-right"
                                            class="feather-16"></i></span>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dataview">
                                <table class="table table-borderless recent-transactions">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Campaign</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($campaignProgress as $key => $campaign)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>
                                                <div class="product-info">
                                                    <a href="{{ route('campaign.details',$campaign->id) }}" class="product-img">
                                                        @if (count($campaign->image) > 0)
                                                            <img src="{{ URL::asset('/storage/campaign_pictures/resized_'.$campaign->image[0]->picture_path) }}" alt="product">
                                                        @else
                                                            <img src="{{ URL::asset('/build/img/avatar/avatar-1.jpg') }}" alt="product">
                                                        @endif
                                                    </a>
                                                    <div class="info">
                                                        <a href="{{ route('campaign.details',$campaign->id) }}">{{ $campaign->name }}</a>
                                                        @switch($campaign->status)
                                                            @case(2)
                                                                <span class="dull-text d-flex align-items-center text-green">Running</span>
                                                                @break
                                                            @case(3)
                                                                <span class="dull-text d-flex align-items-center text-red">Closed</span>
                                                                @break
                                                            @default
                                                                <span class="dull-text d-flex align-items-center text-warning">Pending</span>
                                                                @break

                                                        @endswitch
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="progress progress-xl mb-3 progress-animate custom-progress-4 info" role="progressbar" aria-valuenow="{{ round(floatval($campaign->total_amount/$campaign->target_amount)*100,2) }}" aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar bg-info" style="width: {{ round(floatval($campaign->total_amount/$campaign->target_amount)*100,2) }}%"></div>
                                                        <div class="progress-bar-label">{{ round(floatval($campaign->total_amount/$campaign->target_amount)*100,2) }}%</div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill default-cover w-100 mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Recent Donation</h4>
                            <div class="dropdown">
                                <a href="{{ route('donation.history') }}" class="view-all d-flex align-items-center">
                                    View All<span class="ps-2 d-flex align-items-center"><i data-feather="arrow-right"
                                            class="feather-16"></i></span>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless recent-transactions">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Campaign</th>
                                            <th>Donatur Name</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Volunteer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentDonation as $key=>$donation)

                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>
                                                <div class="product-info">
                                                    <a href="{{ route('campaign.details',$donation->campaign->id) }}" class="product-img">
                                                        @if (count($donation->campaign->image) > 0)
                                                            <img src="{{ URL::asset('/storage/campaign_pictures/'.$donation->campaign->image[0]->picture_path) }}" alt="product">
                                                        @else
                                                            <img src="{{ URL::asset('/build/img/avatar/avatar-1.jpg') }}" alt="product">
                                                        @endif
                                                    </a>
                                                    <div class="info">
                                                        <a href="{{ route('campaign.details',$donation->campaign->id) }}">{{ $donation->campaign->name }}</a>
                                                        <span class="dull-text d-flex align-items-center"><i
                                                                data-feather="file" class="feather-14"></i>{{ $donation->donation->liq_number }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="d-block head-text">{{ $donation->donation->donatur_name }}</span>
                                                <span class="text-blue">{{ $donation->donation->donatur_phone }}</span>
                                            </td>
                                            <td><span class="badge bg-outline-success">{{ $donation->donation->status }}</span></td>
                                            <td>@currency($donation->amount)</td>
                                            <td>{{ (isset($donation->donation->volunteer))? $donation->donation->volunteer->name : "" }}</td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row sales-board">
                <div class="col-md-12 col-lg-12 col-sm-12 col-12">
                    <div class="card flex-fill default-cover">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Donation Analytics</h5>
                            <div class="row">
                                <div class="col-6">
                                    <select class="form-select" id="category">
                                        <option value="all">All</option>
                                        @foreach ($category as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select class="form-select" id="range">
                                        <option value="today">Today</option>
                                        <option value="last_7_days">Last 7 Days</option>
                                        <option value="last_30_days" selected>Last 30 Days</option>
                                        <option value="last_3_month">Last 3 Month</option>
                                        <option value="last_year">Last Year</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="donation-charts" class="chart-set"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Volunteer Leaderboards</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive dataview">
                        <table class="table" id="volunteerLeaderboardTable">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        #
                                    </th>
                                    <th>Group</th>
                                    <th>Name</th>
                                    <th>Campaign Contrib.</th>
                                    <th>Total Receipt</th>
                                    <th>Total Coupon</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
