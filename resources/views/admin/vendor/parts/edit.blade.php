<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">


            <!-- Image Upload -->
            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">الصوره</label>
                    <input type="file" class="dropify" name="image" id="image"
                           data-default-file="{{ getFile($obj->image) }}">
                </div>
            </div>

            <!-- Region Selection -->
            <div class="col-6">
                <div class="form-group">
                    <label for="city_id" class="form-control-label">اسم المدينة</label>
                    <select class="form-control" name="city_id" id="city_id">
                        <option value="" disabled>اختر المدينة</option>
                        @foreach ($cities as $city)
                            <option
                                value="{{ $city->id }}" {{ $obj->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>




            <!-- Name -->
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الإسم</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $obj->name }}">
                </div>
            </div>

            <!-- Email -->
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

            <!-- National ID -->
            <div class="col-6">
                <div class="form-group">
                    <label for="national_id" class="form-control-label">رقم الهويه</label>
                    <input type="number" class="form-control" name="national_id" minlength="14"
                           value="{{ $obj->national_id }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="commercial_number" class="form-control-label">رقم السجل الضريبي</label>
                    <input type="number" class="form-control" name="commercial_number" value="{{ $obj->commercial_number }}" id="commercial_number">
                </div>
            </div>




            <!-- Password Fields -->
            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">كلمة المرور</label>
                    <input type="password" class="form-control" name="password">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password_confirmation" class="form-control-label">تأكيد كلمة المرور</label>
                    <input type="password" class="form-control" name="password_confirmation">
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
                    <input class="form-check-input" type="checkbox" id="selectAllPermissions">
                    <label class="form-check-label" for="selectAllPermissions">اختيار الكل</label>
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
                            {{ in_array($permission->id, $obj->permissions->pluck('id')->toArray()) ? 'checked' : '' }}>
                        <label class="form-check-label">
                            {{ getKey()[$loop->iteration-1] }}
                        </label>
                    </div>
                @endforeach
                @endforeach
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلاق</button>
            <button type="submit" class="btn btn-primary" id="updateButton">حفظ</button>
        </div>
    </form>
</div>

<!-- Scripts -->
<script>
    $('.dropify').dropify();
    $('select').select2({dropdownParent: $('#editOrCreate .modal-content')});

    // Select All Permissions
    document.getElementById('selectAllPermissions').addEventListener('change', function () {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });

        document.querySelectorAll('.parent-select-all').forEach(groupCheckbox => {
            groupCheckbox.checked = this.checked;
        });
    });

    // Ensure dependent checkboxes are checked
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            let group = this.dataset.group;
            let secondPermission = document.querySelectorAll(`.permission-checkbox[data-group='${group}']`)[1];
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
