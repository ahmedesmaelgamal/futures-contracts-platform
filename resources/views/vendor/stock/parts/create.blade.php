<div class="modal-body">
    <form method="POST" id="addStockBase" class="addStockBase" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">

            <div class="col-6">
                <div class="form-group">
                    <label for="operation" class="form-control-label">نوع العملية</label>
                    <select class="form-control" name="operation" id="operation">
                        <option value="" selected disabled>اختر نوع العملية</option>
                        <option value="1">أضافة</option>
                        <option value="0">أنقاص</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="category_id" class="form-control-label">الصنف</label>
                    <select class="form-control" name="category_id" id="category_id">
                        <option value="" selected disabled>اختر الصنف</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="investor_id" class="form-control-label">المستثمر</label>
                    <select class="form-control" name="investor_id" id="investor_id">
                        <option value="" selected disabled>اختر المستثمر</option>
                        @foreach ($investors as $investor)
                            <option value="{{ $investor->id }}">{{ $investor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="branch_id" class="form-control-label">الفرع</label>
                    <select class="form-control" name="branch_id" id="branch_id">
                        <option value="" selected disabled>اختر الفرع</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="quantity " class="form-control-label">
                        الكمية
                    </label>
                    <input type="number" min="1" class="form-control" name="quantity" id="quantity">
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="total_price_add" class="form-control-label">
                        السعر
                    </label>
                    <input type="number" min="1" class="form-control" name="total_price_add"
                        id="total_price_add">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="total_price_sub" class="form-control-label">
                        السعر
                    </label>
                    <input type="number" min="1" class="form-control" name="total_price_sub"
                        id="total_price_sub">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="vendor_commission" class="form-control-label">أجمالي العمولة للمكتب
                    </label>
                    <input type="number" min="0" value="0" class="form-control" name="vendor_commission"
                        id="vendor_commission">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="investor_commission" class="form-control-label">أجمالي العمولة للمستثمر
                    </label>
                    <input type="number" min="0" class="form-control" name="investor_commission" value="0"
                        id="investor_commission">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="sell_diff" class="form-control-label">
                        فروقات اعاده البيع
                    </label>
                    <input type="number" min="0" class="form-control" name="sell_diff" id="sell_diff"
                        value="0">
                </div>
            </div>
        </div>

        <div class="footer" id="addFooter"
            style="padding: 10px; display: flex; justify-content: space-between; margin: 20px 0;">
            <div class="text" style="display: flex; flex-direction: column;">
                <span>الكميه </span>
                <span id="quantity_display" style="text-align: center">0</span>
            </div>
            <div class="text" style="display: flex; flex-direction: column;">
                <span>السعر الاجمالي </span>
                <span id="price_display" style="text-align: center">0</span>
            </div>


            <div class="text" style="display: flex; flex-direction: column;">
                <span> العمولات </span>
                <span id="commission_display" style="text-align: center">0</span>
            </div>

            <div class="text" style="display: flex; flex-direction: column;">
                <span>فروقات اعاده البيع </span>
                <span id="sell_diff_display" style="text-align: center">0</span>
            </div>

            <div class="text" style="display: flex; flex-direction: column;">
                <span>صافي السعر </span>
                <span id="total_display" style="text-align: center">0</span>
            </div>
            <br>

            <div class="text" style="display: flex; flex-direction: column;">
                <span>سعر الوحده </span>
                <span id="division_result" style="text-align: center">0</span>
            </div>
        </div>
        <div class="footer" id="subFooter" style="padding: 10px;">
            <div style="padding: 10px; display: flex; justify-content: space-between; margin: 20px 0;">
                <div class="text" style="display: flex; flex-direction: column;">
                    <span>الكميه </span>
                    <span id="available_quantity" style="text-align: center">0</span>
                </div>
                <div class="text" style="display: flex; flex-direction: column;">
                    <span>السعر الاجمالي </span>
                    <span id="total_price" style="text-align: center">0</span>
                </div>

                <div class="text" style="display: flex; flex-direction: column;">
                    <span> السعر ناقص العموله </span>
                    <span id="total_price_commission" style="text-align: center">0</span>
                </div>
            </div>
            <hr>
            <div style="padding: 10px; display: flex; justify-content: space-evenly; margin: 20px 0;">

                <div class="text" style="display: flex; flex-direction: column;">
                    <span>الكميه السابقه </span>
                    <span id="previous_quantity" style="text-align: center">0</span>
                </div>
                <div class="text" style="display: flex; flex-direction: column;">
                    <span>السعر الاجمالي </span>
                    <span id="total_price_of_previous_quantity" style="text-align: center">0</span>
                </div>


            </div>
            <div style="padding: 10px; display: flex; justify-content: space-evenly; margin: 20px 0;">
                <div class="text" style="display: flex; flex-direction: column;">
                    <span>الكميه المنقصه </span>
                    <span id="sub_quantity" style="text-align: center">0</span>
                </div>
                <div class="text" style="display: flex; flex-direction: column;">
                    <span>السعر المتبقي </span>
                    <span id="total_price_of_sub_quantity" style="text-align: center">0</span>
                </div>
            </div>
            <div style="padding: 10px; display: flex; justify-content: space-evenly; margin: 20px 0;">

                <div class="text" style="display: flex; flex-direction: column;">
                    <span>المتبقي </span>
                    <span id="final_quantity" style="text-align: center">0</span>
                </div>
                <div class="text" style="display: flex; flex-direction: column;">
                    <span> الاجمالي </span>
                    <span id="final_price" style="text-align: center">0</span>
                </div>
            </div>
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButtonStock">حفظ
            </button>
        </div>

    </form>
</div>


<script>
    $('select').select2({
        dropdownParent: $('#addStockBase .modal-content')

    });
</script>
<script>
    $(document).ready(function() {
        function toggleFields() {
            var operation = $("#operation").val();
            $(".form-group").closest('.col-6').fadeOut(200);
            $("#operation").closest('.col-6').fadeIn(300);

            setTimeout(function() {
                if (operation === "1") { // إضافة
                    $("#category_id").closest('.col-6').fadeIn(300);
                    $("#branch_id").closest('.col-6').fadeIn(300);
                    $("#investor_id").closest('.col-6').fadeIn(300);
                    $("#quantity").closest('.col-6').fadeIn(300);
                    $("#total_price_add").closest('.col-6').fadeIn(300);
                    $("#vendor_commission").closest('.col-6').fadeIn(300);
                    $("#investor_commission").closest('.col-6').fadeIn(300);
                    $("#sell_diff").closest('.col-6').fadeIn(300);
                    $("#addFooter").fadeIn(300); // إظهار الفوتر
                    $("#subFooter").fadeOut(200); // إخفاء الفوتر

                } else if (operation === "0") { // إنقاص
                    $("#category_id").closest('.col-6').fadeIn(300);
                    $("#investor_id").closest('.col-6').fadeIn(300);
                    $("#branch_id").closest('.col-6').fadeIn(300);
                    $("#quantity").closest('.col-6').fadeIn(300);
                    $("#total_price_sub").closest('.col-6').fadeIn(300);
                    $("#addFooter").fadeOut(200); // إخفاء الفوتر
                    $("#subFooter").fadeIn(300); // إظهار الفوتر
                }
            }, 200);

            // إخفاء الفوتر عند تحميل الصفحة
            $(".footer").hide();
        }

        function updateTotals() {
            let quantity = parseFloat($("#quantity").val()) || 0;
            let availableQuantity = parseFloat($("#available_quantity").text()) || 0;

            // في حالة العملية "إنقاص"
            if ($("#operation").val() === "0") {
                if (quantity > availableQuantity) {
                    toastr.error('الكمية المدخلة أكبر من الكمية المتاحة!');
                    $("#quantity").val(availableQuantity);
                    quantity = availableQuantity;
                }
            }

            let totalPrice = parseFloat($("#total_price_add").val()) || 0;
            let vendorCommission = parseFloat($("#vendor_commission").val()) || 0;
            let investorCommission = parseFloat($("#investor_commission").val()) || 0;
            let sellDiff = parseFloat($("#sell_diff").val()) || 0;

            let totalCommission = vendorCommission + investorCommission;
            let grandTotal = totalPrice - totalCommission - sellDiff;

            let divisionResult = (quantity !== 0) ? (grandTotal / quantity).toFixed(2) : 0;

            $("#quantity_display").text(quantity);
            $("#price_display").text(totalPrice);
            $("#commission_display").text(totalCommission);
            $("#sell_diff_display").text(sellDiff);
            $("#total_display").text(grandTotal);
            $("#division_result").text(divisionResult);

            // For subtraction operation
            let totalPriceSub = parseFloat($("#total_price_sub").val()) || 0;
            let finalQuantity = parseFloat($("#available_quantity").text()) - quantity || 0;
            let finalPrice = parseFloat($("#total_price").text()) - totalPriceSub || 0;

            $("#sub_quantity").text(quantity);
            $("#total_price_of_sub_quantity").text(totalPriceSub);
            $("#previous_quantity").text(parseFloat($("#available_quantity").text()) || 0);
            $("#total_price_of_previous_quantity").text(parseFloat($("#total_price").text()) || 0);
            $("#final_quantity").text(finalQuantity);
            $("#final_price").text(finalPrice);
        }


        $(".form-group input").on("input", updateTotals);

        $(".form-group").closest('.col-6').hide();
        $("#operation").closest('.col-6').show();
        $("#operation").change(toggleFields);
        toggleFields();
    });


    // add script
    $(document).off('submit', '#addStockBase').on('submit', '#addStockBase', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var url = $('#addStockBase').attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('#addButtonStock').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                    ' ></span> <span style="margin-left: 4px;">أنتظر قليلًا...</span>').attr(
                    'disabled', true);
            },
            success: function(data) {
                if (data.status == 200) {
                    $('#dataTable').DataTable().ajax.reload();
                    toastr.success('تمت العملية بنجاح');
                } else if (data.status == 405) {
                    toastr.error(data.mymessage);
                } else
                    toastr.error('حدث خطأ ما');
                $('#addButtonStock').html(`اضافه`).attr('disabled', false);
                $('#addStockBase').modal('hide')
            },
            error: function(data) {
                if (data.status === 500) {
                    toastr.error('');
                } else if (data.status === 422) {
                    var errors = $.parseJSON(data.responseText);
                    $.each(errors, function(key, value) {
                        if ($.isPlainObject(value)) {
                            $.each(value, function(key, value) {
                                toastr.error(value, 'خطأ');
                            });
                        }
                    });
                } else
                    toastr.error('حدث خطأ ما');
                $('#addButtonStock').html(`اضافة`).attr('disabled', false);
            }, //end error method

            cache: false,
            contentType: false,
            processData: false
        });
    });

    function fetchAvailableStock() {
        var operation = $("#operation").val();
        var categoryId = $("#category_id").val();
        var branchId = $("#branch_id").val();
        var investorId = $("#investor_id").val();

        if (operation === "0" && categoryId && branchId) {
            $.ajax({
                url: "{{ route('vendor.investors.getAvailableStock') }}",
                type: "GET",
                data: {
                    category_id: categoryId,
                    branch_id: branchId,
                    investor_id: investorId
                },
                success: function(response) {
                    if (response.status === 200) {
                        console.log(response);
                        $("#available_quantity").text(response.available);
                        $("#total_price").text(response.total_price);
                        $("#total_price_commission").text(response.total_price_commission);
                        updateTotals();

                    } else {
                        toastr.error("حدث خطأ أثناء جلب البيانات");
                    }
                },
                error: function() {
                    toastr.error("تعذر جلب البيانات، تحقق من الاتصال بالسيرفر");
                }
            });
        }
    }

    // تشغيل الفانكشن عند تغيير نوع العملية أو الصنف أو الفرع
    $("#category_id, #branch_id").change(fetchAvailableStock);
    $("#operation").change(function() {
        if ($(this).val() === "0") {
            fetchAvailableStock();
        } else {
            $("#quantity_display, #add_display, #sell_display").text(0);
        }
    });
</script>

<script>
    // لما تختار المستثمر
    $("#investor_id").off('change').on('change', function() {
        let investorId = $(this).val();
        if (investorId) {
            $.ajax({
                url: '{{ route('vendor.stocks.getBranches') }}',
                method: 'POST',
                data: {
                    investor_id: investorId,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    let branchSelect = $('#branch_id');
                    branchSelect.empty().append('<option disabled selected>اختر الفرع</option>');
                    response.branches.forEach(function(branches) {
                        branchSelect.append(
                            `<option value="${branches.id}">${branches.name}</option>`);
                    });
                },
                error: function() {
                    toastr.error('حدث خطأ أثناء تحميل الأصناف');
                }
            });
        }
    });
</script>
