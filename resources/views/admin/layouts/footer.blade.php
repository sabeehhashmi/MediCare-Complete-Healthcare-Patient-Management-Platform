<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                {{date('Y')}} © {{config('global.site_name')}}.
            </div>
            <div class="col-sm-6">
                <!-- <div class="text-sm-end d-none d-sm-block">
                    Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://Themesdesign.com/" target="_blank" class="text-reset">Themesdesign</a>
                </div> -->
            </div>
        </div>
    </div>
</footer>

<div class="modal fade" id="upload-docs" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Documents</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{route('admin.uploadAppointmentDocs')}}" id="confirm_docs_upload_form" class="custom-form"
            enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="idUplpad" name="booking_id" value="">
                
                <div class="row">

                    

                            <!-- Lab Report Upload -->
                            <div class="col-md-12 mb-3 lab_upload">
                                <label class="form-label" for="">
                                    Lab Report
                                    <small class="text-muted">(PDF / Image)</small>
                                </label>
                                <div class="position-relative">
                                    <input type="file"
                                    name="lab_report[]"
                                    class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    multiple>
                                </div>
                            </div>
                        
                            <!-- X-Ray Upload -->
                            <div class="col-md-12 mb-3 xray_upload">
                                
                                <label class="form-label" for="">
                                    X-Ray
                                    <small class="text-muted">(Image preferred)</small>
                                </label>
                                <div class="position-relative">
                                    <input type="file"
                                           name="xray[]"
                                           class="form-control"
                                           accept=".jpg,.jpeg,.png,.pdf"
                                           multiple>
                                </div>
                                
                            </div>
                
                </div>
                
            </form>
        </div>
        <div class="modal-footer">
            <button type="button cancel_docs_upload" class="btn btn-outline-secondary waves-effect waves-light"  style="width: 120px;" data-bs-dismiss="modal">No</button>
            <button type="button" id="confirm_docs_upload" class="btn btn-primary">Upload Documents</button>
        </div>
        </div>
    </div>
</div>


