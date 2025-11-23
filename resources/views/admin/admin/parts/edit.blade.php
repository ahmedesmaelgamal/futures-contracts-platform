<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{route('admins.update',$admin->id)}}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{$admin->id}}" name="id">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">الإسم</label>
                    <input type="text" class="form-control" name="name" value="{{$admin->name}}" id="name">
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="phone" class="form-control-label">رقم الهاتف</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="phone" maxlength="11"
                               id="phone"       value="{{ substr($admin->phone, 4) }}" style="text-align: left;">
                        <span class="input-group-text">966+</span>
                    </div>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">البريد الإلكتروني</label>
                    <input type="text" class="form-control" name="email" value="{{$admin->email}}" id="email">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">كلمة المرور</label>
                    <input type="password" class="form-control" name="password" id="password" autocomplete="off">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">تأكيد كلمة المرور</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password" autocomplete="off">
                </div>
            </div>
            <!-- Permissions Section -->
            <div class="col-lg-3 col-12 mb-2">
                <div class="name-rule">
                    <h5>صلاحيات المدير</h5>
                </div>
            </div>

            <div class="col-lg-9 col-12 d-flex flex-wrap justify-content-between mb-5">
                <div class="form-check">

                    <input class="form-check-input" type="checkbox" id="selectAllPermissions" name="selectAllPermissions">
                    <label class="form-check-label" for="selectAllPermissions" id="selectAllLabel">اختيار الكل</label>


                </div>
                @foreach ($permissions->groupBy('parent_name') as $parent => $group)
            </div>

            <div class="col-lg-3 col-12 mb-2">
                <div class="name-rule">
                    <h5>{{ trans('permissions.'.$parent) }}</h5>
                </div>
            </div>

            <div class="col-lg-9 col-12 d-flex flex-wrap justify-content-between mb-5">
                @foreach($group as $permission)
                <div class="form-check">
                    <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]"
                        value="{{ $permission->id }}" data-group="{{ $parent }}"
                        {{ in_array($permission->id, $admin->permissions->pluck('id')->toArray()) ? 'checked' : '' }}>
                    <label class="form-check-label">
                        {{ getKey()[$loop->iteration-1] }}
                    </label>
                </div>
                @endforeach
                @endforeach
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')
    });

    // Select All Permissions
    document.getElementById('selectAllPermissions').addEventListener('change', function() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });

        document.querySelectorAll('.parent-select-all').forEach(groupCheckbox => {
            groupCheckbox.checked = this.checked;
        });
    });

    // Ensure dependent checkboxes are checked
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            let group = this.dataset.group;
            let secondPermission = document.querySelectorAll(`.permission-checkbox[data-group='${group}']`)[0];
            if (this.checked && secondPermission) {
                secondPermission.checked = true;
            }
        });
    });



</script>

<script>
    function checkSelectAllStatus() {
        const allPermissions = document.querySelectorAll('.permission-checkbox');
        const selectAll = document.getElementById('selectAllPermissions');
        const allChecked = Array.from(allPermissions).every(checkbox => checkbox.checked);
        selectAll.checked = allChecked;
    }

    // بعد تحميل الصفحة شغّلها بعد تأخير بسيط
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            checkSelectAllStatus();
        }, 200);
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
