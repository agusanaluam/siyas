<?php $page = 'mutation-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Mutation List
                @endslot
                @slot('li_1')
                    Manage Your Mutation
                @endslot
                @slot('li_2')
                    #
                @endslot
                @slot('li_3')
                    Add New Mutation
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
                        <table class="table" id="mutationTable">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Invoice</th>
                                    <th>Date</th>
                                    <th>Total LIQ</th>
                                    <th>Grand Total</th>
                                    <th>Status</th>
                                    <th>Approve By</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>
@endsection
@component('pages.mutation.form-modal')

@endcomponent
