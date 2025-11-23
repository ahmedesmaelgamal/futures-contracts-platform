@extends('vendor/layouts/master')

@section('title')
    {{ config()->get('app.name') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="form-group row">
                        <div class="col-3">
                            <label> حاله العميل</label>
                            <select id="filter-status" class="form-control">
                                <option value="">الكل</option>
                                <option value="متعثر">متعثر</option>
                                <option value="لديه طلب قائم">لديه طلب قائم</option>
                                <option value="غير منتظم في السداد">غير منتظم في السداد</option>
                                <option value="منتظم في السداد">منتظم في السداد</option>
                                <option value="ليس لديه طلبات">ليس لديه طلبات</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="statusSelection"> الحاله</label>
                            <select id="statusSelection" class="form-control">
                                <option value="show all" selected>الكل</option>
                                <option value="1">نشط</option>
                                <option value="0">غير نشط</option>
                            </select>
                        </div>

                        <div class="col-3">
                            <label for="branchSelection">الفرع</label>
                            <select id="branchSelection" class="form-control">
                                <option value="" selected>الكل</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <h3 class="card-title"></h3>


                    <div class="">
                        @can('create_client')
                            <button class="btn btn-secondary btn-icon text-white addBtn">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> إضافة
                            </button>
                        @endcan
                        @can('delete_client')
                            <button class="btn btn-danger btn-icon text-white" id="bulk-delete">
                                <span><i class="fe fe-trash"></i></span> حذف المحدد
                            </button>
                        @endcan
                        @can('update_client')
                            <button class="btn btn-secondary btn-icon text-white" id="bulk-update">
                                <span><i class="fe fe-trending-up"></i></span> تعديل حالة المحدد
                            </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th class="min-w-25px">#</th>
                                <th class="min-w-25px">الإسم</th>
                                <th class="min-w-25px">رقم الهاتف</th>
                                <th class="min-w-25px">رقم الهويه</th>
                                <th class="min-w-25px">الحاله</th>
                                <th class="min-w-25px">حاله العميل</th>
                                <th class="min-w-25px">الفرع</th>
                                <th class="min-w-50px rounded-end">العمليات</th>
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
                        <p>هل انت متاكد من حذف هذا العنصر <span id="title"
                                                                class="text-danger"></span>?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="dismiss_delete_modal">
                            اغلاق
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">حذف !</button>
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
                        <p>هل أنت متأكد من أنك تريد حذف العناصر المحددة</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-danger" id="confirm-delete-btn">حذف</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete selected  Modal -->


        <!-- update cols selected  Modal -->
        <div class="modal fade" id="updateConfirmModal" tabindex="-1" role="dialog"
             aria-labelledby="updateConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">تأكيد التعديل</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>هل انت متاكد من تعديل هذه السجلات</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-send" id="confirm-update-btn">تعديل</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete selected  Modal -->
    </div>
    @include('vendor/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [{
            data: 'checkbox',
            name: 'checkbox',
            orderable: false,
            serverSide: false,
            searchable: false,
            render: function (data, type, row) {
                return `<input type="checkbox" class="delete-checkbox" value="${row.id}">`;
            }
        },
            {
                data: 'id',
                name: 'id',
                visible: false,
                searchable: false
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'national_id',
                name: 'national_id'
            },
            {
                data: 'status',
                name: 'status'
            },  {
                data: 'order_status',
                name: 'order_status',
                render: function (data, type, row) {
                    // if (type === 'filter' || type === 'sort') {
                        return $('<div>').html(data).text().trim();
                    // }
                    return data;
                }
            },
            {
                data: 'branch_id',
                name: 'branch_id'
            },

            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
        showData('{{ route($route . '.index') }}', columns);

        // Delete Using Ajax
        deleteScript('{{ route($route . '.destroy', ':id') }}');
        deleteSelected('{{ route($route . '.deleteSelected') }}');

        updateColumnSelected('{{ route($route . '.updateColumnSelected') }}');


        // Add Using Ajax
        showAddModal('{{ route($route . '.create') }}');
        addScript();
        // Add Using Ajax
        showEditModal('{{ route($route . '.edit', ':id') }}');
        editScript();






    </script>
    <script>
        $(document).ready(function () {

            $('#statusSelection').val('show all');
            $('#filter-status').val('');
            $('#branchSelection').val('');
            $('#statusSelection, #filter-status,#branchSelection').trigger('change');

        });

    </script>
    <script>
        // for status
        $(document).on('click', '.statusBtn', function () {
            let id = $(this).data('id');

            var val = $(this).is(':checked') ? 1 : 0;

            let ids = [id];


            $.ajax({
                type: 'POST',
                url: '{{ route($route . '.updateColumnSelected') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'ids': ids,
                },
                success: function (data) {
                    if (data.status === 200) {
                        if (val !== 0) {
                            toastr.success('', "نشط");
                        } else {
                            toastr.warning('', "غير نشط");
                        }
                    } else {
                        toastr.error('Error', "هناك خطأ ما");
                    }
                },
                error: function () {
                    toastr.error('Error', "هناك خطأ ما");
                    toastr.warning('', "غير نشط ");
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            let table = $('#dataTable').DataTable();

            $(document).on("change", "#statusSelection, #filter-status, #branchSelection", function () {
                filterRows();
            });

            table.on("draw", function () {
                filterRows();
            });

            function filterRows() {
                let selectedStatus = $('#statusSelection').val();
                let selectedOrderStatus = $('#filter-status').val();
                let selectedBranch = $('#branchSelection').val();

                table.rows().every(function () {
                    let row = this.node();

                    let statusText = $(row).find('.status').text().trim();
                    let orderStatusText = $(row).find('td:eq(5)').text().trim();
                    let branchText = $(row).find('td:eq(6)').text().trim();

                    let matchStatus = (selectedStatus === 'show all') || (statusText === selectedStatus);
                    let matchOrder = (selectedOrderStatus === '') || (orderStatusText.includes(selectedOrderStatus));
                    let matchBranch = (selectedBranch === '') || (branchText === selectedBranch);

                    $(row).toggle(matchStatus && matchOrder && matchBranch);
                });
            }

        });




    </script>





@endsection
