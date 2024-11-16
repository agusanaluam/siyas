@if (Route::is(['volunteer.list']))
    <!-- Add Group -->
    <div class="modal fade" id="add-volunteer" tabindex="-1" role="dialog"   aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0">
                            <div class="page-title">
                                <h4>Create Volunteer</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="add-volunteer-form">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Group Name</label>
                                    <select id="group" name="group" class="form-control select select2-hidden-accessible" required>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="user@email.com">
                                </div>
                                <div class="mb-3">
                                    <label>Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">

                                </div>
                                <div class="mb-3">
                                    <label>Confirm Passworrd</label>
                                    <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">

                                </div>
                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" id="volunteer-save" class="btn btn-submit">Add Volunteer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Group -->
@endif
