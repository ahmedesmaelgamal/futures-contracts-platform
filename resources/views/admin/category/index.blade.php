@extends('admin/layouts/master')

@section('title')
{{ config()->get('app.name') }} | المكاتب
@endsection
@section('page_name')
@endsection


@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
{{--                <div class="card-header">--}}
{{--                    <div class="form-group row">--}}
{{--                        <label for="officeFilter" class="col">اختر المكتب</label>--}}
{{--                        <select id="officeFilter" class="form-control col">--}}
{{--                            <option value="">الكل</option>--}}
{{--                            @foreach ($vendors as $vendor)--}}
{{--                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                    <h3></h3>--}}
{{--                    <h3 class="card-title"></h3>--}}
{{--                    <div class="">--}}

{{--                    </div>--}}
{{--                </div>--}}

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
                                        <option value="">الكل</option>
                                        @foreach ($offices as $office)
                                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <small id="officeHelp" class="form-text text-muted">
                                    حدد المكتب لتصفية الأصناف المتاحة
                                </small>
                            </div>
                        </div>

{{--                        <!-- Branch Filter - Hidden Initially -->--}}
                        {{--                        <div class="col-md-5 mb-3 mb-md-0" id="branch-div" style="display: none">--}}
                        {{--                            <div class="form-group mb-0">--}}
                        {{--                                <label for="branchFilter" class="font-weight-bold text-muted mb-1">فرع المكتب</label>--}}
                        {{--                                <div class="input-group">--}}
                        {{--                                    <div class="input-group-prepend">--}}
                        {{--                        <span class="input-group-text bg-white">--}}
                        {{--                            <i class="fas fa-code-branch"></i>--}}
                        {{--                        </span>--}}
                        {{--                                    </div>--}}
                        {{--                                    <select id="branchFilter" class="form-control select2"--}}
                        {{--                                            aria-describedby="branchHelp">--}}
                        {{--                                        <option selected value="all">كل الفروع</option>--}}
                        {{--                                        @foreach ($branches as $branch)--}}
                        {{--                                            <option value="{{ $branch->id }}"--}}
                        {{--                                                    data-office-id="{{ $branch->vendor->parent_id != null ? $branch->vendor->parent->id : $branch->vendor->id }}">--}}
                        {{--                                                {{ $branch->name }}--}}
                        {{--                                                ({{ $branch->vendor->parent_id != null ? $branch->vendor->parent->name : $branch->vendor->name }}--}}
                        {{--                                                )--}}
                        {{--                                            </option>--}}
                        {{--                                        @endforeach--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                                <small id="branchHelp" class="form-text text-muted">--}}
                        {{--                                    اختر فرع معين من الفروع التابعة للمكتب المحدد--}}
                        {{--                                </small>--}}
                        {{--                            </div>--}}
{{--                        </div>--}}

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
                                <th class="min-w-50px rounded-end">المكتب</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

     </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>

        var columns = [

            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'vendor', name: 'vendor'},

        ]



        // Hide the first column and remove checkboxes
        $('#dataTable').on('draw.dt', function () {
            var table = $('#dataTable').DataTable();
            table.column(0).visible(false);
            $('.select-all-checkbox, .delete-checkbox, .checkbox-selector').remove();
        });



        // Initialize the DataTable
        var dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route($route.'.index')}}',
                data: function (d) {
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


        // Handle office filter change
        // $('#officeFilter').on('change', function() {
        //     dataTable.ajax.reload();
        // });


        $('#officeFilter').on('change', function () {
            dataTable.ajax.reload();
        });


    </script>



@endsection


