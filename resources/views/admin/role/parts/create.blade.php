<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{$storeRoute}}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">الدور</label>
                    <input type="text" class="form-control" name="name" id="name">
                    <input type="hidden" class="form-control" name="guard_name" id="guard_name" value="admin">
                </div>
            </div>

            {{--            <div class="col-6">--}}
            {{--                <div class="form-group">--}}
            {{--                    <label for="name" class="form-control-label">{{ trns('guard_name') }}</label>--}}
            {{--                   <select name="guard_name" id="guard_name" class="form-control">--}}
            {{--                       <option value="" disabled selected>{{ trns('choose') }}</option>--}}
            {{--                       <option value="admin">{{ trns('admin') }}</option>--}}
            {{--                       <option value="partner">{{ trns('partner') }}</option>--}}
            {{--                   </select>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <div class="col-12">
                <div class="form-group">
                    <!-- Add Select All -->
                    <label for="select_all" class="form-control-label">
                        <input type="checkbox" id="select_all"> اختر جميع الصلاحيات
                    </label>
                </div>
            </div>

            @foreach(\App\Enums\AdminModuleEnum::cases() as $module)
                <div class="col-12">
                    <div class="form-group">
                        <label for="name" class="form-control-label">
                            <input type="checkbox" class="module-checkbox"
                                   data-module="{{ $module->value }}"> {{ $module->value }}
                        </label>
                        <div class="row">
                            @foreach($module->permissions() as $permission)
                                <div class="col-6">
                                    <label for="{{$permission}}" class="form-control permission-label badge">
                                        <input type="checkbox" id="{{$permission}}" name="permissions[]"
                                               value="{{$permission}}"
                                               class="permission-checkbox"
                                               data-module="{{ $module->value }}"> {{$permission}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary" id="addButton">حفظ</button>
            </div>
        </div>
    </form>
</div>
<script>
    $('.module-checkbox').click(function () {
        var module = $(this).data('module');
        var checked = this.checked;
        $(`.permission-checkbox[data-module="${module}"]`).each(function () {
            this.checked = checked;
        });
    });

    $('.permission-checkbox').click(function () {
        var module = $(this).data('module');
        var allChecked = $(`.permission-checkbox[data-module="${module}"]`).length === $(`.permission-checkbox[data-module="${module}"]:checked`).length;
        $(`.module-checkbox[data-module="${module}"]`).prop('checked', allChecked);
    });
</script>
<!-- jQuery for Select All functionality -->
<script>
    $('#select_all').click(function () {
        var checked = this.checked;
        $('.permission-checkbox').each(function () {
            this.checked = checked;
        });
    });
</script>

<!-- Dropify script -->
<script>
    $('.dropify').dropify();
</script>

<!-- Custom CSS for checkbox styling data -->
<style>
    .permission-label {
        display: inline-block;
        margin-right: 15px;
    }

    .permission-checkbox {
        margin-right: 8px;
        transform: scale(1.2); /* Increase size */
    }

    /* Optional: Add more styles to improve the look */
</style>


<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });</script>
