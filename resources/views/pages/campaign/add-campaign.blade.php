<?php $page = 'add-campaign'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    New Campaign
                @endslot
                @slot('li_1')
                    Create new Campaign
                @endslot
                @slot('li_2')
                    {{ route('campaign.list') }}
                @endslot
                @slot('li_3')
                    Back to List
                @endslot
            @endcomponent

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
            <!-- /add -->
            <form action="{{ route('campaign.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="card">
                    <div class="card-body add-product pb-0">
                        <div class="accordion-card-one accordion" id="accordionCampaign">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingOne">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                        aria-controls="collapseOne">
                                        <div class="addproduct-icon">
                                            <h5><i data-feather="info" class="add-info"></i><span>Campaign Information</span>
                                            </h5>
                                            <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                    class="chevron-down-add"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionCampaign">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-lg-9 col-md-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Campaign Title</label>
                                                    <input type="text" class="form-control" name="name" placeholder="Campaign Title" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Campaign Category</label>
                                                    <select class="form-select" name="category">
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">PIC</label>
                                                    <input type="text" class="form-control" name="pic" placeholder="PIC name" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-12">
                                                <div class="input-blocks">
                                                    <label class="form-label">Start Date</label>
                                                    <div class="input-groupicon calender-input">
														<i data-feather="calendar" class="info-img"></i>
														<input type="text" class="datetimepicker" name="start_date"  placeholder="Choose Date" required>
													</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-12">
                                                <div class="input-blocks">
                                                    <label class="form-label">End Date</label>
                                                    <div class="input-groupicon calender-input">
														<i data-feather="calendar" class="info-img"></i>
														<input type="text" class="datetimepicker" name="end_date"  placeholder="Choose Date" required>
													</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Target Amount</label>
                                                    <input type="number" class="form-control" name="target_amount" placeholder="0" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Target Object (penerima manfaat)</label>
                                                    <input type="number" class="form-control" name="target_object" placeholder="0">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Close Type</label>
                                                    <select class="form-select" name="close_type">
                                                        <option value="1">End Date</option>
                                                        <option value="2">Target Amount</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Editor -->
                                        <div class="col-lg-12">
                                            <div class="input-blocks summer-description-box transfer mb-3">
                                                <label>Description</label>
                                                <textarea class="form-control" rows="4" name="description"></textarea>
                                            </div>
                                        </div>
                                        <!-- /Editor -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-card-one accordion" id="accordionCampaignImg">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingThree">
                                    <div class="accordion-button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree"
                                        aria-controls="collapseThree">
                                        <div class="addproduct-icon list">
                                            <h5><i data-feather="image"
                                                    class="add-info"></i><span>Images</span></h5>
                                            <a href="javascript:void(0);"><i
                                                    data-feather="chevron-down"
                                                    class="chevron-down-add"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseThree" class="accordion-collapse collapse show"
                                    aria-labelledby="headingThree"
                                    data-bs-parent="#accordionCampaignImg">
                                    <div class="accordion-body">
                                        <div class="text-editor add-list add">
                                            <div class="col-lg-12">
                                                <div class="add-choosen">
                                                    <div class="input-blocks">
                                                        <div class="image-upload">
                                                            <input type="file" id="campaign_picture" name="campaign_picture[]" multiple accept="image/*" required>
                                                            <div class="image-uploads">
                                                                <i data-feather="plus-circle"
                                                                    class="plus-down-add me-0"></i>
                                                                <h4>Add Images</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="btn-addproduct mb-4">
                        <a href="{{ route('campaign.list') }}" class="btn btn-cancel me-2">Cancel</a>
                        <button type="submit" class="btn btn-submit">Save Product</button>
                    </div>
                </div>
            </form>
            <!-- /add -->

        </div>
    </div>
@endsection
