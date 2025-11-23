<meta charset="UTF-8">
<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">
            <!-- Name Field -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الإسم</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                </div>
            </div>

            <!-- Price Field -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price" class="form-control-label">السعر</label>
                    <input type="number" class="form-control" name="price" id="price" step="0.01" required>
                </div>
            </div>

            <!-- Period Field -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="period" class="form-control-label">المدة بالأيام</label>
                    <input type="number" class="form-control" name="period" id="period" required>
                </div>
            </div>

            <!-- Discount Field -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="discount" class="form-control-label">الخصم</label>
                    <input type="number" class="form-control" name="discount" id="discount" value="0">
                </div>
            </div>

            <!-- Description Field -->
            <div class="col-md-12">
                <div class="form-group">
                    <label for="description" class="form-control-label">الوصف</label>
                    <textarea rows="3" class="form-control" name="description" id="description"></textarea>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="col-md-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">الصورة</label>
                    <input type="file" class="form-control dropify" name="image" id="image">
                </div>
            </div>
        </div>

        <!-- Plans Section -->
        <button type="button" class="btn btn-success mt-2" id="add_plan">
            <i class="fe fe-plus"></i> إضافة تفاصيل
        </button>

        <div class="col-12">
            <div id="plans_container"></div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-primary" id="addButton">حفظ</button>
        </div>
    </form>
</div>

<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')
    });

    $(document).ready(function () {
        let planCount = 0;
        let selectedOptions = new Set();

        $('#addButton').prop('disabled', true);

        $('#add_plan').on('click', function () {
            planCount++;

            let selectKey = `
            <select class="form-control plan-select" name="plans[${planCount}][key]" required>
                <option selected disabled value="">اختر نوع</option>
                <option value="Vendor">الموظفين</option>
                <option value="Branch">الفروع</option>
                <option value="Investor">المستثمرين</option>
                <option value="Order">الطلبات</option>
            </select>`;

            let newPlan = `
            <div class="row plan-row border p-3 mb-2">
                <div class="col-4">
                    <div class="form-group">
                        <label class="form-control-label">اختر نوع</label>
                        ${selectKey}
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label class="form-control-label">العدد</label>
                        <input type="number" class="form-control plan-value" name="plans[${planCount}][value]" required>
                    </div>
                </div>

                <div class="col-2 d-flex align-items-center">
                    <button type="button" class="btn btn-danger remove-plan">
                        <i class="fe fe-trash"></i>
                    </button>
                </div>
            </div>`;

            $('#plans_container').append(newPlan);
            updateSelectOptions();
            toggleSaveButton();
        });

        $('#plans_container').on('change', '.plan-select', function () {
            let selectedValue = $(this).val();
            let previousValue = $(this).data('previous') || '';

            if (previousValue) {
                selectedOptions.delete(previousValue);
            }
            if (selectedValue) {
                selectedOptions.add(selectedValue);
            }

            $(this).data('previous', selectedValue);
            updateSelectOptions();
        });

        $('#plans_container').on('change', '.unlimited-checkbox', function () {
            let inputField = $(this).closest('.plan-row').find('.plan-value');
            if ($(this).is(':checked')) {
                inputField.prop('disabled', true).val('');
            } else {
                inputField.prop('disabled', false);
            }
        });

        $('#plans_container').on('click', '.remove-plan', function () {
            let removedValue = $(this).closest('.plan-row').find('.plan-select').val();
            if (removedValue) {
                selectedOptions.delete(removedValue);
            }
            $(this).closest('.plan-row').remove();
            updateSelectOptions();
            toggleSaveButton();
        });

        function updateSelectOptions() {
            $('.plan-select').each(function () {
                let currentValue = $(this).val();
                $(this).find('option').each(function () {
                    let optionValue = $(this).val();
                    if (optionValue && selectedOptions.has(optionValue) && optionValue !== currentValue) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            });
        }

        function toggleSaveButton() {
            if ($('.plan-row').length > 0) {
                $('#addButton').prop('disabled', false);
            } else {
                $('#addButton').prop('disabled', true);
            }
        }

        for (let i = 0; i < 4; i++) {
            $('#add_plan').trigger('click');
        }
        $('#add_plan').hide();
    });
</script>
