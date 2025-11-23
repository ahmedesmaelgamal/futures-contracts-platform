<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">اختر المكتب</label>
                    <select class="form-control" name="vendor_id">
                        <option selected disabled value="">اختر المكتب</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">اختر الخطة</label>
                    <select class="form-control" name="plan_id" id="plan_id">
                        <option selected disabled value="">اختر الخطة</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-6">
                    <label for="name" class="form-control-label">من</label>
                    <input type="date" class="form-control" name="from" id="from" readonly>
                </div>

                <div class="form-group col-6">
                    <label for="name" class="form-control-label">الى</label>
                    <input type="date" class="form-control" name="to" id="to" readonly>
                </div>
            </div>


            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">ايصال الدفع</label>
                    <input type="file" class="dropify" name="payment_receipt" id="payment_receipt">
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
    });


    $(document).ready(function () {
        // Set default 'from' date when the page loads
        $('#from').val(new Date().toISOString().split('T')[0]);

        $('#plan_id').on('change', function () {
            var plan_id = $(this).val();
            var from = $('#from').val() || new Date().toISOString().split('T')[0]; // Ensure 'from' has a value


            $.ajax({
                url: "{{ route('getToDate') }}",
                type: 'GET',
                data: {plan_id: plan_id, from: from},
                dataType: 'json',
                success: function (response) {
                    if (response.status === 200) {
                        $('#to').val(response.data); // Corrected field name
                    } else {
                        alert('الخطة غير متاحة.');
                    }
                },
                error: function () {
                    alert('حدث خطأ في جلب بيانات الخطة.');
                }
            });

        });
    });


</script>

