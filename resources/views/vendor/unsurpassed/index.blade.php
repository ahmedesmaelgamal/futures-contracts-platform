@extends('vendor.layouts.master')
@section('title')
    {{ config()->get('app.name') }}
@endsection
<style>
    .custom-select-lg.select2-hidden-accessible+.select2-container .select2-selection--single {
        font-size: 15px;
        padding-left: 70px;
    }

    .custom-select-lg.select2-hidden-accessible+.select2-container .select2-selection__rendered {
        padding-left: 70px;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-one-tab" data-bs-toggle="tab" href="#tab-one" role="tab"
                        aria-controls="tab-one" aria-selected="true">المتعثرين</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-two-tab" data-bs-toggle="tab" href="#tab-two" role="tab"
                        aria-controls="tab-two" aria-selected="false">متعثرين المكتب </a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- التبويب الأول -->
                <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one-tab">
                    <div class="card">


                        <div class="card-body">
                            <div class="table-responsive">

                                <div class="mr-auto col-4 ml-3">
                                    <label>ابحث <span id="digitsCount"
                                            style="color: blue; margin: 15px; font-weight: bold;">0</span></label>
                                    <input type="text" id="searchByNationalId" class="form-control"
                                        placeholder="ابحث برقم الهوية">
                                </div>
                                <table class="table table-bordered text-nowrap w-100" id="dataTableWithoutButtons">
                                    <thead>
                                        <tr class="fw-bolder text-muted bg-light">
                                            <th>#</th>
                                            <th>اسم المستخدم</th>
                                            <th>رقم الهويه</th>
                                            <th> اسم المستثمر</th>
                                            <th>رقم الهاتف</th>
                                            <th>المكتب التابع له</th>
                                            <th>رقم هاتف المكتب</th>
                                            <th>حاله العميل</th>
                                            <th>ألإجراءات</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- التبويب الثاني -->
                <div class="tab-pane fade" id="tab-two" role="tabpanel" aria-labelledby="tab-two-tab">

                    <div class="card">
                        <div class="card-header">
                            <div class="form-group row">
                                <div class="col-6">
                                    <label for="email" class="form-control-label">المستثمر
                                    </label>
                                    <select name="investor_id" id="investor_id"
                                        class="form-control select2 custom-select-lg">
                                        <option value="">الكل</option>
                                        @foreach ($investors as $investor)
                                            <option value="{{ $investor->id }}">{{ $investor->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <h3 class="card-title"></h3>

                            <div>
                                @can('create_unsurpassed')
                                    <button class="btn btn-secondary btn-icon text-white addBtn">أضافه</button>

                                    <button class="btn btn-secondary btn-icon text-white addExcelFile"> ملف اكسل</button>
                                @endcan


                                @can('delete_unsurpassed')
                                    <button class="btn btn-danger btn-icon text-white" id="bulk-delete">
                                        <span><i class="fe fe-trash"></i></span> حذف المحدد
                                    </button>
                                @endcan

                                <a href="{{ route('unsurpasseds.download.example') }}"
                                    class="btn btn-primary btn-icon text-white">
                                    <span><i class="fe fe-download"></i></span> تحميل مثال
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap w-100" id="dataTable">
                                    <thead>
                                        <tr class="fw-bolder text-muted bg-light">

                                            <th class="min-w-25px">
                                                <input type="checkbox" id="select-all">
                                            </th>
                                            <th>#</th>
                                            <th>اسم المستخدم</th>
                                            <th>رقم الهويه</th>
                                            <th> اسم المستثمر</th>
                                            <th>رقم الهاتف</th>
                                            <th>المكتب التابع له</th>
                                            <th>رقم هاتف المكتب</th>
                                            <th> المبلغ المطلوب سداده</th>
                                            <th>حاله العميل</th>
                                            <th>ألإجراءات</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pay MODAL -->
        <div class="modal fade" id="pay_modal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel"
            aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="payModalLabel">دفع</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="pay_id" name="id" type="hidden">
                        <p>هل انت متأكد من دفع <span id="title" class="text-success fw-bold"></span>؟</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="dismiss_pay_modal">
                            إغلاق
                        </button>
                        <button type="button" class="btn btn-success" id="pay_btn">
                            <span class="btn-text">ادفع الآن</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->

        <!--Delete MODAL -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>هل انت متاكد من حذف هذا العنصر <span id="title" class="text-danger"></span>?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal"
                            id="dismiss_delete_modal">
                            أغلاق
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">حذف !</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->

        <!-- add stock Modal -->
        <div class="modal fade" id="addStock" data-backdrop="static" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">التفاصيل</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body1">

                    </div>
                </div>
            </div>
        </div>
        <!-- add stock Modal -->

        <!-- Create Or Edit Modal -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog"
            aria-hidden="true">
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
        <!-- Create Or Edit Modal -->
        <!-- addExcelFile Modal -->
        <div class="modal fade" id="addExcelFile" data-backdrop="static" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal5">التفاصيل</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-excel-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- addExcelFile Modal -->
        <!-- delete selected  Modal -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog"
            aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">تأكيد الحذف</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد من أنك تريد حذق العناصر المحدده</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء
                        </button>
                        <button type="button" class="btn btn-danger" id="confirm-delete-btn">حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete selected  Modal -->


        <!-- Modals -->
        @include('vendor.layouts.myAjaxHelper')
    </div>

    <style>
        .dataTables_processing {
            display: none !important;
        }
    </style>
@endsection

@section('ajaxCalls')
    <script>
        var dataTableWithoutButtons = $('#dataTableWithoutButtons').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [0, "DESC"]
            ],
            ajax: {
                url: '{{ route($route . '.index') }}',
                type: 'GET',
                data: function(d) {
                    let value = $('#searchByNationalId').val();
                    d.national_id = /^\d{10}$/.test(value) ? value : 'invalid_nid';
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id',
                    visible: false,
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: false
                },
                {
                    data: 'national_id',
                    name: 'national_id',
                    orderable: false
                }, {
                    data: 'investor_name',
                    name: 'investor_name',
                    orderable: true
                },
                {
                    data: 'phone',
                    name: 'phone',
                    orderable: false
                },
                {
                    data: 'office_name',
                    name: 'office_name',
                    orderable: false
                },
                {
                    data: 'office_phone',
                    name: 'office_phone',
                    orderable: false
                },
                {
                    data: 'client_status',
                    name: 'client_status',
                    orderable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ],
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            language: {
                search: "_INPUT_",
                processing: "",

                searchPlaceholder: "ابحث...",
                emptyTable: "لا توجد بيانات متاحة في الجدول",
                info: "عرض _START_ إلى _END_ من أصل _TOTAL_ سجل",
                infoEmpty: "عرض 0 إلى 0 من أصل 0 سجل",
                lengthMenu: "عرض _MENU_ سجل",
            },
            deferLoading: 0,
            searching: false
        });

        $('#searchByNationalId').on('input', function() {
            let val = $(this).val();
            $('#digitsCount').text(val.length);
            dataTableWithoutButtons.ajax.reload();
        });

        $(document).ready(function() {
            $('#searchByNationalId').val('');
            $('#digitsCount').text('0');
        });


        // الجدول الثاني بدون أزرار
        var dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [1, "DESC"]
            ],
            ajax: {
                url: '{{ route('myUnsurpassed') }}',
                type: 'GET',
                data: function(d) {
                    d.investor_id = $('#investor_id').val();
                }
            },
            columns: [

                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (row.action && row.action.includes('لايمكنك اتخاد اي احراء')) {
                            return '';
                        }

                        return `<input type="checkbox" class="delete-checkbox" value="${row.id}">`;
                    }
                },

                {
                    data: 'id',
                    name: 'id',
                    orderable: true,
                    visible: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: true
                },
                {
                    data: 'national_id',
                    name: 'national_id',
                    orderable: true
                },
                {
                    data: 'investor_name',
                    name: 'investor_name',
                    orderable: true
                },
                {
                    data: 'phone',
                    name: 'phone',
                    orderable: true
                },
                {
                    data: 'office_name',
                    name: 'office_name',
                    orderable: true
                },
                {
                    data: 'office_phone',
                    name: 'office_phone',
                    orderable: true
                },
                {
                    data: 'debt',
                    name: 'debt',
                    orderable: true
                },
                {
                    data: 'client_status',
                    name: 'client_status',
                    orderable: true
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true
                },
            ],
            language: {
                search: "_INPUT_",
                orderable: true,
                searchable: false,
                searchPlaceholder: "ابحث...",
                emptyTable: "لا توجد بيانات متاحة في الجدول",
                info: "عرض _START_ إلى _END_ من أصل _TOTAL_ سجل",
                infoEmpty: "عرض 0 إلى 0 من أصل 0 سجل",
                lengthMenu: "عرض _MENU_ سجل",
            },
        });

        // باقي السكريبتات كما هي
        deleteScript('{{ route($route . '.destroy', ':id') }}');
        deleteSelected('{{ route($route . '.deleteSelected') }}');
        showAddModal('{{ route($route . '.create') }}');
        addScript();
        showEditModal('{{ route($route . '.edit', ':id') }}');
        editScript();

        $('#loadData').click(function() {
            dataTable.ajax.reload();
        });
        $('#investor_id').on('change', function() {
            dataTable.ajax.reload();
        });


        $(document).on('click', '.addExcelFile', function() {
            let routeOfShow = '{{ route('unsurpasseds.add.excel') }}';
            $('#modal-excel-body').html(loader);
            $('#addExcelFile').modal('show');
            setTimeout(function() {
                $('#modal-excel-body').load(routeOfShow);
            }, 250);
        });
    </script>

    <script>
        $(document).on('click', '[data-bs-target="#pay_modal"]', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');

            // خزن القيم داخل المودال
            $('#pay_id').val(id);
            $('#title').text(title);

            // خزن الـ id داخل الزر نفسه إن أردت
            $('#pay_btn').data('id', id);
        });

        $(document).on('click', '#pay_btn', function() {
            var id = $(this).data('id');
            console.log("Paying ID:", id);

            var routeTemplate = "{{ route('unsurpasseds.pay', ':id') }}";
            var route = routeTemplate.replace(':id', id);

            var $btn = $('#pay_btn');
            $btn.attr('disabled', true);
            $btn.find('.btn-text').text('جارٍ الدفع...');
            $btn.find('.spinner-border').removeClass('d-none');

            $.ajax({
                type: 'GET',
                url: route,
                data: {
                    '_token': "{{ csrf_token() }}",
                    'id': id
                },
                success: function(data) {
                    $("#dismiss_pay_modal")[0].click();
                    if (data.status === 200) {
                        $('#dataTable').DataTable().ajax.reload();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr) {
                    toastr.error("حدث خطأ أثناء الدفع.");
                    console.error(xhr.responseText);
                },
                complete: function() {
                    $btn.attr('disabled', false);
                    $btn.find('.btn-text').text('ادفع الآن');
                    $btn.find('.spinner-border').addClass('d-none');
                }
            });
        });
    </script>
@endsection
