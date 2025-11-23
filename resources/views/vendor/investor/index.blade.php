@extends('vendor.layouts.master')

@section('title')
    {{ config()->get('app.name') }}
@endsection
<style>
    .custom-select-lg.select2-hidden-accessible + .select2-container .select2-selection--single {
        font-size: 15px;
        padding-left: 70px;
    }

    .custom-select-lg.select2-hidden-accessible + .select2-container .select2-selection__rendered {
        padding-left: 70px;
    }




</style>
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">

                <div class="form-group row">


                    <div class="col-12">
                        <label for="branchSelection">الفرع</label>
                        <select id="branchSelection"  class="form-control select2 ">
                            <option value="" selected>الكل</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <h3 class="card-title"></h3>
                    <div class="">
                        @can('create_investor')
                            <button class="btn btn-secondary btn-icon text-white addBtn">
                                <span>
                                    <i class="fe fe-plus"></i>
                                </span> إضافة
                            </button>
                        @endcan
                        @can('delete_investor')
                            <button class="btn btn-danger btn-icon text-white" id="bulk-delete">
                                <span><i class="fe fe-trash"></i></span> حذف المحدد
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
                                    <th class="min-w-25px">البريد الإلكتروني</th>
                                    <th class="min-w-25px">رقم الهاتف</th>
                                    <th class="min-w-25px">الفرع</th>
                                    <th class="min-w-25px">رقم الهوية</th>
                                    <th class="min-w-25px"> المحفظه</th>

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
                        <p>هل انت متاكد من حذف هذا العنصر <span id="title" class="text-danger"></span>?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="dismiss_delete_modal">
                            أغلاق
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">حذف !</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->

        <!-- add stock Modal -->
        <div class="modal fade" id="addStock" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
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

        <!-- showInvestorSummary Modal -->
        <div class="modal fade" id="showInvestorSummary" data-backdrop="static" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">التفاصيل</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body2">

                    </div>
                </div>
            </div>
        </div>
        <!-- showInvestorSummary Modal -->

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


    </div>
    @include('vendor.layouts.myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [{
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
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
                data: 'email',
                name: 'email'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'branch_id',
                name: 'branch_id'
            },
            {
                data: 'national_id',
                name: 'national_id'
            }, {
                data: 'balance',
                name: 'balance'
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

        checkVendorKeyLimit('.addBtn', 'Investor');


        $(document).on('click', '.addStock', function() {
            let id = $(this).data('id');
            $('#modal-body').html(loader)
            $('#addStock').modal('show')
            setTimeout(function() {
                $('#modal-body1').load('{{ route('vendor.investors.addStock', ':id') }}'.replace(':id', id))
            }, 250)
        });

        $(document).on('click', '.showInvestorSummary', function() {
            let id = $(this).data('id');
            $('#modal-body').html(loader)
            $('#showInvestorSummary').modal('show')
            setTimeout(function() {
                $('#modal-body2').load('{{ route('investors.stocks.summary', ':id') }}'.replace(':id', id))
            }, 250)
        });
    </script>
    <script>
        $(document).ready(function () {

            $('#branchSelection').val('');
            $('#branchSelection').trigger('change');

        });

    </script>
    <script>
        $(document).ready(function () {
            let table = $('#dataTable').DataTable();

            $(document).on("change","#branchSelection", function () {
                filterRows();
            });

            table.on("draw", function () {
                filterRows();
            });

            function filterRows() {
                let selectedBranch = $('#branchSelection').val();

                table.rows().every(function () {
                    let row = this.node();

                    let branchText = $(row).find('td:eq(4)').text().trim();
                    console.log(branchText, selectedBranch);

                    let matchBranch = (selectedBranch === '') || (branchText === selectedBranch);

                    $(row).toggle(matchBranch);
                });
            }

        });




    </script>

@endsection
