<?php $page = 'add-campaign'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Edit Campaign</h4>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li>
                        <div class="page-btn">
                            <a href="{{ route('campaign.list') }}" class="btn btn-secondary"><i data-feather="arrow-left"
                                    class="me-2"></i>Back to List</a>
                        </div>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                                data-feather="chevron-up" class="feather-chevron-up"></i></a>
                    </li>
                </ul>

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
            <!-- /add -->
            <form action="{{ route('campaign.update') }}" method="POST" enctype="multipart/form-data">
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
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                    <input type="text" class="form-control" name="name" value="{{ $data->name }}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Campaign Category</label>
                                                    <select class="form-select" name="category">
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @if($category->id == $data->category_id) selected @endif>{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">PIC</label>
                                                    <input type="text" class="form-control" name="pic" value="{{ $data->pic }}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-12">
                                                <div class="input-blocks">
                                                    <label class="form-label">Start Date</label>
                                                    <div class="input-groupicon calender-input">
														<i data-feather="calendar" class="info-img"></i>
														<input type="text" class="datetimepicker" name="start_date"  value="{{ date("d-m-Y", strtotime($data->start_date)) }}" required>
													</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-12">
                                                <div class="input-blocks">
                                                    <label class="form-label">End Date</label>
                                                    <div class="input-groupicon calender-input">
														<i data-feather="calendar" class="info-img"></i>
														<input type="text" class="datetimepicker" name="end_date"  value="{{ date("d-m-Y", strtotime($data->end_date)) }}" required>
													</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Target Amount</label>
                                                    <input type="number" class="form-control" name="target_amount" value="{{ $data->target_amount }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Close Type</label>
                                                    <select class="form-select" name="close_type">
                                                        <option value="1" @if($data->close_type == 1) selected @endif>End Date</option>
                                                        <option value="2" @if($data->close_type == 2) selected @endif>Target Amount</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Editor -->
                                        <div class="col-lg-12">
                                            <div class="input-blocks summer-description-box transfer mb-3">
                                                <label>Description</label>
                                                <textarea class="form-control" rows="4" name="description">{{ $data->description }}</textarea>
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
                                                            <input type="file" id="campaign_picture" name="campaign_picture[]" multiple accept="image/*">
                                                            <div class="image-uploads">
                                                                <i data-feather="plus-circle"
                                                                    class="plus-down-add me-0"></i>
                                                                <h4>Add Images</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @foreach ($data->image as $image)
                                                        <div class="phone-img">
                                                            <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                                                            <img src="{{ URL::asset('storage/campaign_pictures/'.$image->picture_path) }}" alt="image" style="width:100px; height:100px; object-fit:cover;">
                                                            <a href="javascript:void(0);" class="remove-img-campaign" data-index="{{ $image->id }}">
                                                                <i data-feather="x" class="x-square-add">x</i>
                                                            </a>
                                                        </div>
                                                    @endforeach
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
                        <button type="submit" class="btn btn-submit">Update Product</button>
                    </div>
                </div>
            </form>
            <!-- /add -->

        </div>
    </div>
@endsection
