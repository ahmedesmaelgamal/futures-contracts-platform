<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ route('admins.store') }}">
        @csrf
        <input type="hidden" name="code" value="{{$code}}">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">الإسم</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="phone" class="form-control-label">رقم الهاتف</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="phone" name="phone" maxlength="11" style="text-align: left;">
                        <span class="input-group-text">966+</span>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">البريد الإلكتروني</label>
                    <input type="text" class="form-control" name="email" id="email" autocomplete="off">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">كلمة السر</label>
                    <input type="password" class="form-control" name="password" id="password" autocomplete="off">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password_confirmation" class="form-control-label">تأكيد كلمة السر</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                </div>
            </div>

            <div class="col-lg-3 col-12 mb-2">
                <div class="name-rule">
                    <h5>صلاحيات المدير</h5>
                </div>
            </div>

            <div class="col-lg-9 col-12 d-flex flex-wrap justify-content-between mb-5">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllPermissions">
                    <label class="form-check-label" for="selectAllPermissions">اختيار الكل</label>
                </div>
            </div>

            @foreach ($permissions->groupBy('parent_name') as $parent => $group)
                <div class="col-lg-3 col-12 mb-2">
                    <div class="name-rule">
                        <h5>{{ trans('permissions.'.$parent) }}</h5>
                    </div>
                </div>

                <div class="col-lg-9 col-12 d-flex flex-wrap justify-content-between mb-5">
                    @foreach($group as $permission)
                        <div class="form-check">
                            <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]"
                                   value="{{ $permission->id }}" data-group="{{ $parent }}">
                            <label class="form-check-label" for="flexCheckDefault{{ $loop->iteration }}">
                                {{ getKey()[$loop->iteration - 1] }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">حفظ</button>
        </div>
    </form>
</div>

<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')
    });

    document.getElementById('selectAllPermissions').addEventListener('change', function () {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });

        document.querySelectorAll('.parent-select-all').forEach(groupCheckbox => {
            groupCheckbox.checked = this.checked;
        });
    });

    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            let group = this.dataset.group;
            let secondPermission = document.querySelectorAll(`.permission-checkbox[data-group='${group}']`)[0];
            if (this.checked && secondPermission) {
                secondPermission.checked = true;
            }
        });
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

    handlePhoneInput('phone');
</script>
