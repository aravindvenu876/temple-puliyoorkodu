<div id="modal-dialog-image-upload" class="modal fade modalCustom" role="dialog">
    <form data-validate="parsley" method="post" id="frmdata" class="document-upload-form">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="form_title_h2">Uploading Supporting Documents</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row ">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <input type="hidden" id="image_upload_id" name="image_upload_id">
                                <input type="hidden" id="image_upload_grid" name="image_upload_grid">
                                <input type="hidden" id="image_upload_type" name="image_upload_type">
                                <input id="FileInput" name="FileInput" type="file" class="form-control parsley-validated" data-required="true" placeholder="Display Value">
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <b>Max file size : 2MB</b><br>
                            <b>Supported file types: jpg, png, jpeg,docx,pdf</b>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success submit-btn saveData1">Upload</button>
                    <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var options = {
            beforeSubmit: beforeSubmit, // pre-submit callback 
            success: afterSuccess,      // post-submit callback 
            resetForm: true             // reset the form after successful submit 
        };
        $('.document-upload-form').submit(function () {
            $(this).ajaxSubmit(options);
            return false;
        });
        function afterSuccess(data) {
            $('#alert-prompt').hide();
            $('.submit-btn').hide(); //hide submit button
            $('#loading-img').hide(); //hide submit button
            $('.document-upload-form').parsley().reset();
            $.toaster({priority: 'success', title: '', message: 'Document Uploaded Successfully'});
            $("#" + data.grid).dataTable().fnDraw();
            $('.close-modal').trigger('click');
            $('.submit-btn').show();
        }
        function beforeSubmit() {
            //check whether browser fully supports all File API
            if (window.File && window.FileReader && window.FileList && window.Blob){
                if (!$('#FileInput').val()){
                    $.toaster({priority: 'danger', title: '', message: 'Select document file'});
                    return false;
                }
                //allow file types
                var ext = $('#FileInput').val().split('.').pop().toLowerCase();
                if($.inArray(ext, ['doc', 'docx', 'pdf','jpg', 'jpeg', 'png']) == -1) {
                    $.toaster({priority: 'danger', title: '', message: 'Unsupported file type!'});
                    return false;
                }   
                var fsize = $('#FileInput')[0].files[0].size; //get file size 
                if (fsize > <?php echo DOCUMENT_UPLOAD_SIZE_BYTE ?>){
                    $.toaster({priority: 'danger', title: '', message: 'File is too large, it should be less than <?php echo DOCUMENT_UPLOAD_SIZE_MB ?> MB'});
                    return false
                }
                $('#alert-prompt').show();
                $('.submit-btn').hide(); //hide submit button
                $('#loading-img').show(); //hide submit button
                $("#output").html("");
            } else {
                $.toaster({priority: 'danger', title: '', message: 'Please upgrade your browser, because your current browser lacks some new features we need!'});
                return false;
            }
        }
    });
    
</script>
