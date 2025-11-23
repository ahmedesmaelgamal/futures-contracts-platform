@extends('vendor/layouts/master')

@section('title')
    {{ config()->get('app.name') }}
@endsection
@section('page_name')
    {{ $bladeName }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>

                    <div class="row align-items-end g-2">
                        <div class="col-md-2 mb-2">
                            <label for="type">اختر نوع العملية</label>
                            <select id="type" class="form-control">
                                <option value="">الكل</option>
                                <option value="0">إيداع</option>
                                <option value="1">سحب</option>
                            </select>
                        </div>

                        <div class="col-md-2 mb-2">
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

                        <div class="col-md-2 mb-2">
                            <label for="yearFilter">اختر السنة</label>
                            <select id="yearFilter" class="form-control">
                                <option value="">الكل</option>
                                @for ($year = 2025; $year <= now()->year; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-3 mb-2">
                            <label for="investorFilter">اختر المستثمر</label>
                            <select class="form-control select2" id="investorFilter">
                                <option value="">كل المستثمرين</option>
                                @foreach ($investors as $investor)
                                    <option value="{{ $investor->id }}">{{ $investor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-2 d-flex align-items-end">
                            @can('create_investor_wallets')
                            <button class="btn btn-primary btn-block addBtn">
                                <i class="fe fe-plus"></i> أضافه
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <h2 class=" text-center text-warning">
                            <span id="filtered-balance">
                            المبلغ الكلي {{ $investors->sum('balance') }}</span>
                        </h2>
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">

                                <th class="min-w-25px">#</th>
                                <th class="min-w-25px">المبلغ</th>

                                <th class="min-w-25px">نوع العمليه</th>
                                <th class="min-w-25px">التاريخ</th>
                                <th class="min-w-25px">اسم المستثمر</th>
                                <th class="min-w-25px">من قام بالعمليه</th>
                                <th class="min-w-25px">الملاحظات</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>


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
        $('#investorFilter').select2({
            width: 'resolve'
        });

        let dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [
                [0, "DESC"]
            ],
            ajax: {
                url: '{{ route($route . '.index') }}',
                data: function (d) {
                    d.type = $('#type').val();
                    d.month = $('#monthFilter').val();
                    d.year = $('#yearFilter').val();
                    d.investor_id = $('#investorFilter').val();
                }
            },
            columns: [{
                data: 'id',
                name: 'id',
                visible: false,
                searchable: false
            },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'type',
                    name: 'type'
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
                    data: 'vendor_id',
                    name: 'vendor_id'
                },
                {
                    data: 'note',
                    name: 'note'
                },
            ]
        });

        $('#type, #monthFilter, #yearFilter ,#investorFilter').on('change', function () {
            dataTable.ajax.reload();
        });

        dataTable.on('xhr', function (e, settings, json) {
            if (json && json.total_amount !== undefined) {
                $('#filtered-balance').text(json.total_amount + ' ريال');
            }
        });

        showAddModal('{{ route($route . '.create') }}');
        addScript();
    </script>
    <script>
        $(document).ready(function () {

            $('#type').val('');
            $('#monthFilter').val('');
            $('#yearFilter').val('');
            $('#investorFilter').val('');
            $('#monthFilter, #yearFilter,#investorFilter,#type').trigger('change');

        });

    </script>
@endsection
