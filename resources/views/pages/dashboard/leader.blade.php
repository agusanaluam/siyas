<?php $page = 'sales-dashboard'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="welcome d-lg-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center welcome-text">
                    @if (!isset(Auth::user()->email_verified_at))
                    <h3 class="d-flex align-items-center"><img src="{{ URL::asset('/build/img/icons/hi.svg') }}"
                            alt="img">&nbsp;Hi {{ Auth::user()->name }},</h3>&nbsp;<h6>Your email not verified, verify Now with click verify button.</h6>
                    @else
                    <h3 class="d-flex align-items-center"><img src="{{ URL::asset('/build/img/icons/hi.svg') }}"
                            alt="img">&nbsp;Hi {{ Auth::user()->name }},</h3>&nbsp;<h6>let see how you're doing today ;).</h6>
                    @endif
                </div>
                <div class="d-flex align-items-center">
                    @if (!isset(Auth::user()->email_verified_at))
                    <form id="emailVerifyForm">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}" readonly>
                        <button type="button" id="verifyBtn" class="btn btn-primary d-none d-md-inline-block">Verify Email</button>
                    </form>
                    @endif
                    <button type="button" data-toggle="tooltip" class="btn btn-white-outline d-none d-md-inline-block"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw"
                            class="feather-16"></i></button>
                    <a href="#" class="d-none d-lg-inline-block" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-16"></i></a>
                </div>
            </div>
            <div class="row sales-cards">
                <div class="col-xl-6 col-sm-12 col-12">
                    <div class="card d-flex align-items-center justify-content-between default-cover mb-4">
                        <div>
                            <h6>Total Contibution</h6>
                            <h3>Rp <span class="counters" data-count="{{ $statistic['totalPenerimaan'] }}">@currency($statistic['totalPenerimaan'])</span></h3>
                            <p class="sales-range">From <span class="text-success">{{ $statistic['jmlRelawan'] }}&nbsp;</span> contributed volunteers.</p>
                        </div>
                        <img src="{{ URL::asset('/build/img/icons/weekly-earning.svg') }}" alt="img">
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card color-info bg-primary mb-4">
                        <img src="{{ URL::asset('/build/img/icons/total-sales.svg') }}" alt="img">
                        <h3 class="counters" data-count="{{ $statistic['jmlKupon'] }}">{{ $statistic['jmlKupon'] }}</h3>
                        <p>Total of Coupon Contribution</p>
                        <i data-feather="rotate-ccw" class="feather-16" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Refresh"></i>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card color-info bg-secondary mb-4">
                        <img src="{{ URL::asset('/build/img/icons/purchased-earnings.svg') }}" alt="img">
                        <h3 class="counters" data-count="{{ $statistic['jmlProgram'] }}">{{ $statistic['jmlProgram'] }}</h3>
                        <p>Total of Campaign Contribution</p>
                        <i data-feather="rotate-ccw" class="feather-16" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Refresh"></i>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-xl-4 d-flex">
                    <div class="card flex-fill default-cover w-100 mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Campaign Progress</h4>
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="view-all d-flex align-items-center">
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
                <div class="col-sm-12 col-md-12 col-xl-8 d-flex">
                    <div class="card flex-fill default-cover w-100 mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Recent Donation</h4>
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="view-all d-flex align-items-center">
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
            <!-- Button trigger modal -->

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
