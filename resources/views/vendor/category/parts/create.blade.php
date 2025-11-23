<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">الاسم
                        </label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
            <button type="submit" class="btn btn-primary" id="addButton">حفظ</button>
        </div>

    </form>
</div>


<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });
</script>
