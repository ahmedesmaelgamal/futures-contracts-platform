<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{$storeRoute}}">
        @csrf
{{--        <div class="row">--}}
{{--            <div class="col-12">--}}
{{--                <div class="form-group">--}}
{{--                    <label for="image" class="form-control-label">{{ trns('image') }}</label>--}}
{{--                    <input type="file" class="dropify" name="image" id="image">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('name') }}</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('role') }}</label>
                    {{--                    <input type="text" class="form-control" name="name" id="name">--}}
                    <select  id="role_id" name="role_id" class="form-control">
                        @foreach($roles as $role)
                            <option value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{  trns('user_name')}}</label>
                    <input type="text" class="form-control" name="user_name" id="user_name">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="code" class="form-control-label">{{ trns('code') }}</label>
                    <span class="form-control text-center">{{ $code }}</span>
                    <input hidden type="hidden" class="form-control" name="code" value="{{ $code }}" id="code">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">{{ trns('email') }}</label>
                    <input type="text" class="form-control" name="email" id="email">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">{{ trns('password') }}</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">{{ trns('password_confirmation') }}</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password">
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('close') }}</button>
            <button type="submit" class="btn btn-primary" id="addButton">{{ trns('save') }}</button>
        </div>

    </form>
</div>


<script>
    $('.dropify').dropify();
</script>

<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });
</script>

{{--<script>--}}
{{--    $(document).ready(function() {--}}

{{--        $('#role_id').on('change', function() {--}}
{{--            $x1= $(this).val();--}}

{{--            console.log('Selected Role ID:', {{\App\Enums\RoleEnum::tryFrom(3)}});--}}

{{--            @php--}}

{{--                $x1=    \App\Enums\RoleEnum::tryFrom(3);--}}

{{--                echo $x1;--}}

{{--            @endphp--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}
