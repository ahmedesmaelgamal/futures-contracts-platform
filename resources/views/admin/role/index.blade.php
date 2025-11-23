@extends('admin/layouts/master')

@section('title')
    {{ config()->get('app.name') }} | الأدوار
@endsection
@section('page_name')
الادوار

@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <div class="">
                        <button class="btn btn-secondary btn-icon text-white addBtn">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> اضافه
                        </button>
                        <button class="btn btn-danger btn-icon text-white" id="bulk-delete">
                            <span><i class="fe fe-trash"></i></span> حذف المحدد
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="max-w-25px">#</th>
                                <th class="min-w-50px">الدور</th>
                                <th class="min-w-50px">الصلاحيات</th>
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
                        <p>هل أنت متأكد من أنك تريد حذف هذا العنصر <span id="title"
                                                                                        class="text-danger"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="dismiss_delete_modal">
                            إلغاء
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">! حذف</button>
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
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')

    <script>
        var columns = [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'permissions', name: 'permissions'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        showData('{{route($route.'.index')}}', columns);
        // Delete Using Ajax
        deleteScript('{{route($route.'.destroy',':id')}}');
        deleteSelected('{{route($route.'.deleteSelected')}}');

        // Add Using Ajax
        showAddModal('{{route($route.'.create')}}');
        addScript();
        // Add Using Ajax
        showEditModal('{{route($route.'.edit',':id')}}');
        editScript();
    </script>

@endsection


