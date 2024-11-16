@if (Route::is(['group.list']))
    <!-- Add Group -->
    <div class="modal fade" id="add-group" tabindex="-1" role="dialog"   aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0">
                            <div class="page-title">
                                <h4>Create Group</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="add-group-form">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Group</label>
                                    <input type="text" class="form-control" name="name" placeholder="Group Name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="3" name="description" required></textarea>
                                </div>
                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                    data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" id="group-save" class="btn btn-submit">Create Group</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Group -->

    <!-- Edit Group -->
    <div class="modal fade" id="edit-group">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0">
                            <div class="page-title">
                                <h4>Edit Group</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit-group-form">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                <input type="hidden" class="form-control" name="id">
                                <div class="mb-3">
                                    <label class="form-label">Group</label>
                                    <input type="text" class="form-control" name="name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="3" name="description"></textarea>
                                </div>

                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" id="group-edit" value="update" class="btn btn-submit">Update Group</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Group -->
@endif
