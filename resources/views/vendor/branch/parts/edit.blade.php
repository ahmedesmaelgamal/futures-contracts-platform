<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">


            <div class="col-6">
                <div class="form-group">

                    <label for="name" class="form-control-label">الاسم</label>

                    <input type="text" class="form-control" name="name" id="name"
                           value="{{ $obj->name }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">

                    <label for="address" class="form-control-label">العنوان</label>
                    <input type="text" class="form-control" name="address" id="address"
                           value="{{ $obj->address }}">
                </div>
            </div>

        </div>


        <div class="modal-footer">

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>

            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });
</script>
