<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{$updateRoute}}">
        @csrf
        {{--        @method('PUT')--}}
        <input type="hidden" value="{{\Illuminate\Support\Facades\Auth::id()}}" name="id">
        <input type="hidden" value="{{ \Illuminate\Support\Facades\Auth::user()->password }}" name="password">        <div class="row">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="image" class="form-control-label">{{ trns('image') }}</label>
                        <input type="file" class="dropify" name="image" id="image">
                    </div>
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
        dropdownParent: $('#updateProfileImage .modal-content')

    });
</script>
