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
												<label>Total Setor</label>
                                                <input type="hidden" name="total_amount" readonly/>
                                                <input type="text" id="total_amount" readonly/>
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
		<div class="modal fade" id="view-mutation">
			<div class="modal-dialog modal-dialog-centered stock-adjust-modal">
				<div class="modal-content">
					<div class="page-wrapper-new p-0">
						<div class="content">
							<div class="modal-header border-0 custom-modal-header">
								<div class="page-title">
									<h4>View Mutation</h4>
								</div>
								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body custom-modal-body">
									<div class="row">
										<div class="col-lg-6">
											<div class="input-blocks">
												<label>Invoice</label>
												<input type="text" class="form-control" id="invoice_number" placeholder="INV000X" readonly/>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="input-blocks">
												<label>Date</label>
                                                <div class="input-groupicon calender-input">
                                                    <i data-feather="calendar" class="info-img"></i>
                                                    <input type="text" class="datetimepicker" id="trans_date"
                                                        placeholder="Choose date" readonly>
                                                </div>
											</div>
										</div>

										<div class="col-lg-12">
											<div class="modal-body-table">
												<div class="table-responsive">
													<table class="table" id="viewMutationTable">
														<thead>
															<tr>
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
												<label>Total Setor</label>
                                                <input type="text" id="total_amount" readonly/>
											</div>
										</div>
                                        <div class="col-lg-12">
											<div class="input-blocks">
												<label>Notes</label>
												<textarea class="form-control" id="description" readonly></textarea>
											</div>
										</div>
									</div>
									<div class="modal-footer-btn">
										<button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Close</button>
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
