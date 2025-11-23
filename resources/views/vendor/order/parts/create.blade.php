<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <label for="national_id" class="form-control-label">رقم الهويه
                    </label>
                    <input type="number" maxlength="10" class="form-control" name="national_id" id="national_id"
                           required>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الاسم </label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="phone" class="form-control-label">رقم الهاتف</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="phone" name="phone" maxlength="9" required
                               style="text-align: left;">
                        <span class="input-group-text">966+</span>
                    </div>
                </div>
            </div>
            <hr>
            <div class="col-6">
                <div class="form-group">
                    <label for="branch_id" class="form-control-label">الفرع</label>
                    <select class="form-control" name="branch_id" id="branch_id" required>
                        <option value="" selected disabled>اختر الفرع</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="investor_id" class="form-control-label">المستثمر</label>
                    <select class="form-control" name="investor_id" id="investor_id" required>
                        <option value="" selected disabled>اختر المستثمر</option>
                        @foreach ($investors as $investor)
                            <option value="{{ $investor->id }}">{{ $investor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="category_id" class="form-control-label">الصنف</label>
                    <select class="form-control" name="category_id" id="category_id" required>
                        <option value="" selected disabled>اختر الصنف</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="quantity" class="form-control-label">
                        الكمية
                    </label>
                    <input type="number" min="1" class="form-control" name="quantity" required id="quantity">
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="expected_price" class="form-control-label">
                        سعر اعاده البيع المتوقع
                    </label>
                    <input type="number" min="0" step="0.01" class="form-control" name="expected_price" readonly value="0"
                           id="expected_price">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="Total_expected_commission" class="form-control-label">
                        اجمالي العموله المتوقعه
                    </label>
                    <input type="number" min="0" step="0.01" class="form-control" name="Total_expected_commission" required readonly
                           value="0"
                           id="Total_expected_commission">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="sell_diff" class="form-control-label">
                        فروقات البيع
                    </label>
                    <input type="number" min="0" step="0.01" class="form-control" value="0" name="sell_diff" id="sell_diff"
                           required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="delivered_price_to_client" class="form-control-label">
                        السعر المسلم للعميل
                    </label>
                    <input type="number" min="0" step="0.01" class="form-control" name="delivered_price_to_client" value="0"
                           id="delivered_price_to_client" required>
                </div>
            </div>

            <input type="hidden" name="vendor_commission" id="vendor_commission" value="0">
            <input type="hidden" name="investor_commission" id="investor_commission" value="0">

            <hr>
            <div class="col-4">
                <div class="form-group">
                    <label for="required_to_pay" class="form-control-label">
                        المبلغ الطلوب سداده
                    </label>
                    <input type="number" min="0" step="0.01" class="form-control" name="required_to_pay" value="0"
                           id="required_to_pay" required>
                </div>
            </div>

            <div class="col-4">
                <div class="form-group">
                    <label for="date" class="form-control-label">
                        تاريخ السداد
                    </label>

                    <input type="date" class="form-control" min="{{ date('Y-m-d') }}" required name="date"
                           id="date">

                </div>
            </div>

            <div class="col-4">
                <div class="form-group">
                    <label for="profit_ratio" class="form-control-label">
                        نسبه الربح
                    </label>

                    <div class="input-group">
                        <input type="number" step="0.01" class="form-control" value="{{ $profit_ratio }}"
                               @if ($is_profit_ratio_static == 1) readonly @endif min="0" max="100"
                               name="profit_ratio" id="profit_ratio">
                        <span class="input-group-text">%</span>
                    </div>

                </div>
            </div>
            <hr>
            <div class="col-6">
                <label class="form-control-label d-block">هل يوجد تقسيط؟</label>
                <div class="" style="min-height: 40px;">
                    <div class="form-check form-switch">
                        <input class="tgl tgl-ios form-check-input" type="checkbox" name="is_installment"
                               id="is_installment" style="margin-right: 0">
                        <label class="tgl-btn" for="is_installment"></label>
                    </div>
                </div>
            </div>

            <div class="col-6" id="installment_number_wrapper" style="display: none;">
                <div class="form-group">
                    <label for="installment_number" class="form-control-label">عدد الأقساط</label>
                    <input type="number" min="1" class="form-control" name="installment_number" id="installment_number">
                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلاق</button>
                <button type="submit" class="btn btn-primary" id="addButton">حفظ
                </button>
            </div>

    </form>
</div>


<script>
    $('.dropify').dropify();
    $('select#branch_id,#investor_id,#category_id').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });
</script>

<script>
    $(document).ready(function () {
        $('#national_id').on('input', function () {
            let nationalId = $(this).val();

            if (nationalId.length === 10) {
                $.ajax({
                    url: '{{ route('vendor.clients.getUserByNationalId') }}',
                    method: 'GET',
                    data: {
                        national_id: nationalId
                    },
                    success: function (response) {
                        if (response.exists) {
                            $('#name').val(response.user.name).prop('readonly', true);
                            $('input[name="phone"]').val(response.user.phone.substring(4))
                                .prop('readonly', true);
                        } else {
                            toastr.error('المستخدم ليس موجود من فضلك أكمل بياناته');
                            $('#name').val('').prop('readonly', false);
                            $('input[name="phone"]').val('').prop('readonly', false);
                        }
                    },
                    error: function () {
                        toastr.error('حدث خطأ أثناء جلب البيانات');
                    }
                });
            } else {
                $('#name').val('').prop('readonly', false);
                $('input[name="phone"]').val('').prop('readonly', false);
            }
        });
    });
</script>
<script>
    function calculateRequiredToPay() {
        let deliveredPrice = parseFloat($('#delivered_price_to_client').val());
        let profitRatio = parseFloat($('#profit_ratio').val());

        if (!isNaN(deliveredPrice) && !isNaN(profitRatio)) {
            let profitAmount = (deliveredPrice * profitRatio) / 100;
            let requiredToPay = deliveredPrice + profitAmount;
            $('#required_to_pay').val(requiredToPay.toFixed(2));
        }
    }

    $(document).ready(function () {
        $('#delivered_price_to_client, #profit_ratio').on('input', calculateRequiredToPay);

    });
</script>

<script>
    $(document).ready(function () {

        // لما تختار الفرع
        $('#branch_id').on('change', function () {
            let branchId = $(this).val();
            if (branchId) {
                $.ajax({
                    url: '{{ route("vendor.orders.getInvestors") }}',
                    method: 'POST',
                    data: {
                        branch_id: branchId,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        let investorSelect = $('#investor_id');
                        investorSelect.empty().append('<option disabled selected>اختر المستثمر</option>');
                        response.investors.forEach(function (investor) {
                            investorSelect.append(`<option value="${investor.id}">${investor.name}</option>`);
                        });

                        // إفراغ واخفاء الأصناف كمان
                        $('#category_id').empty().append('<option disabled selected>اختر الصنف</option>');
                    },
                    error: function () {
                        toastr.error('حدث خطأ أثناء تحميل المستثمرين');
                    }
                });
            }
        });

        // لما تختار المستثمر
        $('#investor_id').on('change', function () {
            let investorId = $(this).val();
            if (investorId) {
                $.ajax({
                    url: '{{ route("vendor.orders.getCategories") }}',
                    method: 'POST',
                    data: {
                        investor_id: investorId,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        let categorySelect = $('#category_id');
                        categorySelect.empty().append('<option disabled selected>اختر الصنف</option>');
                        response.categories.forEach(function (category) {
                            categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
                        });
                    },
                    error: function () {
                        toastr.error('حدث خطأ أثناء تحميل الأصناف');
                    }
                });
            }
        });

    });

</script>
<script>
    function fetchCalculatedFields() {
        let categoryId = $('#category_id').val();
        let investorId = $('#investor_id').val();
        let quantity = parseInt($('#quantity').val());
        let branchId = $('#branch_id').val();

        if (categoryId && investorId && quantity) {
            $.ajax({
                url: '{{ route("vendor.orders.calculatePrices") }}',
                method: 'GET',
                data: {
                    category_id: categoryId,
                    investor_id: investorId,
                    quantity: quantity,
                    branch_id: branchId
                },
                success: function (response) {
                    if (response.available_quantity < quantity) {
                        toastr.warning('الكمية غير متوفرة. الكمية المتاحة هي ' + response.available_quantity);
                        $('#quantity').val(response.available_quantity);
                        quantity = response.available_quantity;
                        $('#quantity').attr('max', response.available_quantity);

                    }

                    $('#expected_price').val(parseFloat(response.expected_price).toFixed(2));
                    $('#Total_expected_commission').val(parseFloat(response.Total_expected_commission).toFixed(2));
                    $('#sell_diff').val(parseFloat(response.sell_diff).toFixed(2));
                    $('#delivered_price_to_client').val(parseFloat(response.expected_price).toFixed(2));
                    $('#investor_commission').val(parseFloat(response.investor_commission).toFixed(2));
                    $('#vendor_commission').val(parseFloat(response.vendor_commission).toFixed(2));
                    calculateRequiredToPay();

                },
                error: function () {
                    toastr.error('حدث خطأ أثناء الحساب');
                }
            });
        }
    }


    $(document).ready(function () {
        $('#category_id, #investor_id, #quantity').on('change input', fetchCalculatedFields);
    });
</script>

<script>
    $(document).ready(function () {
        $('#is_installment').on('change', function () {
            if ($(this).is(':checked')) {
                $('#installment_number_wrapper').slideDown();
            } else {
                $('#installment_number_wrapper').slideUp();
                $('#installment_number').val('');
            }
        });
    });
</script>

<script>
    function handlePhoneInput(inputId) {
        document.getElementById(inputId).addEventListener('input', function (e) {
            let value = e.target.value;

            // Remove leading zero
            if (value.startsWith('0')) {
                value = value.slice(1);
            }

            // Limit to a maximum of 9 digits
            if (value.length > 9) {
                value = value.slice(0, 9);
            }

            e.target.value = value;
        });
    }

    handlePhoneInput('phone');
</script>


