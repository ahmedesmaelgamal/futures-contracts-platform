<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{$updateRoute}}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">اسم الصلاحية</label>
                    <input type="text" class="form-control" value="{{$obj->name}}" name="name" id="name">
                    <input type="hidden" class="form-control" name="guard_name" id="guard_name" value="admin">
                </div>
            </div>

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
                        <label for="name" class="form-control-label">{{ $module->value }}</label>
                        <div class="row">
                            @foreach($module->permissions() as $permission)
                                <div class="col-6">
                                    <label for="{{$permission}}" class="form-control permission-label badge">
                                        <input type="checkbox" id="{{$permission}}" name="permissions[]"
                                               value="{{$permission}}"
                                               {{ in_array($permission,$old_permissions) ? 'checked="checked"' : '' }}
                                               class="permission-checkbox"> {{$permission}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
        </div>
    </form>
</div>

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

<!-- Custom CSS for checkbox styling -->
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
