<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الاسم
                    </label>
                    <input type="text" class="form-control" name="name" id="name" value="{{$obj->name}}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">رقم الهويه
                    </label>
                    <input type="text" class="form-control" name="national_id" id="national_id"
                           value="{{$obj->national_id}}" style=" text-align: left;">

                </div>
            </div>

                <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">المستثمر
                    </label>
                    <select name="investor_id" id="investor_id" class="form-control select2">
                        <option value="" selected disabled>اختر المستثمر</option>
                        @foreach ($investors as $investor)
                            <option value="{{ $investor->id }}" @if($obj->investor_id == $investor->id) selected @endif>{{ $investor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="debt" class="form-control-label">المبلغ المطلوب سداده
                    </label>
                    <input type="number" step="0.01" class="form-control" value="{{$obj->debt}}" min="0.01" name="debt" id="debt">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="phone" class="form-control-label">رقم الهاتف
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="phone" id="phone"
                               value="{{substr($obj->phone, 4)}}"
                               style=" text-align: left;">
                        <span class="input-group-text">966+</span>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">اسم المكتب
                    </label>
                    <input type="text" class="form-control" name="office_name" id="office_name" readonly
                           value="{{$obj->office_name}}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">رقم هاتف المكتب
                    </label>
                    <div class="input-group">

                        <input type="text" class="form-control" name="office_phone" id="office_phone"
                               value="{{substr($obj->office_phone, 4)}}"  readonly style=" text-align: left;">
                        <span class="input-group-text">966+</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify();
    $('select').select2({
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

    handlePhoneInput('office_phone');
    handlePhoneInput('phone');
</script>

