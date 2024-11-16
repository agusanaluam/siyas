@if(Route::is(['mutation.list']))
		<!-- Add Stock -->
		<div class="modal fade" id="add-mutation">
			<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="dialog">
				<div class="modal-content">
					<div class="page-wrapper-new p-0">
						<div class="content">
							<div class="modal-header border-0 custom-modal-header">
								<div class="page-title">
									<h4>Add Mutation</h4>
								</div>
								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body custom-modal-body">
								<form id="formAddMutation">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="total_amount" readonly/>
									<div class="row">
										<div class="col-lg-6">
											<div class="input-blocks">
												<label>Invoice</label>
												<input type="text" class="form-control" name="invoice_number" placeholder="INV000X"/>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="input-blocks">
												<label>Date</label>
                                                <div class="input-groupicon calender-input">
                                                    <i data-feather="calendar" class="info-img"></i>
                                                    <input type="text" class="datetimepicker" name="trans_date"
                                                        value="{{ date('d-m-Y', strtotime(now())) }}"
                                                        placeholder="Choose date">
                                                </div>
											</div>
										</div>

										<div class="col-lg-12">
											<div class="modal-body-table">
												<div class="table-responsive">
													<table class="table" id="addMutationTable">
														<thead>
															<tr>
                                                                <th class="no-sort">
                                                                    <label class="checkboxs">
                                                                        <input type="checkbox" id="select-donation">
                                                                        <span class="checkmarks"></span>
                                                                    </label>
                                                                </th>
																<th>LIQ Number</th>
																<th>Donor Name</th>
																<th>Date</th>
																<th>LIQ Amount</th>
															</tr>
														</thead>
														<tbody>

														</tbody>
													</table>
												</div>
											</div>
										</div>

                                        <div class="col-lg-12">
											<div class="input-blocks">
												<label>Notes</label>
												<textarea class="form-control" name="description"></textarea>
											</div>
										</div>
									</div>
									<div class="modal-footer-btn">
										<button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
										<button type="button" class="btn btn-submit" id="mutationSubmit">Save Changes</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Add Stock -->

		<!-- Edit Stock -->
		<div class="modal fade" id="edit-units">
			<div class="modal-dialog modal-dialog-centered stock-adjust-modal">
				<div class="modal-content">
					<div class="page-wrapper-new p-0">
						<div class="content">
							<div class="modal-header border-0 custom-modal-header">
								<div class="page-title">
									<h4>Edit Stock</h4>
								</div>
								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body custom-modal-body">
								<form action="manage-stocks">
									<div class="input-blocks search-form">
										<label>Product</label>
										<input type="text" class="form-control" value="Nike Jordan">
										<i data-feather="search" class="feather-search"></i>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<div class="input-blocks">
												<label>Warehouse</label>
												<select class="select">
													<option>Lobar Handy</option>
													<option>Quaint Warehouse</option>
												</select>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="input-blocks">
												<label>Shop</label>
												<select class="select">
													<option>Selosy</option>
													<option>Logerro</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="input-blocks">
												<label>Responsible Person</label>
												<select class="select">
													<option>Steven</option>
													<option>Gravely</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="input-blocks search-form mb-3">
												<label>Product</label>
												<input type="text" class="form-control" placeholder="Select Product" value="Nike Jordan">
												<i data-feather="search" class="feather-search"></i>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="modal-body-table">
												<div class="table-responsive">
													<table class="table">
														<thead>
															<tr>
																<th>Product</th>
																<th>SKU</th>
																<th>Category</th>
																<th>Qty</th>
																<th class="no-sort">Action</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	<div class="productimgname">
																		<a href="javascript:void(0);" class="product-img stock-img">
																			<img src="{{ URL::asset('/build/img/products/stock-img-02.png')}}" alt="product">
																		</a>
																		<a href="javascript:void(0);">Nike Jordan</a>
																	</div>
																</td>
																<td>PT002</td>
																<td>Nike</td>
																<td>
																	<div class="product-quantity">
																		<span class="quantity-btn"><i data-feather="minus-circle" class="feather-search"></i></span>
																		<input type="text" class="quntity-input" value="2">
																		<span class="quantity-btn">+<i data-feather="plus-circle" class="plus-circle"></i></span>
																	</div>
																</td>
																<td class="action-table-data">
																	<div class="edit-delete-action">
																		<a class="me-2 p-2" href="#" data-bs-toggle="modal" data-bs-target="#edit-units">
																			<i data-feather="edit" class="feather-edit"></i>
																		</a>
																		<a class="confirm-text p-2" href="javascript:void(0);">
																			<i data-feather="trash-2" class="feather-trash-2"></i>
																		</a>
																	</div>

																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer-btn">
										<button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
										<button type="submit" class="btn btn-submit">Save Changes</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Edit Stock -->
@endif
