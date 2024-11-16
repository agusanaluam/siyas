@if (Route::is(['campaign.categories']))
    <!-- Add Category -->
    <div class="modal fade" id="add-campaign-category" tabindex="-1" role="dialog"   aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0">
                            <div class="page-title">
                                <h4>Create Category</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="add-campaign-category-form">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <input type="text" class="form-control" name="name" placeholder="Category Name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="3" name="description" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">PIC</label>
                                    <input type="text" class="form-control" name="pic" placeholder="PIC name" required>
                                </div>
                                <div class="mb-0">
                                    <div
                                        class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                        <span class="status-label">Status</span>
                                        <input type="checkbox" id="status_category" class="check" name="status" checked="">
                                        <label for="status_category" class="checktoggle"></label>
                                    </div>
                                </div>
                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                    data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" id="btn-save" class="btn btn-submit">Create Category</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Category -->

    <!-- Edit Category -->
    <div class="modal fade" id="edit-campaign-category">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0">
                            <div class="page-title">
                                <h4>Edit Category</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit-campaign-category-form">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                <input type="hidden" class="form-control" name="id">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <input type="text" class="form-control" name="name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="3" name="description"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">PIC</label>
                                    <input type="text" class="form-control" name="pic">
                                </div>
                                <div class="mb-0">
                                    <div
                                        class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                        <span class="status-label">Status</span>
                                        <input type="checkbox" id="status_category" class="check" name="status" checked="">
                                        <label for="status_category" class="checktoggle"></label>
                                    </div>
                                </div>
                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" id="btn-edit" value="update" class="btn btn-submit">Update Category</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Category -->
@endif
