@extends('admin/layouts/master')

@section('title')
    {{ config()->get('app.name') }} | الاشتراكات
@endsection
@section('page_name')
    <!-- {{ $bladeName }} -->
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <div class="">
                        {{-- <button class="btn btn-secondary btn-icon text-white addBtn">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> أضافه
                        </button> --}}
                        {{--                        <button class="btn btn-danger btn-icon text-white" id="bulk-delete">--}}
                        {{--                            <span><i class="fe fe-trash"></i></span> حذف المحدد--}}
                        {{--                        </button>--}}

                        {{--                        <button class="btn btn-secondary btn-icon text-white" id="bulk-update">--}}
                        {{--                            <span><i class="fe fe-trending-up"></i></span> تحديث حالة المحدد--}}
                        {{--                        </button>--}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                {{--                                <th class="min-w-25px checkbox-selector">--}}
                                {{--                                    <input type="checkbox" id="select-all">--}}
                                {{--                                </th>--}}
                                <th class="min-w-25px">#</th>
                                <th class="min-w-25px">المكاتب</th>
                                <th class="min-w-25px">الخطة</th>
                                <th class="min-w-25px">من</th>
                                <th class="min-w-25px">الى</th>
                                <th class="min-w-25px">ايصال الدفع</th>
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
                        <p>هل أنت متأكد من أنك تريد حذف هذا العنصر <span id="title" class="text-danger"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="dismiss_delete_modal">
                            إلعاء
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
                        <p>هل أنت متأكد من أنك تريد حذف العناصر المجدده</p>
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
                        <h5 class="modal-title" id="deleteConfirmModalLabel">تفعيل الإشتراك</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد من أنك تريد تعديل حالة العناصر المحدده</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-send" id="confirm-update-btn">تعديل</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete selected  Modal -->

        <!-- accept activate Modal -->
        <div class="modal fade" id="acceptActivateModal" tabindex="-1" role="dialog"
             aria-labelledby="updateConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">رفض الإشتراك</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد من أنك تريد قبول طلب الإشتراك لللمستخدم
                            : <span class="vendor-name"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-send" id="confirm-accept-btn">قبول الطلب</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- accept selected  Modal -->

        <!-- reject activate Modal -->
        <div class="modal fade" id="rejectActivateModal" tabindex="-1" role="dialog"
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
                        <p>هل أنت متأكد من أنك تريد رفض طلب الإشتراك لللمستخدم
                            : <span class="vendor-name"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-send" id="confirm-reject-btn">رفض الطلب</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- reject selected  Modal -->
    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [
            {
                data: 'id',
                name: 'id'
            },
            {
                data: 'vendor_id',
                name: 'vendor_id'
            },
            {
                data: 'plan_id',
                name: 'plan_id'
            },

            {
                data: 'from',
                name: 'from'
            },
            {
                data: 'to',
                name: 'to'
            },
            {
                data: 'payment_receipt',
                name: 'payment_receipt'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ];

        showData('{{ route($route . '.index') }}', columns);

        // Hide the first column and remove checkboxes
        $('#dataTable').on('draw.dt', function () {
            var table = $('#dataTable').DataTable();
            table.column(0).visible(false);
            $('.select-all-checkbox, .delete-checkbox, .checkbox-selector').remove();
        });

        // Delete Using Ajax
        {{--        deleteScript('{{ route($route . '.destroy', ':id') }}');--}}
        {{--        deleteSelected('{{ route($route . '.deleteSelected') }}');--}}

        {{--updateColumnSelected('{{ route($route . '.updateColumnSelected') }}');--}}


        // Add Using Ajax
        showAddModal('{{ route($route . '.create') }}');
        addScript();
        // Add Using Ajax
        {{--showEditModal('{{ route($route . '.edit', ':id') }}');--}}
        // editScript();
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
                            toastr.warning('', "غير نشط ");
                        }
                    } else {
                        toastr.error('Error', "حدث خطأ ما");
                    }
                },
                error: function () {
                    toastr.error('Error', "حدث خطأ ما");
                }
            });
        });
    </script>
    <script>
        $('#rejectActivateModal,#acceptActivateModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var subscriptionId = button.data('id');
            var vendorName = button.data('vendor-name');
            console.log(vendorName);
            $('.vendor-name').text(vendorName);
            $('#confirm-update-btn').data('id', subscriptionId);
        });


        $(document).ready(function() {
            // Single modal show handler for both modals
            $('#rejectActivateModal, #acceptActivateModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // The button that triggered the modal
                var subscriptionId = button.data('id');
                var vendorName = button.data('vendor-name');

                console.log("Vendor Name:", vendorName);
                console.log("Subscription ID:", subscriptionId);

                // Update the modal content
                $('.vendor-name').text(vendorName);

                // Store the ID on both action buttons
                $('#confirm-reject-btn, #confirm-accept-btn').data('id', subscriptionId);
            });

            // Handle rejection via AJAX
            $('#confirm-reject-btn').click(function() {
                var subscriptionId = $(this).data('id');
                var button = $(this); // Use the clicked button

                console.log("Rejecting ID:", subscriptionId);
                sendRequest(subscriptionId, button, 'reject');
            });

            // Handle activation via AJAX
            $('#confirm-accept-btn').click(function() {
                var subscriptionId = $(this).data('id');
                var button = $(this); // Use the clicked button

                console.log("Activating ID:", subscriptionId);
                sendRequest(subscriptionId, button, 'activate');
            });

            // Common function for both actions
            function sendRequest(id, button, action) {
                if (!id) {
                    console.error("No ID provided for", action);
                    toastr.error('معرف الاشتراك غير موجود');
                    return;
                }

                // Disable button during AJAX call
                button.prop('disabled', true);
                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري المعالجة...');

                // Determine URL based on action
                var url = action === 'reject'
                    ? '/admin/subscriptions/reject/' + id
                    : '/admin/subscriptions/activate/' + id;

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            toastr.success(response.message);
                            $('#' + (action === 'reject' ? 'rejectActivateModal' : 'acceptActivateModal')).modal('hide');
                            // window.location.reload();
                            if ($.fn.DataTable.isDataTable('#dataTable')) {
                                $('#dataTable').DataTable().ajax.reload(null, false); // false means don't reset paging
                            }
                        } else {
                            toastr.error(response.message || 'حدث خطأ غير متوقع');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('حدث خطأ أثناء المعالجة');
                        console.error(xhr.responseText);
                    },
                    complete: function() {
                        // Re-enable button
                        button.prop('disabled', false);
                        button.text(action === 'reject' ? 'رفض الطلب' : 'تفعيل الطلب');
                    }
                });
            }
        });
    </script>
@endsection
