<?php $page = 'product-details'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Campaign Details</h4>
                    <h6>Full details of a campaign</h6>
                </div>
            </div>
            <!-- /add -->
            <div class="row">
                <div class="col-lg-8 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="productdetails">
                                <ul class="product-bar">
                                    <li>
                                        <h4>Campaign</h4>
                                        <h6>{{ $data->name }}</h6>
                                    </li>
                                    <li>
                                        <h4>Category</h4>
                                        <h6>{{ $data->category->name }}</h6>
                                    </li>
                                    <li>
                                        <h4>PIC</h4>
                                        <h6>{{ $data->pic }}</h6>
                                    </li>
                                    <li>
                                        <h4>Target Amount</h4>
                                        <h6>@currency($data->target_amount)</h6>
                                    </li>
                                    <li>
                                        <h4>Target Object</h4>
                                        <h6>{{ $data->target_object }}</h6>
                                    </li>
                                    <li>
                                        <h4>Progress Amount</h4>
                                        <h6>@currency($data->total_amount) ( {{ round(floatval($data->total_amount/$data->target_amount)*100,2)}}% )</h6>
                                    </li>
                                    <li>
                                        <h4>Start - End Data</h4>
                                        <h6>{{ date('d-m-Y', strtotime($data->start_date)) }} s/d {{ date('d-m-Y', strtotime($data->end_date)) }}</h6>
                                    </li>
                                    <li>
                                        <h4>Status</h4>
                                        <h6>
                                            @switch($data->status)
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
                                        </h6>
                                    </li>
                                    <li>
                                        <h4>Description</h4>
                                        <h6>{{ $data->description }}</h6>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="slider-product-details">
                                <div class="owl-carousel owl-theme product-slide">
                                    @foreach ($data->image as $image)
                                    <div class="slider-product">
                                        <img src="{{ URL::asset('/storage/campaign_pictures/'.$image->picture_path) }}" alt="img">
                                        <h4>{{ $image->picture_path }}</h4>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /add -->
        </div>
    </div>
@endsection
