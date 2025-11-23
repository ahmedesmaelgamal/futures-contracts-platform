

<script>
    var loader = `
			<div id="skeletonLoader" class="skeleton-loader">
    <div class="loader-header">
        <div class="skeleton skeleton-text"></div>
    </div>
    <div class="loader-body">
        <div class="skeleton skeleton-textarea"></div>
    </div>

</div>
        `;




    // Show Data Using YAJRA
    async function showData(routeOfShow, columns) {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: routeOfShow,
            columns: columns,
            order: [
                [1, "DESC"]
            ],
            columnDefs: [
                {
                targets: 0,
                checkboxes: true,
                render: function() {
                    return '<input type="checkbox" class="select-all-checkbox">';
                }
            }],
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
    }

    function deleteScript(routeTemplate) {
        $(document).ready(function() {
            // Configure modal event listeners
            $('#delete_modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var title = button.data('title');
                var modal = $(this);
                modal.find('.modal-body #delete_id').val(id);
                modal.find('.modal-body #title').text(title);
            });

            $(document).on('click', '#delete_btn', function() {
                var id = $("#delete_id").val();
                var routeOfDelete = routeTemplate.replace(':id', id);

                $.ajax({
                    type: 'DELETE',
                    url: routeOfDelete,
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'id': id
                    },
                    success: function(data) {
                        $("#dismiss_delete_modal")[0].click();
                        if (data.status === 200) {
                            $('#dataTable').DataTable().ajax.reload();
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            });
        });
    }

    // show Add Modal
    function showAddModal(routeOfShow) {
        $(document).on('click', '.addBtn', function() {
            $('#modal-body').html(loader)
            $('#editOrCreate').modal('show')
            setTimeout(function() {
                $('#modal-body').load(routeOfShow)
            }, 250)
        });
    }



    // show view Modal
    function showEditModal(routeOfEdit) {
        $(document).on('click', '.editBtn', function() {
            var id = $(this).data('id')
            var url = routeOfEdit;
            url = url.replace(':id', id)
            $('#modal-body').html(loader)
            $('#editOrCreate').modal('show')

            setTimeout(function() {
                $('#modal-body').load(url)
            }, 500)
        })
    }




    function addScript() {
        $(document).on('submit', 'Form#addForm', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var url = $('#addForm').attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#addButton').html('<span class="spinner-border spinner-border-sm mr-2"></span> <span style="margin-left: 4px;">أنتظر قليلًا ...</span>'
                    ).attr('disabled', true);
                },
                success: function(data) {
                    if (data.status == 200) {
                        $('#dataTable').DataTable().ajax.reload();
                        toastr.success('تمت العملية بنجاح');
                    } else if (data.status == 405) {
                        toastr.error(data.mymessage);
                    } else if (data.status == 250) {
                        toastr.error('هذا المستخدم مشترك بالفعل في خطه');
                    }else if (data.status == 251) {
                        toastr.error('هذا المستخدم قدم طلب على خطه معينه الرجاء تحديث حالتها أولاً');
                    }
                    else {
                        toastr.error('حدث خطأ ما');
                    }
                    $('#addButton').html('إضافة').attr('disabled', false);
                    $('#editOrCreate').modal('hide');
                },
                error: function(data) {
                    if (data.status === 500) {
                        toastr.error('خطأ في الخادم');
                    } else if (data.status === 422) {
                        var errors = $.parseJSON(data.responseText);
                        $.each(errors, function(key, value) {
                            if ($.isPlainObject(value)) {
                                $.each(value, function(key, value) {
                                    toastr.error(value, 'خطأ');
                                });
                            }
                        });
                    } else {
                        toastr.error('حدث خطأ ما');
                    }
                    $('#addButton').html('إضافة').attr('disabled', false);
                }, //end error method
                cache: false,
                contentType: false,
                processData: false
            });
        });
    }



    function editScript() {
        $(document).on('submit', 'Form#updateForm', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var url = $('#updateForm').attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#updateButton').html('<span class="spinner-border spinner-border-sm mr-2"></span> <span style="margin-left: 4px;">أنتظر قليلًا ...</span>'
                    ).attr('disabled', true);
                },
                success: function(data) {
                    $('#updateButton').html('تحديث').attr('disabled', false);
                    if (data.status == 200) {
                        $('#dataTable').DataTable().ajax.reload();
                        toastr.success('تمت العملية بنجاح');
                    } else {
                        toastr.error('حدث خطأ ما');
                    }
                    $('#editOrCreate').modal('hide');
                },
                error: function(data) {
                    if (data.status === 500) {
                        toastr.error('حدث خطأ ما');
                    } else if (data.status === 422) {
                        var errors = $.parseJSON(data.responseText);
                        $.each(errors, function(key, value) {
                            if ($.isPlainObject(value)) {
                                $.each(value, function(key, value) {
                                    toastr.error(value, 'خطأ');
                                });
                            }
                        });
                    } else {
                        toastr.error('حدث خطأ ما');
                    }
                    $('#updateButton').html('تحديث').attr('disabled', false);
                }, //end error method

                cache: false,
                contentType: false,
                processData: false
            });
        });
    }

    function deleteSelected(route) {
        $(document).ready(function() {
            $('#bulk-delete').prop('disabled', true);

            $('#select-all').on('click', function() {
                const isChecked = $(this).is(':checked');
                $('.delete-checkbox').prop('checked', isChecked);
                toggleBulkDeleteButton();
            });

            $(document).on('change', '.delete-checkbox', function() {
                toggleBulkDeleteButton();
            });

            $('#bulk-delete').on('click', function() {
                const selected = $('.delete-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selected.length > 0) {
                    $('#deleteConfirmModal').modal('show');

                    $('#confirm-delete-btn').off('click').on('click', function() {
                        $.ajax({
                            url: route,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selected
                            },
                            success: function(response) {
                                if (response.status === 200) {
                                    toastr.success('تم الحذف بنجاح');
                                    $('#select-all').prop('checked', false);
                                    $('.delete-checkbox').prop('checked', false);
                                    $('#dataTable').DataTable().ajax.reload();
                                } else {
                                    toastr.error('حدث خطأ ما');
                                }
                                $('#deleteConfirmModal').modal('hide');
                                toggleBulkDeleteButton();
                            },
                            error: function() {
                                toastr.error('حدث خطأ ما');
                                $('#deleteConfirmModal').modal('hide');
                                toggleBulkDeleteButton();
                            }
                        });
                    });
                }
            });

            function toggleBulkDeleteButton() {
                const anyChecked = $('.delete-checkbox:checked').length > 0;
                $('#bulk-delete').prop('disabled', !anyChecked);
            }
        });
    }

    function updateColumnSelected(route) {
        $(document).ready(function() {
            $('#bulk-update').prop('disabled', true);

            $('#select-all').on('click', function() {
                const isChecked = $(this).is(':checked');
                $('.delete-checkbox').prop('checked', isChecked);
                toggleBulkUpdateButton();
            });

            $(document).on('change', '.delete-checkbox', function() {
                toggleBulkUpdateButton();
            });

            $('#bulk-update').on('click', function() {
                const selected = $('.delete-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selected.length > 0) {
                    $('#updateConfirmModal').modal('show');

                    $('#confirm-update-btn').off('click').on('click', function() {
                        $.ajax({
                            url: route,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selected
                            },
                            success: function(data) {
                                if (data.status === 200) {
                                    toastr.success('تم التحديث بنجاح');
                                    $('#select-all').prop('checked', false);
                                    $('.delete-checkbox').prop('checked', false);
                                    $('#dataTable').DataTable().ajax.reload();
                                } else {
                                    toastr.error('حدث خطأ ما');
                                }
                                $('#updateConfirmModal').modal('hide');
                                toggleBulkUpdateButton();
                            },
                            error: function(xhr) {
                                toastr.error('حدث خطأ ما');
                                $('#updateConfirmModal').modal('hide');
                                toggleBulkUpdateButton();
                            }
                        });
                    });
                } else {
                    toastr.error('يرجى التحديد أولاً');
                }
            });

            function toggleBulkUpdateButton() {
                const anyChecked = $('.delete-checkbox:checked').length > 0;
                $('#bulk-update').prop('disabled', !anyChecked);
            }
        });
    }

</script>
