<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">


        <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-control-label">اختر المكتب</label>
                        <select class="form-control" name="vendor_id">
                            @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ $obj->vendor_id == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
        </div>

        <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-control-label">اختر الخطة</label>
                        <select class="form-control" name="plan_id" id="plan_id">
                            @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ $obj->plan_id == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </div>
        </div>

        <div class="row">
                <div class="form-group col-6">
                    <label for="name" class="form-control-label">من</label>
                    <input type="date" class="form-control" name="from" id="from" value="{{ $obj->from }}" readonly>
                </div>

                <div class="form-group col-6">
                    <label for="name" class="form-control-label">الى</label>
                    <input type="date" class="form-control" name="to" id="to" value="{{ $obj->to }}" readonly>
                </div>
        </div>

        <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">ايصال الدفع</label>
                    <input type="file" class="dropify" name="payment_receipt" id="payment_receipt" data-default-file="{{$obj->payment_receipt}}">
                </div>
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-primary" id="addButton">حفظ</button>
        </div>
    </div>
    </form>
</div>
<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });</script>
