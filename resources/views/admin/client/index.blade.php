@extends('admin/layouts/master')

@section('title')
    {{ config()->get('app.name') }} | العملاء
@endsection
@section('page_name')
    <!-- {{ $bladeName }} -->
@endsection
<!-- {{--@section('page_name') {{ $title }} -->
  @endsection--}} -->

@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                {{--@dd($offices)--}}

                <div class="card-header bg-light">
                    <div class="row align-items-center" style="width: 800px">
                        <!-- Office Filter -->
                        <div class="col-md-5 mb-3 mb-md-0">
                            <div class="form-group mb-0">
                                <label for="officeFilter" class="font-weight-bold text-muted mb-1">المكتب
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-building"></i>
                        </span>
                                    </div>
                                    <select id="officeFilter" class="form-control select2"
                                            aria-describedby="officeHelp">
                                        <option disabled selected value="">إختر المكتب</option>
                                        <option value="all">الكل</option>
                                        @foreach ($offices as $office)
                                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <small id="officeHelp" class="form-text text-muted">
                                    حدد المكتب لتصفية الفروع المتاحة
                                </small>
                            </div>
                        </div>

                        <!-- Branch Filter - Hidden Initially -->
                        <div class="col-md-5 mb-3 mb-md-0" id="branch-div" style="display: none">
                            <div class="form-group mb-0">
                                <label for="branchFilter" class="font-weight-bold text-muted mb-1">فرع المكتب</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-code-branch"></i>
                        </span>
                                    </div>
                                    <select id="branchFilter" class="form-control select2"
                                            aria-describedby="branchHelp">
                                        <option selected value="all">كل الفروع</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                    data-office-id="
        @if($branch->vendor)
            {{ $branch->vendor->parent_id != null ? ($branch->vendor->parent->id ?? $branch->vendor->id) : $branch->vendor->id }}
        @else
            0
        @endif
    ">
                                                {{ $branch->name }}
                                                (
                                                @if($branch->vendor)
                                                    {{ $branch->vendor->parent_id != null ? ($branch->vendor->parent->name ?? $branch->vendor->name) : $branch->vendor->name }}
                                                @else
                                                    بدون تاجر
                                                @endif
                                            )
                                            </option>

                                        @endforeach
                                    </select>
                                </div>
                                <small id="branchHelp" class="form-text text-muted">
                                    اختر فرع معين من الفروع التابعة للمكتب المحدد
                                </small>
                            </div>
                        </div>

                        {{--                        <!-- Optional Title Area -->--}}
                        {{--                        <div class="col-md-2 text-md-right">--}}
                        {{--                            <h3 class="card-title mb-0 text-primary">إعدادات التصفية</h3>--}}
                        {{--                            <small class="text-muted">استخدم الفلاتر للبحث الدقيق</small>--}}
                        {{--                        </div>--}}
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                {{--                                <th class="min-w-25px">--}}
                                {{--                                    <input type="checkbox" id="select-all">--}}
                                {{--                                </th>--}}
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px rounded-end">الإسم</th>
                                <th class="min-w-50px rounded-end">رقم الهويه</th>
                                <th class="min-w-50px rounded-end">رقم الهاتف</th>
                                <th class="min-w-50px rounded-end">الفرع</th>
                                {{--                                <th class="min-w-50px rounded-end">الموظف</th>--}}
                                <th class="min-w-50px rounded-end">المكتب</th>
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
                        <p>هل أنت متأكد من أنك تريد حذف هذا العنصر <span id="title"
                                                                         class="text-danger"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="dismiss_delete_modal">
                            إلغاء
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
                        <p>هل أنت متأكد من أنك تريد حذف العناصر المحدده</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">إلغاء
                        </button>
                        <button type="button" class="btn btn-danger"
                                id="confirm-delete-btn">حذف
                        </button>
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
                        <p>هل أنت متأكد من أنك تريد تعديل حالة العناصر المحدده</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">إلغاء
                        </button>
                        <button type="button" class="btn btn-send" id="confirm-update-btn">تعديل</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete selected  Modal -->
    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [

            {
                data: 'id',
                name: 'id',
                visible: false,
                searchable: false
            },
            {data: 'name', name: 'name'},
            {data: 'national_id', name: 'national_id'},
            {data: 'phone', name: 'phone'},
            {data: 'branch', name: 'branch'},

            {data: 'office', name: 'office'},

        ]


        {{--        showData('{{route($route.'.index')}}', columns);--}}

        {{--// Then get the DataTable instance and modify it--}}
        {{--var dataTable = $('#dataTable').DataTable();--}}

        {{--// Update the ajax.data option to include filters--}}
        {{--dataTable.ajax.url('{{route($route.'.index')}}').data(function(d) {--}}
        {{--    d.branch_id = $('#branchFilter').val();--}}
        {{--    d.office_id = $('#officeFilter').val();--}}
        {{--});--}}



        var dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true, // Change to true for server-side processing
            ajax: {
                url: '{{route($route.'.index')}}',
                data: function (d) {
                    d.branch_id = $('#branchFilter').val();
                    d.office_id = $('#officeFilter').val();
                }
            },
            columns: columns,
            order: [[0, 'desc']],
            "language": {
                "sProcessing": "جاري المعالجة...",
                "sLengthMenu": "عرض _MENU_ سجلات",
                "sZeroRecords": "لم يتم العثور على سجلات",
                "sInfo": "عرض _START_ إلى _END_ من _TOTAL_ سجلات",
                "sInfoEmpty": "عرض 0 إلى 0 من 0 سجلات",
                "sInfoFiltered": "(تمت التصفية من _MAX_ إجمالي السجلات)",
                "sSearch": "بحث :",
                "oPaginate": {
                    "sPrevious": "السابق",
                    "sNext": "التالي"
                },
                "buttons": {
                    "copyTitle": "تم النسخ <i class=\"fa fa-check-circle text-success\"></i>",
                    "copySuccess": {
                        "1": "تم نسخ 1 صف",
                        "_": "تم نسخ %d صفوف"
                    }
                }
            },


            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: "نسخ",
                    className: 'btn-primary'
                },
                {
                    extend: 'print',
                    text: "طباعة",
                    className: 'btn-primary'
                },
                {
                    extend: 'excel',
                    text: "إكسل",
                    className: 'btn-primary'
                },
                {
                    extend: 'pdf',
                    text: "PDF",
                    className: 'btn-primary'
                },
                {
                    extend: 'colvis',
                    text: "إظهار/إخفاء الأعمدة",
                    className: 'btn-primary'
                }
            ]
        });
        $('#officeFilter').on('change', function () {

            $('#branchFilter').val('all');
            const selectedOfficeId = $(this).val();
            let branchOfficeId = 'all'
            if (selectedOfficeId) {
                $('#branch-div').css('display', 'block');
            }

            // Update branch options
            let hasMatch = false;
            $('#branchFilter option').each(function () {
                branchOfficeId = $(this).data('office-id');
                if ($(this).val() === "all") {
                    $(this).show(); // Ensure "all" is always visible
                    return;
                }

                if (selectedOfficeId === "all" || branchOfficeId == selectedOfficeId || !selectedOfficeId) {
                    $(this).show();
                    hasMatch = true;
                } else {
                    $(this).hide();
                }
            });

            // $('#branchFilter option:first').prop('selected', true).show();
            //
            // if (!hasMatch) {
            //     $('#branchFilter').append('<option value="" disabled class="none-matched">لا يوجد فروع في هذا المكتب</option>');
            // } else {
            //     $('#branchFilter .none-matched').remove();
            // }

            // Reload the table with new filters
            dataTable.ajax.reload();
        });

        $('#branchFilter').on('change', function () {
            dataTable.ajax.reload();
        });
    </script>

@endsection


