@if (Route::is(['donation.list']))

    <!-- details popup -->
    <div class="modal fade effect-scale" id="donation-details">
        <div class="modal-dialog  modal-dialog-centered sales-details-modal">
            <div class="modal-content">
                <div class="page-wrapper p-0 m-0">
                    <div class="content p-0">
                        <div class="page-header p-4 mb-0">
                            <div class="add-item d-flex">
                                <div class="page-title modal-detail">
                                    <h4 id="liq_number">LIQ Number : </h4>
                                </div>
                            </div>
                            <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <form action="sales-list">
                                    <div class="invoice-box table-height"
                                        style="max-width: 1600px;width:100%;overflow: auto;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                                        <div class="sales-details-items d-flex">
                                            <div class="details-item" id="donatur_info">
                                                <h6>Donor Name</h6>
                                                <p id="donor_info">
                                                </p>
                                            </div>
                                            <div class="details-item">
                                                <h6>Description</h6>
                                                <p id="description">
                                                </p>
                                            </div>
                                            <div class="details-item">
                                                <h6>Volunteer Info</h6>
                                                <p id="volunteer_info">
                                                </p>
                                            </div>
                                            <div class="details-item">
                                                <h5 id="liq_status"></h5>
                                            </div>
                                        </div>
                                        <h5 class="order-text">Donation Summary</h5>
                                        <div class="table-responsive no-pagination">
                                            <table class="table" id="donationDetailTable">
                                                <thead>
                                                    <tr>
                                                        <th>Campaign</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="row">
                                            <div class="col-lg-6 ms-auto">
                                                <div class="total-order w-100 max-widthauto m-auto mb-4">
                                                    <ul>
                                                        <li>
                                                            <h4>Total Amount</h4>
                                                            <h5 id="total_amount">Rp 0,00</h5>
                                                        </li>
                                                        <li>
                                                            <h4>Payment Method</h4>
                                                            <h5 id="payment_method"></h5>
                                                        </li>
                                                        <li>
                                                            <h4>Reference Code</h4>
                                                            <h5 id="reference_code"></h5>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /details popup -->

    <!-- edit popup -->
    <div class="modal fade effect-scale" id="edit-donation">
        <div class="modal-dialog modal-dialog-centered edit-sales-modal">
            <div class="modal-content">
                <div class="page-wrapper p-0 m-0">
                    <div class="content p-0">
                        <div class="page-header p-4 mb-0">
                            <div class="add-item new-sale-items d-flex">
                                <div class="page-title">
                                    <h4>Edit Donation</h4><br/>
                                </div>
                                <button type="button" class="close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <form id="formEditDonation">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" readonly/>
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <label>LIQ Number</label>
                                                <input type="text" class="form-control" name="liq_number"  readonly/>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="donatur_name" />
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <label>Phone Number</label>
                                                <input type="text" class="form-control" name="donatur_phone" />
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <label>Transaction Date</label>
                                                <div class="input-groupicon calender-input">
                                                    <i data-feather="calendar" class="info-img"></i>
                                                    <input type="text" class="datetimepicker" name="trans_date"
                                                        placeholder="Choose date">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-9 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <label>Address</label>
                                                <input type="text" class="form-control" name="donatur_address" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <label>Campaign Name</label>
                                                <div class="input-groupicon select-code">
                                                    <select placeholder="Please type campaign name and select" class="select2 form-select" id="select-campaign">
                                                    </select>
                                                    <div class="addonset">
                                                        <img src="{{ URL::asset('/build/img/icons/search.svg')}}" alt="img">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive no-pagination">
                                        <table class="table" id="editDonationTable">
                                            <thead>
                                                <tr>
                                                    <th>Campaign</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 ms-auto">
                                            <div class="total-order w-100 max-widthauto m-auto mb-4">
                                                <ul>
                                                    <li>
                                                        <h4>Grand Total</h4>
                                                        <h5 id="edit_total_amount">Rp 0,00</h5>
                                                        <input type="hidden" name="total_amount" />
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <label>Reference Code</label>
                                                <div class="row">
														<div class="col-lg-10 col-sm-10 col-10">
															<input type="text" name="reference_code" />
														</div>
														<div class="col-lg-2 col-sm-2 col-2 ps-0">
															<div class="add-icon">
															    <input type="file" style="display: none" id="reference_file" name="reference_picture" />
																<a href="#" class="choose-add" onclick="$('#reference_file').click()"><i data-feather="upload" class="plus"></i></a>
															</div>
														</div>
													</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-6 col-12">
                                            <div class="input-blocks mb-5">
                                                <label>Payment Method</label>
                                                <select class="select" placeholder="Choose" name="payment_method">
                                                    <option value="cash">Cash</option>
                                                    <option value="transfer">Transfer</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="input-blocks">
                                                <label>Description</label>
                                                <textarea class="form-control" name="description"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 text-end">
                                            <button type="button" class="btn btn-cancel add-cancel me-3"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-submit add-sale" id="editSubmit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /edit popup -->



@endif
