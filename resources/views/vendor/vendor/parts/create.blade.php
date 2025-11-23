<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">الصوره</label>
                    <input type="file" class="dropify" name="image" id="image">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="region_id" class="form-control-label">اسم المدينه</label>
                    <select class="form-control" name="city_id" id="region_id">
                        <option value="" selected disabled>اسم المدينه</option>
                        @foreach ($cities as $city)
                        <option value="{{$city->id }}">{{$city->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="branch_id" class="form-control-label">اسم الفرع التابع له</label>
                    <select class="form-control" name="branch_ids[]" id="branch_id" multiple
                    onchange="officeManager({{ $branches->toJson() }}, this)">
                        <option value="" disabled>اختر الفرع</option>
                        @foreach ($branches as $branch)
                        <option value="{{$branch->id }}">{{$branch->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الإسم</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control" name="email" id="email">
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
                    <label for="name" class="form-control-label">رقم الهويه</label>
                    <input type="number" class="form-control" name="national_id" minlength="14" id="name">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">كلمة المرور</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">تأكيد كلمة المرور</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password">
                </div>
            </div>

            <div class="col-lg-3 col-12 mb-2">
                <div class="name-rule">
                    <h5>صلاحيات المدير</h5>
                </div>
            </div>
            <div class="col-lg-9 col-12 d-flex flex-wrap justify-content-between mb-5">
                <div class="row form-check w-100">
                    <div class="col-4">
                        <input class="form-check-input" type="checkbox" id="selectAllPermissions">
                        <label class="form-check-label" for="selectAllPermissions">اختيار الكل</label>
                    </div>

{{--                   <div class="col-4 office-manager-container" style="display: none">--}}
{{--                        <input class="form-check-input office-manager-check" type="checkbox" id="selectAllPermissions1">--}}
{{--                        <label class="form-check-label " for="selectAllPermissions1">مدير المكتب</label>--}}

                    <div class="col-4" id="officeManagerCheckbox" style="display: none;">
                        <input class="form-check-input" type="checkbox" id="selectAllPermissions1" checked>
                        <label class="form-check-label" for="selectAllPermissions1">مدير المكتب</label>
                    </div>

                </div>
                @foreach ($permissions->groupBy('parent_name') as $parent => $group)
            </div>
            <div class="col-lg-3 col-12 mb-2">
                <div class="name-rule">
                    <h5> {{trans('permissions.'.$parent)}} </h5>
                </div>
            </div>
            <div class="col-lg-9 col-12 d-flex flex-wrap justify-content-between mb-5">

                @foreach($group as $permission)
                <div class="form-check">
                    <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]"
                        value="{{ $permission->id }}" data-group="{{ $parent }}">
                    <label class="form-check-label" for="flexCheckDefault{{$loop->iteration}}">
                        {{getKey()[$loop->iteration-1]}}
                    </label>
                </div>
                @endforeach

                @endforeach

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
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });

    document.getElementById('selectAllPermissions').addEventListener('change', function() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });

        document.querySelectorAll('.parent-select-all').forEach(groupCheckbox => {
            groupCheckbox.checked = this.checked;
        });
    });

    document.getElementById('selectAllPermissions1').addEventListener('change', function() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });

        document.querySelectorAll('.parent-select-all').forEach(groupCheckbox => {
            groupCheckbox.checked = this.checked;
        });
    });

    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            let group = this.dataset.group;
            let secondPermission = document.querySelectorAll(`.permission-checkbox[data-group='${group}']`)[0];
            if (this.checked && secondPermission) {
                secondPermission.checked = true;
            }
        });
    });


    function officeManager(branches, selectElement) {
//        // Get the selected options
//        // let selectedOptions = Array.from(selectElement.selectedOptions).map(option => option.value);
//        // console.log(branches.find(branch => branch.name === 'الفرع الرئيسي').id, selectedOptions.includes(branches.find(branch => branch.name === 'الفرع الرئيسي').id));
//        if (branches.find(branch => branch.name === 'الفرع الرئيسي').id == selectElement.value) {
//            document.querySelector('.office-manager-container').style.display = 'block';
//            document.querySelector('.office-manager-check').checked = true;
//            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
//                checkbox.checked = this.checked=true;
//            });

//            document.querySelectorAll('.parent-select-all').forEach(groupCheckbox => {
//                groupCheckbox.checked = this.checked=true;
//            });
//        } else {
//            document.querySelector('.office-manager-container').style.display = 'none';
//            document.querySelector('.office-manager-check').checked = false;
//            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
//                checkbox.checked = this.checked=false;
//            });

   //         document.querySelectorAll('.parent-select-all').forEach(groupCheckbox => {
  //              groupCheckbox.checked = this.checked=false;
 //           });
//        }




    let selectedOptions = Array.from(selectElement.selectedOptions).map(option => option.value);

    // ابحث عن الفرع الرئيسي
    let mainBranch = branches.find(branch => branch.name === 'الفرع الرئيسي');

    if (mainBranch && selectedOptions.includes(mainBranch.id.toString())) {
        // إظهار زر مدير المكتب وتحديده
        document.getElementById('officeManagerCheckbox').style.display = 'block';
        document.getElementById('selectAllPermissions1').checked = true;

        // تحديد كل الصلاحيات
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });

        // تعطيل باقي الفروع
        Array.from(selectElement.options).forEach(option => {
            if (option.value !== mainBranch.id.toString()) {
                option.disabled = true;
            }
        });

    } else {
        // إخفاء الزر وإلغاء تحديده
        document.getElementById('officeManagerCheckbox').style.display = 'none';
        document.getElementById('selectAllPermissions1').checked = false;

        // إزالة تحديد الصلاحيات
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });

        // إعادة تمكين الفروع
        Array.from(selectElement.options).forEach(option => {
            option.disabled = false;
        });
    }
}


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

