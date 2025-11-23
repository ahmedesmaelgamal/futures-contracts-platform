@extends('vendor.layouts.master')

@section('title')
    أجل

@endsection
@section('page_name')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                        <div class="col-md-6">
                            <label for="investorFilter" class="form-label fw-bold">اختر المستثمر</label>
                            <select id="investorFilter" class="form-control">
                                <option value="" selected disabled>اختر المستثمر</option>
                                @foreach ($investors as $investor)
                                    <option value="{{ $investor->id }}">{{ $investor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="categoryFilter" class="form-label fw-bold">اختر الصنف</label>
                            <select id="categoryFilter" class="form-control">
                                <!-- سيتم تعبئة الأصناف ديناميكيًا -->
                            </select>
                        </div>


                <h3 class="card-title"></h3>
                                {{-- <div class="">
                                    @can('create_stock')
                                        <button class="btn btn-secondary btn-icon text-white addBtnStock">
                                            <span>
                                                <i class="fe fe-plus"></i>
                                            </span> أضافه
                                        </button>
                                    @endcan

                                </div> --}}
                </div>
                <div class="card-body">
                        <!--begin::Table-->
                        <div class="table-responsive" id="tableContainer" style="display:none;">
                        <table class="table table-bordered text-nowrap w-100" id="filterTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">


                                <th class="min-w-50px rounded-end"> اسم الصنف</th>

                                <th class="min-w-50px rounded-end">الكميه المضافه</th>


                                <th class="min-w-50px rounded-end"> الكميه المنقصه</th>

                                <th class="min-w-50px rounded-end">الكميه المطلوبه </th>

                                <th class="min-w-50px rounded-end"> الكميه المتبقيه</th>






                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">

                                    <th class="min-w-25px">#</th>

                                    <th class="min-w-50px rounded-end"> اسم المستثمر</th>

                                    <th class="min-w-50px rounded-end"> رقم هويه المستثمر</th>


                                    <th class="min-w-50px rounded-end">اسم الفرع</th>

                                    <th class="min-w-50px rounded-end">الكميه</th>

                                    <th class="min-w-50px rounded-end">نوع العمليه</th>

                                    <th class="min-w-50px rounded-end"> التاريخ</th>


                                    <th class="min-w-50px rounded-end">اسم الصنف</th>

                                    <th class="min-w-50px rounded-end"> السعر الاجمالي</th>


                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="addStockBase" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">التفاصيل</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">

                    </div>
                </div>
            </div>
        </div>


    </div>
    @include('vendor.layouts.myAjaxHelper')
@endsection
@section('ajaxCalls')
            <script>
                let filterTable = $('#filterTable').DataTable({
                    processing: true,
                    serverSide: false,
                    paging: false,
                    ordering: false,
                    searching: false,
                    info: false,
                    language: {
                        "sEmptyTable": "لا توجد بيانات متاحة في الجدول",
                        "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                        "sInfoEmpty": "إظهار 0 إلى 0 من أصل 0 مدخل",
                        "sInfoFiltered": "(منتقاة من مجموع _MAX_ مدخل)",
                        "sLengthMenu": "أظهر _MENU_ مدخلات",
                        "sLoadingRecords": "جارٍ التحميل...",
                        "sProcessing": "جارٍ المعالجة...",
                        "sSearch": "ابحث:",
                        "sZeroRecords": "لم يعثر على أية سجلات",
                        "oPaginate": {
                            "sFirst": "الأول",
                            "sLast": "الأخير",
                            "sNext": "التالي",
                            "sPrevious": "السابق"
                        }
                    },
                    ajax: {
                        url: "{{route('filteredTable')}}",
                        data: function(d) {
                            d.investor_id = $('#investorFilter').val();
                            d.category_id = $('#categoryFilter').val();
                        }
                    },
                    columns: [
                        { data: 'category', name: 'category' },
                        { data: 'added_quantity', name: 'added_quantity' },
                        { data: 'subtracted_quantity', name: 'subtracted_quantity' },
                        { data: 'ordered_quantity', name: 'ordered_quantity' },
                        {
                            data: null,
                            name: 'remaining_quantity',
                            render: function (data, type, row) {
                                return Number(row.added_quantity) - (Number(row.subtracted_quantity) + Number(row.ordered_quantity));
                            }
                        }
                    ]
                });

                function canLoadTable() {
                    return $('#investorFilter').val() !== ''
                        && $('#categoryFilter').val() !== '';
                }

                $('#investorFilter').on('change', function () {
                    const investorId = $(this).val();

                    if (!investorId) {
                        // لم يتم اختيار المستثمر - إخفاء الجدول ومسح الأصناف
                        $('#categoryFilter').html('');
                        $('#tableContainer').hide();
                        return;
                    }

                    // جلب الأصناف بناءً على المستثمر المختار
                    let url = '{{ route("vendor.getCategoriesByInvestor", ":id") }}'.replace(':id', investorId);

                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (data) {
                            $('#categoryFilter').html(''); // حذف جميع الخيارات السابقة
                            data.forEach(function (category) {
                                $('#categoryFilter').append(`<option value="${category.id}">${category.name}</option>`);
                            });
                            $('#categoryFilter').val(''); // إجبار اختيار جديد
                            $('#tableContainer').hide();
                        },
                        error: function (xhr) {
                            console.error('فشل تحميل الأصناف:', xhr.responseText);
                        }
                    });
                });

                $('#categoryFilter').on('change', function () {
                    if (canLoadTable()) {
                        $('#tableContainer').show();
                        filterTable.ajax.reload();
                    } else {
                        $('#tableContainer').hide();
                    }
                });

                $(document).ready(function () {
                    $('#investorFilter').val('');
                });
            </script>




        <script>
        let dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            language: {
                "sEmptyTable":     "لا توجد بيانات متاحة في الجدول",
                "sInfo":           "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                "sInfoEmpty":      "إظهار 0 إلى 0 من أصل 0 مدخل",
                "sInfoFiltered":   "(منتقاة من مجموع _MAX_ مدخل)",
                "sInfoPostFix":    "",
                "sInfoThousands":  ",",
                "sLengthMenu":     "أظهر _MENU_ مدخلات",
                "sLoadingRecords": "جارٍ التحميل...",
                "sProcessing":     "جارٍ المعالجة...",
                "sSearch":         "ابحث:",
                "sZeroRecords":    "لم يعثر على أية سجلات",
                "oPaginate": {
                    "sFirst":    "الأول",
                    "sLast":     "الأخير",
                    "sNext":     "التالي",
                    "sPrevious": "السابق"
                },
                "oAria": {
                    "sSortAscending":  ": تفعيل لترتيب العمود تصاعدياً",
                    "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
                }
            },
            order: [
                [0, "DESC"]
            ],

            ajax: {
                url: '{{ route($route . '.index') }}',
                data: function(d) {
                    d.investor_id = $('#investorFilter').val();
                }
            },
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    visible: false,
                    searchable: false
                },
                {
                    data: 'investor_id',
                    name: 'investor_id'
                },
                {
                    data: 'investor_national_id',
                    name: 'investor_national_id'
                },
                {
                    data: 'branch_id',
                    name: 'branch_id'
                },
                {
                    data: 'quantity',
                    name: 'quantity'
                },
                {
                    data: 'operation',
                    name: 'operation'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'category_id',
                    name: 'category_id'
                },
                {
                    data: 'total_price',
                    name: 'total_price'
                },
            ]
        });


    </script>









    <script>
        $(document).one('click', '.addBtnStock', function() {
            let id = $(this).data('id');
            $('#modal-body').html(loader)
            $('#addStockBase').modal('show')
            setTimeout(function() {
                $('#modal-body').load('{{ route($route . '.create') }}');
            }, 250);
        });
    </script>
@endsection
