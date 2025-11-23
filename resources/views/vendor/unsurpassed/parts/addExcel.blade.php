<div class="modal-body">
    <form id="addExcelFile" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeExcelRoute }}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="excel_file" class="form-control-label">أرفق ملف المتعثرين</label>
                    <input type="file" class="dropify" name="excel_file" id="excel_file">
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">حفظ
            </button>
        </div>

    </form>
</div>


<script>
    $('.dropify').dropify();

    $(document).ready(function() {
            console.log('ready');
            $('Form#addExcelFile').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var url = $(this).attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                    $('#addButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                        ' ></span> <span style="margin-left: 4px;">أنتظر قليلًا...</span>').attr('disabled', true);
                },
                    success: function(response) {
                        if (response.status == 200) {
                            toastr.success(response.message);
                            $('#addExcelFile').modal('hide');

                            // Reload the DataTable
                            $('#dataTable').DataTable().ajax.reload(null, false);

                            // Reset the form
                            $('#addExcelFile')[0].reset();
                            $('.dropify-clear').click();
                            $('#addButton').html(`اضافه`).attr('disabled', false);

                        } else {
                            toastr.error(response.message);
                            $('#addButton').html(`اضافه`).attr('disabled', false);

                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    }
                });
            });
        });
    </script>
