@extends('vendor/layouts/master')

@section('title')
    الإشتراكات
@endsection



@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        /* Your existing CSS styles */
        p {
            color: black;
        }

        .pricing .price-table {
            display: flex;
            flex-direction: column;
        }

        @media screen and (max-width: 991.98px) {
            .pricing .price-table {
                max-width: 370px;
                margin: 0 auto;
            }
        }

        .pricing .price-table .pricing-panel {
            background-color: white !important;
            padding: 44px 50px 42px;
            border-radius: 8px;
            box-shadow: 0 5px 83px 0 rgba(40, 40, 40, 0.11);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        /* Rest of your CSS styles */
    </style>

    <section class="pricing pricing-1" id="pricing-1">
        <div class="container">
            <div class="row">
                @foreach($plans as $plan)
                    <div class="col-12 col-lg-4 mb-4">
                        <div class="price-table">
                            <div class="pricing-panel">
                                <div class="pricing-heading">
                                    <h4 class="pricing-title" style="color: #0a0c0d">{{$plan->name}}</h4>
                                </div>
                                <div class="pricing-body">
                                    <div class="pricing-price">

                                        <p>
                                            @if($plan->price!=0)

                                                <span class="currency"
                                                      style="text-decoration: line-through; color: red;">
                                            {{ number_format($plan->price, 2) }} ريال سعودي
                                        </span><br>
                                            @endif
                                            @if($plan->price !=0)

                                                <span class="currency" style="font-weight: bold; color: green;">
                                                {{ number_format($plan->price * (100 - $plan->discount) / 100, 2) }} ريال سعودي
                                            </span>
                                            @else

                                                <span class="currency" style="font-weight: bold; color: green;">
                                                    مجانا
                                            </span>
                                            @endif
                                            @if($plan->id!=1)

                                                <span style="font-weight: bold; color: green;" class="time"> لمده {{$plan->period}} يوما</span>

                                            @endif


                                        </p>
                                    </div>
                                    <div class="pricing-list">
                                        <p>{{ $plan->description }}</p>
                                        <ul class="advantages-list list-unstyled">
                                            @foreach($planDetails->where('plan_id',$plan->id)->get() as $planDetail)
                                                @if($planDetail->is_unlimited == 1)
                                                    <li style="color: #0a0c0d">يمكنك إضافة عدد غير محدود
                                                        من

                                                        @if($planDetail->key=='Branch')
                                                            الأفرع
                                                        @elseif($planDetail->key=='Investor')
                                                            المستثمرين
                                                        @elseif($planDetail->key=='Category')
                                                            التصنيفات
                                                        @elseif($planDetail->key=='Order')
                                                            الطلبات
                                                        @elseif($planDetail->key=='Vendor')
                                                            الموظفين
                                                        @else
                                                            {{ $planDetail->key }}
                                                        @endif
                                                    </li>
                                                @else
                                                    <li style="color: #0a0c0d">يمكنك إضافة عدد {{$planDetail->value}}
                                                        من

                                                        @if($planDetail->key=='Branch')
                                                            الأفرع
                                                        @elseif($planDetail->key=='Investor')
                                                            المستثمرين
                                                        @elseif($planDetail->key=='Category')
                                                            التصنيفات
                                                        @elseif($planDetail->key=='Order')
                                                            الطلبات
                                                        @elseif($planDetail->key=='Vendor')
                                                            الموظفين
                                                        @else
                                                            {{ $planDetail->key }}
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                {{--                                @dd($planSubscription)--}}
                                @if($planSubscription== null)
                                    @if($plan->id==1)
                                        <button type="button"
                                                class="mt-4 btn btn-primary subscribe-btn"
                                                data-plan-id="{{ $plan->id }}"
                                                disabled>
                                            الخطه الخاصة بك
                                        </button>
                                    @else
                                    @can('create_plans')

                                        <button type="button"
                                                class="mt-4 btn btn-primary subscribe-btn"
                                                data-plan-id="{{ $plan->id }}"
                                        >
                                            ترقيه
                                        </button>
                                    @endcan

                                    @endif
                                @elseif($planSubscription->plan_id == $plan->id && $planSubscription->status == 1)
                                    <div class="d-flex flex-column align-items-center mt-4">
                                        <!-- Enhanced Button with Hover Effect -->
                                        <button type="button"
                                                class="btn btn-primary subscribe-btn position-relative"
                                                data-plan-id="{{ $plan->id }}"
                                                disabled
                                                style="
                        padding: 10px 24px;
                        font-weight: 600;
                        border-radius: 8px;
                        border: none;
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        transition: all 0.3s ease;
                    ">
                                            <i class="fas fa-crown me-2"></i>
                                            الخطة الحالية
                                        </button>

                                        @if(now()->diffInDays($planSubscription->to)+1 > 0)
                                            <span class="badge mt-2 bg-white text-danger border border-danger"
                                                  style="
                    font-size: 0.85rem;
                    padding: 8px 16px;
                    border-radius: 50px;
                    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.15);
                    font-weight: 700;
                    letter-spacing: 0.5px;
                    transition: all 0.3s ease;
                    cursor: default;
                "
                                                  onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 12px rgba(220, 53, 69, 0.2)'"
                                                  onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 8px rgba(220, 53, 69, 0.15)'">
                                    <i class="fas fa-clock me-1"></i>
                                    {{now()->diffInDays($planSubscription->to)+1 }} يوم متبقي
                                </span>
                                        @else

                                            <span class="badge mt-2 bg-danger text-white" style="
                            font-size: 0.85rem;
                            padding: 8px 16px;
                            border-radius: 50px;
                            font-weight: 700;
                            letter-spacing: 0.5px;
                            animation: pulseAlert 1.5s infinite;
                        ">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            الخطة منتهية
                                        </span>

                                        @endif
                                    </div>

                                    <style>
                                        @keyframes pulseAlert {
                                            0% {
                                                transform: scale(1);
                                                opacity: 1;
                                            }
                                            50% {
                                                transform: scale(1.05);
                                                opacity: 0.9;
                                            }
                                            100% {
                                                transform: scale(1);
                                                opacity: 1;
                                            }
                                        }

                                        .subscribe-btn:hover {
                                            transform: translateY(-2px);
                                            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
                                        }
                                    </style>

                                @else
                                    @if($plan->id!=1)

                                        <button type="button"
                                                class="mt-4 btn btn-primary subscribe-btn"
                                                data-plan-id="{{ $plan->id }}"
                                        >
                                            ترقيه
                                        </button>
                                    @endif

                                @endif


                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="subscriptionModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <form id="subscriptionForm" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="plan_id" id="selectedPlanId">
                <div class="modal-content">
                    <div class="modal-header">
                        {{--                        <h5 class="modal-title col" id="subscriptionModalLabel"></h5>--}}
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-12">
                                <div class="form-group p-3 mb-4 rounded"
                                     style="background: #f8f9fa; border: 1px solid #e0e0e0; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                    <label class="d-block mb-3">
                                        <!-- Header -->
                                        <div class="d-flex align-items-center mb-2"
                                             style="color: #2c3e50; font-weight: bold;">
                                            <i class="fas fa-money-bill-wave me-2 m-2" style="color: #3498db;"></i>
                                            <span>طرق الدفع المتاحة :</span>
                                        </div>

                                        <!-- Phone Numbers -->
                                        <div class="d-flex flex-wrap align-items-center mb-2">
                                            <i class="fas fa-phone-alt me-2" style="color: #7f8c8d; width: 20px;"></i>
                                            <span class="me-2" style="font-weight: 600; color: #34495e;">الدفع عبر الرقم:</span>
                                            @foreach($phones as $phone)
                                                <span class="px-2 me-1 rounded"
                                                      style="background: #e8f4fc; color: #2980b9;">{{ $phone?->value }}</span>
                                                @if(!$loop->last)
                                                    <span class="me-1" style="color: #95a5a6;">،</span>
                                                @endif
                                            @endforeach
                                        </div>

                                        <!-- Bank Account -->
                                        <div class="d-flex flex-wrap align-items-center">
                                            <i class="fas fa-university me-2" style="color: #7f8c8d; width: 20px;"></i>
                                            <span class="me-2"
                                                  style="font-weight: 600; color: #34495e;">الحساب البنكي:</span>
                                            <span class="px-2 rounded"
                                                  style="background: #e8f4fc; color: #2980b9;">{{ $bank_account?->value }}</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="payment_receipt" class="form-control-label">صورة إيصال الدفع</label>
                                    <input type="file" class="dropify" name="payment_receipt" id="payment_receipt">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary" id="submitButton" disabled>إرسال</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('ajaxCalls')
    <script>
        $(document).ready(function () {
            // Enable the submit button when an image is selected
            $('#payment_receipt').on('change', function () {
                const isFileSelected = $(this).val() !== '';
                $('#submitButton').prop('disabled', !isFileSelected);
            });

            // When subscribe button is clicked
            $('.subscribe-btn').click(function () {
                const planId = $(this).data('plan-id');
                $('#selectedPlanId').val(planId);
                $('#subscriptionForm').attr('action', "{{ route('vendor.plans.store') }}");
                $('#subscriptionModal').modal('show');
            });

            // Form submission
            $('#subscriptionForm').submit(function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const url = $(this).attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('button[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري التحميل...');
                    },
                    success: function (response) {
                        $('#subscriptionModal').modal('hide');
                        toastr.success(response.message);
                        // window.location.reload()

                    },
                    error: function (xhr) {
                        let errorMessage = 'حدث خطأ، يرجى المحاولة مرة أخرى';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = '';
                            $.each(xhr.responseJSON.errors, function (key, value) {
                                errorMessage += value + '<br>';
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                    },
                    complete: function () {
                        $('button[type="submit"]').prop('disabled', false).html('حفظ وتقديم');
                        $('#payment_receipt').val(''); // Clear the input field
                    }
                });
            });
        });
    </script>
@endsection





{{--<section class="pricing pricing-1" id="pricing-1">--}}
{{--    <div class="container">--}}
{{--        @foreach($plans as $plan)--}}
{{--            <div class="col-12 col-lg-4 d-flex">--}}
{{--                @if($plan->id%3==0||$plan->id==1)--}}

{{--                    <div class="row">--}}
{{--                        @endif--}}

{{--                        <form>--}}
{{--                            <div class="price-table">--}}
{{--                                <div class="pricing-panel">--}}
{{--                                    <div class="pricing-body">--}}
{{--                                        <div class="pricing-heading">--}}
{{--                                            <h4 class="pricing-title">{{$plan->name}}</h4>--}}
{{--                                        </div>--}}
{{--                                        <div class="pricing-price">--}}
{{--                                            <p>--}}
{{--                                                <span class="currency">${{$plan->price}}</span>--}}
{{--                                                <span class="time"> شهرياً</span>--}}
{{--                                            </p>--}}
{{--                                        </div>--}}
{{--                                        <div class="pricing-list">--}}
{{--                                            <p>--}}
{{--                                                {{ $plan->description }}--}}
{{--                                            </p>--}}
{{--                                            <ul class="advantages-list list-unstyled">--}}
{{--                                                @foreach($planDetails as $planDetail)--}}
{{--                                                    @if($planDetail->plan_id == $plan->id)--}}
{{--                                                        @if($planDetail->is_unlimited == 1 )--}}
{{--                                                            <li>يمكنك إضافة عدد غير محدود--}}
{{--                                                                من {{$planDetail->key}}</li>--}}
{{--                                                        @else--}}
{{--                                                            <li>يمكنك إضافة--}}
{{--                                                                عدد {{$planDetail->value}}--}}
{{--                                                                من {{ $planDetail->key }}</li>--}}
{{--                                                        @endif--}}
{{--                                                    @endif--}}

{{--                                                @endforeach--}}

{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <button--}}
{{--                                        type="button"--}}
{{--                                        class="mt-4 btn btn-primary"--}}
{{--                                        data-plan-id="{{ $plan->id }}"--}}
{{--                                        data-bs-toggle="modal"--}}
{{--                                        data-bs-target="#exampleModal"--}}
{{--                                    >--}}
{{--                                        إشتراك--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            --}}{{--                        </div>--}}
{{--                        </form>--}}
{{--                        @if($plan->id%3==0||$plan->id==1)--}}

{{--                    </div>--}}
{{--                @endif--}}

{{--                @endforeach--}}


{{--            </div>--}}
{{--    </div>--}}
{{--</section>--}}
