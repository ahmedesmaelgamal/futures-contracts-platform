<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الإسم</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $obj->name }}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ $obj->email }}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="phone" class="form-control-label">رقم الهاتف</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="phone" maxlength="11"
                         id="phone"       value="{{ substr($obj->phone, 4) }}" style="text-align: left;">
                        <span class="input-group-text">966+</span>
                    </div>
                </div>
            </div>

             <div class="col-6">
                <div class="form-group">
                    <label for="phnational_idone" class="form-control-label">رقم الهويه</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="national_id" value="{{ $obj->national_id }}" maxlength="10">
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="branch_id" class="form-control-label">الفرع</label>
                    <select name="branch_id" class="form-control">
                        <option value="null" selected disabled>اختر الفرع</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $obj->branch_id == $branch->id ? 'selected' : ''}}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-success" id="updateButton">تعديل</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify();
    $('select#branch_id').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });</script>
<script>
    function handlePhoneInput(inputId) {
        document.getElementById(inputId).addEventListener('input', function (e) {
            let value = e.target.value;

            // Remove leading zero
            if (value.startsWith('0')) {
                value = value.slice(1);
            }

            // Limit to a maximum of 9 digits
            if (value.length > 9) {
                value = value.slice(0, 9);
            }

            e.target.value = value;
        });
    }

    handlePhoneInput('phone');
</script>
