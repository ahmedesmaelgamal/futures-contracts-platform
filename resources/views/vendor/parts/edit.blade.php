<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{$updateRoute}}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{$admin->id}}" name="id">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{  trns('name')}}</label>
                    <input type="text" class="form-control" name="name" value="{{$admin->name}}" id="name">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('role') }}</label>
                    {{--                    <input type="text" class="form-control" name="name" id="name">--}}
{{--                        @dd($admin->getRoleNames()->first())--}}
                    <select  id="role_id" name="role_id" class="form-control">
                        @foreach($roles as $role)
                            <option value="{{$role->id}}" @if($role->name == $admin->getRoleNames()->first()) selected @endif>{{$role->name}}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{  trns('user_name')}}</label>
                    <input type="text" class="form-control" name="user_name" value="{{$admin->user_name}}"
                           id="user_name">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="code" class="form-control-label">{{ trns('code') }}</label>
                    <span class="form-control text-center">{{ $admin->code }}</span>
                    <input hidden type="hidden" class="form-control" name="code" value="{{ $admin->code }}" id="code">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">{{ trns('email') }}</label>
                    <input type="text" class="form-control" name="email" value="{{$admin->email}}" id="email">
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
            <button type="submit" class="btn btn-success" id="updateButton">{{ trns('update') }}</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify()
</script>
<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });
</script>
