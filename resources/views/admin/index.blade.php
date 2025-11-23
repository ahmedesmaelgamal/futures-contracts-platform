@extends('admin/layouts/master')
@section('title')
    {{ config()->get('app.name') }} | الاحصائيات
@endsection
@section('page_name')
    الاحصائيات
@endsection

@section('content')
    <div class="row">


        <form action="{{ route('adminHome') }}" method="get" class="col-12 col-md-8 mb-3 mb-md-0">
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


        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$investors }}</h2>
                            <p class="text-white mb-0">عدد المستثمرين</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$unsurpassed }}</h2>
                            <p class="text-white mb-0">عدد المتعثرين</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$clients }}</h2>
                            <p class="text-white mb-0">عدد العملاء</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$orders }}</h2>
                            <p class="text-white mb-0">عدد العمليات</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$admins }}</h2>
                            <p class="text-white mb-0">عدد المشرفين</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fas fa-list-ol text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Offices Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$offices }}</h2>
                            <p class="text-white mb-0">عدد المكاتب</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-home text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Branches Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$branches }}</h2>
                            <p class="text-white mb-0">عدد الفروع</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-map-pin text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Regions Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$regions }}</h2>
                            <p class="text-white mb-0">عدد المناطق</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-navigation text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plans Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$plans }}</h2>
                            <p class="text-white mb-0">عدد الخطط</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-calendar text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$categories }}</h2>
                            <p class="text-white mb-0">عدد الاصناف</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-navigation text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Subscriptions Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$activeSubscriptions }}</h2>
                            <p class="text-white mb-0">الاشتراكات النشطة</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-check-circle text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- expired Subscriptions Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$expiredSubscriptions }}</h2>
                            <p class="text-white mb-0">الاشتراكات المنتهية</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-x-circle text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requested Subscriptions Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$requestedSubscriptions }}</h2>
                            <p class="text-white mb-0">طلبات الاشتراك</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-clock text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected Subscriptions Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$rejectedSubscriptions }}</h2>
                            <p class="text-white mb-0">الاشتراكات المرفوضة</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-x-circle text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected Subscriptions Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$vendorsWithOutPlans }}</h2>
                            <p class="text-white mb-0">المكاتب الغير مشتركه في خطط</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-x-circle text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- total orders amount Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$totalOrdersAmounts }}</h2>
                            <p class="text-white mb-0">إجمالي قيمة الطلبات</p>
                        </div>
                        <div class="mr-auto">
                            <span class="text-white fs-20 ml-2 mt-2">﷼</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$totalOfficesAmounts }}</h2>
                            <p class="text-white mb-0">إجمالي رصيد المكاتب</p>
                        </div>
                        <div class="mr-auto">
                            <span class="text-white fs-20 ml-2 mt-2">﷼</span>

                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$totalUnSurpassedMoneyAmounts }}</h2>
                            <p class="text-white mb-0">إجمالي المبالغ المتعثرة</p>
                        </div>
                        <div class="mr-auto">
                            <span class="text-white fs-20 ml-2 mt-2">﷼</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendors Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$vendors }}</h2>
                            <p class="text-white mb-0">عدد الموظفين</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-users text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ @$stock }}</h2>
                            <p class="text-white mb-0">المخزون</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-package text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>






    </div>
@endsection
@section('js')
    <script>
        $('select').select2();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let admins = parseInt({{ $admins }});
        let investors = parseInt({{ $investors }});
        let unsurpasseds = parseInt({{ $unsurpassed }});
        let clients = parseInt({{ $clients }});
        let orders = parseInt({{ $orders }});
        let offices = parseInt({{ $offices }});
        let vendors = parseInt({{ $vendors }});

        let branches = parseInt({{ $branches }});
        let regions = parseInt({{ $regions }});
        let categories = parseInt({{ $categories }});

        let stock = parseInt({{ $stock }});
        let plans = parseInt({{ $plans }});
        let vendorsWithOutPlans = parseInt({{ $vendorsWithOutPlans }});

        let activeSubscriptions = parseInt({{ $activeSubscriptions }});
        let requestedSubscriptions = parseInt({{ $requestedSubscriptions }});
        let rejectedSubscriptions = parseInt({{ $rejectedSubscriptions }});
        let expiredSubscriptions = parseInt({{ $expiredSubscriptions }});

        let totalOrdersAmounts = parseInt({{ $totalOrdersAmounts }});
        let totalOfficesAmounts = parseInt({{ $totalOfficesAmounts }});
        let totalUnSurpassedMoneyAmounts = parseInt({{ $totalUnSurpassedMoneyAmounts }});

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    'المشرفين',

                    'المستثمرين',
                    'المتعثرين',
                    'العملاء',
                    'العمليات',
                    'المكاتب',
                    'الموظفين',
                    'الفروع',
                    'المناطق',
                    'الاصناف',
                    'المخزون',
                    'الخطط',
                    'الموظفين بدون خطط',
                    'الخطط المفعلة',
                    'الخطط المطلوبة',
                    'الخطط المرفوضة',
                    'الخطط المنتهية',

                    'إجمالي قيمة الطلبات',
                    'إجمالي رصيد المكاتب',
                    'إجمالي المبالغ المتعثرة',
                ],
                datasets: [{
                    label: 'العدد',
                    data: [
                        admins,
                        investors,
                        unsurpasseds,
                        clients,
                        orders,
                        offices,
                        vendors,
                        branches,
                        regions,
                        categories,
                        stock,
                        plans,
                        vendorsWithOutPlans,
                        activeSubscriptions,
                        requestedSubscriptions,
                        rejectedSubscriptions,
                        expiredSubscriptions,
                        totalOrdersAmounts,
                        totalOfficesAmounts,
                        totalUnSurpassedMoneyAmounts
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
                            stepSize: 1

                        }

                    }
                }
            }

        });
    </script>
@endsection
