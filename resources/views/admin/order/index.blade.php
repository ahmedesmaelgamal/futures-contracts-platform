@extends('admin/layouts/master')

@section('title')
{{ config()->get('app.name') }} | المكاتب
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
                <div class="card-header">
                    <h3></h3>
                    <h3 class="card-title"></h3>
                    <div class="">
{{--                        <button class="btn btn-secondary btn-icon text-white addBtn">--}}
{{--									<span>--}}
{{--										<i class="fe fe-plus"></i>--}}
{{--									</span> أضافه--}}
{{--                        </button>--}}
{{--                        <button class="btn btn-danger btn-icon text-white" id="bulk-delete">--}}
{{--                            <span><i class="fe fe-trash"></i></span> حذف المحدد--}}
{{--                        </button>--}}

{{--                        <button class="btn btn-secondary btn-icon text-white" id="bulk-update">--}}
{{--                            <span><i class="fe fe-trending-up"></i></span> تغير الحالة--}}
{{--                        </button>--}}
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
                                <th class="min-w-50px rounded-end">اسم العميل </th>
                                <th class="min-w-50px rounded-end">رقم هويه العميل </th>
                                <th class="min-w-50px rounded-end">الكميه</th>
                                <th class="min-w-50px rounded-end">تاريخ السداد</th>
                                <th class="min-w-50px rounded-end"> اسم المستثمر</th>
                                <th class="min-w-50px rounded-end"> رقم الطلب</th>
                                <th class="min-w-50px rounded-end">  حاله الطلب</th>
                                <th class="min-w-50px rounded-end"> المبلغ الطلوب سداده </th>
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
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
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
                data: 'client_id',
                name: 'client_id'
            },

            {
                data: 'client_national_id',
                name: 'client_national_id'
            },

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


            // {
            //     data: 'action', name: 'action', orderable: false, searchable: false
            // },
        ]
        showData('{{route($route.'.index')}}', columns);

        {{--// Delete Using Ajax--}}
        {{--deleteScript('{{route($route.'.destroy',':id')}}');--}}
        {{--deleteSelected('{{route($route.'.deleteSelected')}}');--}}

        {{--updateColumnSelected('{{route($route.'.updateColumnSelected')}}');--}}


        {{--// Add Using Ajax--}}
        {{--showAddModal('{{route($route.'.create')}}');--}}
        {{--addScript();--}}
        {{--// Add Using Ajax--}}
        {{--showEditModal('{{route($route.'.edit',':id')}}');--}}
        {{--editScript();--}}
    </script>

{{--    <script>--}}
{{--        // for status--}}
{{--        $(document).on('click', '.statusBtn', function () {--}}
{{--            let id = $(this).data('id');--}}

{{--            var val = $(this).is(':checked') ? 1 : 0;--}}

{{--            let ids = [id];--}}


{{--            $.ajax({--}}
{{--                type: 'POST',--}}
{{--                url: '{{ route($route . '.updateColumnSelected') }}',--}}
{{--                data: {--}}
{{--                    "_token": "{{ csrf_token() }}",--}}
{{--                    'ids': ids,--}}
{{--                },--}}
{{--                success: function (data) {--}}
{{--                    if (data.status === 200) {--}}
{{--                        if (val !== 0) {--}}
{{--                            toastr.success('', "نشط");--}}
{{--                        } else {--}}
{{--                            toastr.warning('', "غير نشط ");--}}
{{--                        }--}}
{{--                    } else {--}}
{{--                        toastr.error('Error', "حدث خطأ ما");--}}
{{--                    }--}}
{{--                },--}}
{{--                error: function () {--}}
{{--                    toastr.error('Error', "حدث خطأ ما");--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}


{{--    </script>--}}

@endsection


