<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الاسم
                    </label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">رقم الهويه
                    </label>
                    <input type="number" class="form-control" name="national_id" id="national_id" minlength="10"
                           maxlength="10">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">المستثمر
                    </label>
                    <select name="investor_id" id="investor_id22" class="form-control select2">
                        <option value="" selected disabled>اختر المستثمر</option>
                        @foreach ($investors as $investor)
                            <option value="{{ $investor->id }}">{{ $investor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="debt" class="form-control-label">المبلغ المطلوب سداده
                    </label>
                    <input type="number" step="0.01" min="0.01" class="form-control" name="debt" id="debt">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group position-relative">
                    <label for="phone" class="form-control-label">رقم الهاتف
                    </label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="phone" id="phone"
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
                           value="{{ VendorParentAuthData('name') }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group position-relative">
                    <label for="office_phone" class="form-control-label">رقم المكتب</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="office_phone" id="office_phone" readonly
                               value="{{ substr(VendorParentAuthData('phone'), 4) }}" style=" text-align: left;">
                        <span class="input-group-text">966+</span>
                    </div>
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
    $('select#investor_id22').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });
</script>

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

<script>
    $('#national_id').on('input', function () {
        const nationalId = $(this).val();

        if (nationalId.length === 10) {
            $.ajax({
                type: 'POST',
                url: '{{ route("check.unsurpassed.by.national_id") }}',
                data: {
                    national_id: nationalId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status==200) {
                        $('#name').val(response.unsurpassed.name).prop('readonly', true);
                        $('#phone').val(response.unsurpassed.phone.substring(4)).prop('readonly', true);
                        toastr.success("تم العثور على المتعثر");
                    } else {
                        $('#name').val('').prop('readonly', false);
                        $('#phone').val('').prop('readonly', false);
                        toastr.error('المتعثر ليس موجود من فضلك أكمل بياناته');
                    }
                },
                error: function () {
                    toastr.error("حدث خطأ أثناء البحث");
                }
            });
        } else {
            $('#name').val('').prop('readonly', false);
            $('input[name="phone"]').val('').prop('readonly', false);
        }
    });

</script>

