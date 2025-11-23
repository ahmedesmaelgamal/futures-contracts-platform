@extends('vendor/layouts/master')

@section('title')
    {{ config()->get('app.name') }}
@endsection
@section('page_name')
@endsection

@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-end">

                        <div class="col-3 mb-3 d-flex flex-column">
                            <label for="investorFilter">اختر المستثمر</label>
                            <select id="investorFilter" class="form-control w-100">
                                <option value="">الكل</option>
                                @foreach ($investors as $investor)
                                    <option value="{{ $investor->id }}">{{ $investor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-2 mb-3">
                            <label for="monthFilter">اختر الشهر</label>
                            <select id="monthFilter" class="form-control">
                                <option value="">الكل</option>
                                <option value="1">يناير</option>
                                <option value="2">فبراير</option>
                                <option value="3">مارس</option>
                                <option value="4">أبريل</option>
                                <option value="5">مايو</option>
                                <option value="6">يونيو</option>
                                <option value="7">يوليو</option>
                                <option value="8">أغسطس</option>
                                <option value="9">سبتمبر</option>
                                <option value="10">أكتوبر</option>
                                <option value="11">نوفمبر</option>
                                <option value="12">ديسمبر</option>
                            </select>
                        </div>


                        <div class="col-2 mb-3">
                            <label for="yearFilter">اختر السنة</label>
                            <select id="yearFilter" class="form-control">
                                <option value="">الكل</option>
                                @for ($year = 2025; $year <= now()->year; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-3 mb-3">
                            <label for="status">اختر الحاله</label>
                            <select id="status" class="form-control">
                                <option value="">الكل</option>
                                <option value="0">غير مسدد</option>
                                <option value="1">مسدد جزئيا</option>
                                <option value="2">ممهل لمده محدود</option>
                                <option value="3">مسدد بالكامل</option>

                            </select>
                        </div>
                    </div>

                    @can('create_order')
                        <div class="mt-3 text-end">
                            <button class="btn btn-secondary btn-icon text-white addBtn">
                                <span><i class="fe fe-plus"></i></span> إضافة
                            </button>
                        </div>
                    @endcan
                </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">

                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-50px rounded-end">اسم العميل</th>
                                    <th class="min-w-50px rounded-end">رقم هويه العميل</th>
                                    <th class="min-w-50px rounded-end">تاريخ الطلب</th>
                                    {{--                                <th class="min-w-50px rounded-end">حاله العميل </th> --}}
                                    <th class="min-w-50px rounded-end">الكميه</th>
                                    <th class="min-w-50px rounded-end">تاريخ السداد</th>
                                    <th class="min-w-50px rounded-end"> اسم المستثمر</th>
                                    <th class="min-w-50px rounded-end"> رقم الطلب</th>
                                    <th class="min-w-50px rounded-end"> حاله الطلب</th>
                                    <th class="min-w-50px rounded-end"> المبلغ الكلي</th>
                                    <th class="min-w-50px rounded-end"> المبلغ المدفوع</th>
                                    <th class="min-w-50px rounded-end">المتبقي</th>
                                    <th class="min-w-50px rounded-end">ألإجراءات</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete MODAL -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>هل أنت متأكد أنك تريد حذف <span id="title" class="text-danger"></span>؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="dismiss_delete_modal">
                            إغلاق
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">حذف!</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->


        <!-- Create Or Edit Modal -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
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


    </div>
    @include('vendor/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        let dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [1, "DESC"]
            ],
            ajax: {
                url: '{{ route($route . '.index') }}',
                data: function(d) {
                    d.investor_id = $('#investorFilter').val();
                    d.month = $('#monthFilter').val();
                    d.year = $('#yearFilter').val();
                    d.status = $('#status').val();
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
                    data: 'client_id',
                    name: 'client_id'
                },
                {
                    data: 'client_national_id',
                    name: 'client_national_id'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                // {data: 'client_status', name: 'client_status'},
                {
                    data: 'quantity',
                    name: 'quantity'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'investor_id',
                    name: 'investor_id'
                },
                {
                    data: 'order_number',
                    name: 'order_number'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'required_to_pay',
                    name: 'required_to_pay'
                },
                {
                    data: 'paid',
                    name: 'paid'
                },
                {
                    data: null,
                    name: 'diff',
                    render: function(data, type, row) {
                        let required = parseFloat(row.required_to_pay) || 0;
                        let paid = parseFloat(row.paid) || 0;
                        return (required - paid).toLocaleString();
                    },
                    title: 'المتبقي'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#investorFilter, #monthFilter, #yearFilter, #status').on('change', function() {
            dataTable.ajax.reload();
        });


        // Delete Using Ajax
        deleteScript('{{ route($route . '.destroy', ':id') }}');

        showEditModal('{{ route('vendor.orders.editOrderStatus', ':id') }}');
        editScript();

        // Add Using Ajax
        showAddModal('{{ route($route . '.create') }}');
        addScript();

        checkVendorKeyLimit('.addBtn', 'Order');
    </script>

    </script>
    <script>
        $(document).ready(function() {

            $('#investorFilter').val('');
            $('#monthFilter').val('');
            $('#yearFilter').val('');
            $('#status').val('');
            $('#investorFilter,#monthFilter,#yearFilter,#status').trigger('change');

        });
    </script>
@endsection
