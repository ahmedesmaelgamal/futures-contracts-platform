@extends('vendor/layouts/master')
@section('title')
    الرئيسية
@endsection


@section('content')
    <div class="text-center my-5" style="direction: rtl; font-size: 16px;">
        <h2 class="mb-3" style="font-size: 20px;">
            @if (@$vendorPlanSubscription)
                خطتك الحالية هي
                <span class="text-success fw-bold">
                    {{ @$vendorPlanSubscription->plan->name }}
                </span>
                وتنتهي في
                <span class="text-danger fw-bold">
                    {{ \Carbon\Carbon::parse(@$vendorPlanSubscription->to)->format('Y-m-d') }}
                </span>
            @else
                <span class="text-warning fw-bold">أنت على الخطة المجانية</span>
            @endif
        </h2>

        <a href="{{ route('vendor.plans.index') }}" class="btn btn-primary btn-sm">
            عرض جميع الخطط
        </a>
    </div>



    <div class="row">
        <form action="{{ route('vendorHome') }}" method="get" class="col-12 col-md-8 mb-3 mb-md-0">
            @csrf
            <div class="form-group mb-0">
                <label for="officeFilter" class="font-weight-bold text-muted mb-1">
                    إحصائيات المكاتب
                    <i class="fas fa-filter text-muted ml-1"></i>
                </label>

                <div class="d-flex flex-row align-items-center gap-2">
                    <!-- سنة -->
                    <div class="input-group me-2" style="min-width: 200px;">

                        <select name="year" class="form-control select2">
                            </option>
                            <option @if (@$selectedYear == 'all') selected @endif value="all">الكل</option>
                            @foreach ($years as $year)
                                <option @if ($selectedYear == $year) selected @endif value="{{ $year }}">
                                    {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- شهر -->
                    <div class="input-group me-2" style="min-width: 200px;">

                        <select name="month" class="form-control select2">
                            </option>
                            <option @if (@$selectedMonth == 'all') selected @endif value="all">الكل</option>
                            @foreach ($months as $value => $month)
                                <option @if ($selectedMonth == $value) selected @endif value="{{ $value }}">
                                    {{ $month }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- زر التصفية -->
                    <button type="submit" class="btn btn-primary"><i class="fe fe-filter"></i></button>
                </div>



            </div>
        </form>
    </div>
    <br>
    <div class="card">
        <div class="card-header justify-content-center">
            <h2 style="font-weight: bold; padding-top: 1rem">احصائيات بيانية</h2>
        </div>
        <div class="card-body">
            <canvas id="myChart" width="400" height="300"></canvas>
        </div>
    </div>
    <div class="row">



        <!-- Clients Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $clients ?? 0 }}</h2>
                            <p class="text-white mb-0">عدد العملاء</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- branches Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $branches ?? 0 }}</h2>
                            <p class="text-white mb-0">عدد الفروع</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Investors Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $investors ?? 0 }}</h2>
                            <p class="text-white mb-0">عدد المستثمرين</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- subVendors Clients Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $subVendors ?? 0 }}</h2>
                            <p class="text-white mb-0">عدد الموظفين</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <!-- unsurpassed Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $unsurpassed ?? 0 }}</h2>
                            <p class="text-white mb-0">عدد المتعثرين</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- orders Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $orders ?? 0 }}</h2>
                            <p class="text-white mb-0">عدد الطلبات</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- total_vendor_balance Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $total_vendor_balance ?? 0 }}</h2>
                            <p class="text-white mb-0">اجمالي رصيد المكتب</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- total_investor_balance Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ number_format($total_investor_balance ?? 0) }}</h2>
                            <p class="text-white mb-0">إجمالي رصيد المستثمرين</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- categories Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $categories ?? 0 }}</h2>
                            <p class="text-white mb-0">عدد الاصناف</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- total_required_to_pay Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ number_format($total_required_to_pay ?? 0) }}</h2>
                            <p class="text-white mb-0">إجمالي المطلوب</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- total_paid Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ number_format($total_paid ?? 0) }}</h2>
                            <p class="text-white mb-0">إجمالي السداد</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- total_unsurpassed_to_pay Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ number_format($total_unsurpassed_to_pay ?? 0) }}</h2>
                            <p class="text-white mb-0">إجمالي التعثر</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- total_unpaid Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ number_format($total_unpaid ?? 0) }}</h2>
                            <p class="text-white mb-0">المتبقي</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let clients = parseInt({{ $clients }});
        let branches = parseInt({{ $branches }});
        let investors = parseInt({{ $investors }});
        let subVendors = parseInt({{ $subVendors }});
        let unsurpassed = parseInt({{ $unsurpassed }});
        let orders = parseInt({{ $orders }});
        let total_vendor_balance = parseInt({{ $total_vendor_balance }});

        let total_investor_balance = parseInt({{ $total_investor_balance }});
        let categories = parseInt({{ $categories }});

        let total_required_to_pay = parseInt({{ $total_required_to_pay }});
        let total_paid = parseInt({{ $total_paid }});
        let total_unsurpassed_to_pay = parseInt({{ $total_unsurpassed_to_pay }});
        let total_unpaid = parseInt({{ $total_unpaid }});
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    ' العملاء',
                    'الفروع',
                    '  المستثمرين',
                    'الموظفين',
                    'المتعثرين',
                    'الطلبات',
                    'اجمالي رصيد المكتب',
                    'إجمالي رصيد المستثمرين',
                    'الاصناف',

                    'إجمالي المطلوب',
                    'إجمالي السداد',
                    'إجمالي التعثر',
                    'المتبقي',

                ],
                datasets: [{
                    label: 'العدد',
                    data: [
                        clients,
                        branches,
                        investors,
                        subVendors,
                        unsurpassed,
                        orders,
                        total_vendor_balance,
                        total_investor_balance,
                        categories,
                        total_required_to_pay,
                        total_paid,
                        total_unsurpassed_to_pay,
                        total_unpaid
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,

                        }

                    }
                }
            }

        });
    </script>
@endsection
