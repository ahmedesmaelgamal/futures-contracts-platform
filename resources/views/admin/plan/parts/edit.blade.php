<form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
    @csrf
    @method('PUT')

    <input type="hidden" name="plan_id" value="{{ $obj->id }}">

    <div class="row">
        <!-- Name Field -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="name" class="form-control-label">الإسم</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $obj->name }}" required>
            </div>
        </div>

        <!-- Price Field -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="price" class="form-control-label">السعر</label>
                <input type="number" class="form-control" name="price" id="price" value="{{ $obj->price }}" step="0.01"
                       required>
            </div>
        </div>

        <!-- Period Field -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="period" class="form-control-label">المدة بالأيام</label>
                <input type="number" class="form-control" name="period" id="period" value="{{ $obj->period }}" required>
            </div>
        </div>

        <!-- Discount Field -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="discount" class="form-control-label">الخصم</label>
                <input type="number" class="form-control" name="discount" id="discount" value="{{ $obj->discount }}">
            </div>
        </div>

        <!-- Description Field -->
        <div class="col-md-12">
            <div class="form-group">
                <label for="description" class="form-control-label">الوصف</label>
                <textarea rows="3" class="form-control" name="description"
                          id="description">{{ $obj->description }}</textarea>
            </div>
        </div>

        <!-- Image Upload -->
        <div class="col-md-12">
            <div class="form-group">
                <label for="image" class="form-control-label">الصورة</label>
                <input type="file" class="form-control dropify" name="image" id="image"
                       data-default-file="{{ asset($obj->image) }}">
            </div>
        </div>
    </div>

    <!-- Plans Section -->
    {{-- <button type="button" class="btn btn-success mt-2" id="add_plan">
        <i class="fe fe-plus"></i> إضافة تفاصيل
    </button> --}}
    <div class="col-12">
        <div id="plans_container">
            @foreach($obj->details as $index => $plan)
                <div class="row plan-row border p-3 mb-2">
                    <div class="col-4">
                        <div class="form-group" style="pointer-events: none; opacity: 0.6;">
                            <label class="form-control-label">اختر نوع</label>
                            <select class="form-control plan-select" name="plans[{{ $index }}][key]" required >
                                <option selected disabled value="">اختر نوع</option>
                                <option value="Vendor" {{ $plan->key == 'Vendor' ? 'selected' : '' }}>الموظفين</option>
                                <option value="Branch" {{ $plan->key == 'Branch' ? 'selected' : '' }}>الفروع</option>
                                <option value="Investor" {{ $plan->key == 'Investor' ? 'selected' : '' }}>المستثمرين</option>
                                <option value="Order" {{ $plan->key == 'Order' ? 'selected' : '' }}>الطلبات</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-control-label">العدد</label>
                            <input type="number" class="form-control plan-value" name="plans[{{ $index }}][value]"
                                   value="{{ $plan->value }}" required>
                        </div>
                    </div>

                    {{-- <div class="col-2 d-flex align-items-center">
                        <div class="form-group">
                            <label class="form-control-label">غير محدود</label>
                            <input type="hidden" name="plans[{{ $index }}][is_unlimited]" value="0">
                            <input type="checkbox" class="unlimited-checkbox" name="plans[{{ $index }}][is_unlimited]"
                                   value="1" {{ $plan->is_unlimited ? 'checked' : '' }}>
                        </div>
                    </div> --}}

                    <div class="col-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger remove-plan">
                            <i class="fe fe-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal Footer -->
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary" id="editButton">حفظ التعديلات</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('.dropify').dropify();
        $('select').select2({
            dropdownParent: $('#editOrCreate .modal-content')
        });

        let planCount = {{ count($obj->details) }};

        // التحقق عند تحميل الصفحة لتعطيل حقل الإدخال إذا كان "غير محدود" محددًا
        $('.plan-row').each(function () {
            let inputField = $(this).find('.plan-value');
            let checkbox = $(this).find('.unlimited-checkbox');

            if (checkbox.is(':checked')) {
                inputField.prop('disabled', true); // تعطيل الإدخال
            }
        });

        // عند الضغط على زر "إضافة تفاصيل"
        $('#add_plan').on('click', function () {
            planCount++;

            let newPlan = `
        <div class="row plan-row border p-3 mb-2">
            <div class="col-4">
                <div class="form-group">
                    <label class="form-control-label">اختر نوع</label>
                    <select class="form-control plan-select" name="plans[${planCount}][key]" required>
                        <option selected disabled value="">اختر نوع</option>
                      <option value="Vendor">الموظفين</option>
                <option value="Branch">الفروع</option>
                <option value="Investor">المستثمرين</option>
                <option value="Order">العمليات</option>
                    </select>
                </div>
            </div>

            <div class="col-4">
                <div class="form-group">
                    <label class="form-control-label">Value</label>
                    <input type="text" class="form-control plan-value" name="plans[${planCount}][value]" required>
                </div>
            </div>

            {{-- <div class="col-2 d-flex align-items-center">
                <div class="form-group">
                    <label class="form-control-label">غير محدود</label>
                    <input type="hidden" name="plans[${planCount}][is_unlimited]" value="0">
                    <input type="checkbox" class="unlimited-checkbox" name="plans[${planCount}][is_unlimited]" value="1">
                </div>
            </div> --}}

            <div class="col-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger remove-plan">
                    <i class="fe fe-trash"></i>
                </button>
            </div>
        </div>`;

            $('#plans_container').append(newPlan);
        });

        // عند تغيير تحديد "غير محدود"
        $('#plans_container').on('change', '.unlimited-checkbox', function () {
            let inputField = $(this).closest('.plan-row').find('.plan-value');
            let hiddenInput = $(this).closest('.plan-row').find('input[type="hidden"]');

            if ($(this).is(':checked')) {
                inputField.prop('disabled', true).val('');  // تعطيل وإفراغ الإدخال
                hiddenInput.val('1');  // ضبط القيمة على 1
            } else {
                inputField.prop('disabled', false);
                hiddenInput.val('0');  // إعادة ضبط القيمة
            }
        });

        // عند إدخال قيمة، يتم إلغاء تحديد "غير محدود" تلقائيًا
        $('#plans_container').on('input', '.plan-value', function () {
            let checkbox = $(this).closest('.plan-row').find('.unlimited-checkbox');
            let hiddenInput = $(this).closest('.plan-row').find('input[type="hidden"]');

            if ($(this).val().trim() !== '') {
                checkbox.prop('checked', false);
                hiddenInput.val('0');  // إعادة ضبط قيمة hidden input
                $(this).prop('disabled', false); // تأكيد عدم تعطيل الإدخال
            }
        });

        // عند الضغط على زر "حذف"
        $('#plans_container').on('click', '.remove-plan', function () {
            $(this).closest('.plan-row').remove();
        });
    });

</script>
