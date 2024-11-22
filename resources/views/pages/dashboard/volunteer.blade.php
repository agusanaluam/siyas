<?php $page = 'sales-dashboard'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="welcome d-lg-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center welcome-text">
                    <h3 class="d-flex align-items-center"><img src="{{ URL::asset('/build/img/icons/hi.svg') }}"
                            alt="img">&nbsp;Hi {{ Auth::user()->name }},</h3>&nbsp;<h6>let see how you're doing today ;).
                    </h6>
                </div>
                <div class="d-flex align-items-center">
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
                            <p class="sales-range"><span class="text-success">{{ $statistic['points'] }}&nbsp;</span> is your points, today.</p>
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
                            <h4 class="card-title mb-0">Less Contribution Campaign</h4>
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="view-all d-flex align-items-center">
                                    View All<span class="ps-2 d-flex align-items-center"><i data-feather="arrow-right"
                                            class="feather-16"></i></span>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless best-seller">
                                    <tbody>
                                        @foreach ($uncontribCampaign as $campaign)
                                        <tr>
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
                                                        <p class="dull-text">{{ $campaign->category->name }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="head-text">Progress</p>
                                                {{-- {{ round(($campaign->total_amount/$campaign->target_amount)*100, 2) }}% --}}
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
        </div>
    </div>
@endsection
