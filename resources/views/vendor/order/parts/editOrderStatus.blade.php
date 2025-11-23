<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">
<div class="col-6">

     <h4>حاله الطلب : {{ App\Enums\OrderStatus::from($obj->order_status->status)->lang() }}</h4>
</div>
<div class="col-6">
    <h4>المبلغ المطلوب دفعه : {{ $obj->required_to_pay - $obj->order_status->paid }}</h4>
</div>

            <div class="col-6 mt-4">
                <label class="form-control-label d-block">هل تريد امهال  {{ $obj->client->name }}</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_graced" id="show_date_checkbox" style="margin-right: 10px;">
                </div>
            </div>

            <div class="col-6 mt-4" >
                <div class="form-group" id="paid_wrapper">
                    <label for="paid" class="form-control-label">المبلغ</label>
                    <input type="number" step="0.01" min="0" max="{{ $obj->required_to_pay - $obj->order_status->paid}}" class="form-control" name="paid" id="paid">
                </div>
                <div class="form-group" id="grace_period_wrapper" style="display: none;">
                    <label for="grace_period" class="form-control-label"> ممده المهله</label>
                    <input type="number" name="grace_period" id="grace_period" class="form-control">
                </div>
            </div>

             <div class="col-6"  >

            </div>


            <div class="col-12">
                <label for="note" class="form-control-label">ملاحظات</label>
                <textarea name="note" id="note" class="form-control" rows="5">{{ $obj->order_status->note }}</textarea>
            </div>


        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
        </div>
    </form>
</div>

<script>
   $(document).ready(function () {
    $('#show_date_checkbox').on('change', function () {
        if ($(this).is(':checked')) {
            $('#grace_period_wrapper').slideDown(1000);
            $('#paid_wrapper').slideUp(1000);
            $('#paid').val('');
        } else {
            $('#grace_period_wrapper').slideUp(1000);
            $('#grace_period').val('');
            $('#paid_wrapper').slideDown(1000);
        }
    });
});

</script>
