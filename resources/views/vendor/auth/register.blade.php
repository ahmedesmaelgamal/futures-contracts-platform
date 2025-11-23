<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('vendor.auth.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css">
    <link href="{{asset('assets/admin')}}/assets/plugins/bootstrap5/css/bootstrap.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>
    <style>
        .swal2-popup,
        .swal2-modal {
            font-size: 16px !important;
        }

        body.dark-mode {
            color: #c5c9e6;
            background-color: #212741;
        }
    </style>

    <style>
        body.dark-mode {
            background-color: #212741;
            color: #ffffff;
        }

        .swal2-popup,
        .swal2-modal {
            font-size: 16px !important;
        }

        .dark-mode .swal2-popup,
        .dark-mode .swal2-modal {
            background-color: #212741;
            color: #fff;
        }

        .dark-mode .input-text {
            background-color: #212741;
            color: #fff;
        }

        .dark-mode .btn-login,
        .dark-mode .btn-language {
            /*background-color: #444;*/
            color: #fff;
        }

        .dark-mode .input-icon i {
            color: #bbb;
        }

        .signup-container,
        .welcome-container {
            transition: background-color 0.3s, color 0.3s;
        }
    </style>

    <style>
        .input-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .inp {
            flex: 1;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .input-text {
            width: 100%;
            padding: 10px;
        }

        .input-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .signup-container {
            padding: 5rem 10rem;
        }

        .signup-form {
            max-width: 100%;
        }
    </style>

</head>

<body class="d-flex align-items-center" style="background-image: linear-gradient(rgba(33,33,34,0.4), rgba(33,33,34,0.8)),url('{{asset('logo.webp')}}'); background-size: cover;
    background-repeat: no-repeat; height: 100vh">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-12"></div>
            <div class="col-lg-8 col-12">
                <div class="signup-container" style="background-color: white; width: 100%; box-shadow: 0 16px 32px 0 rgba(7, 28, 31, 0.1);">
                    <div class="d-flex justify-content-center w-100">
                        <img src="{{ asset('logo 1.png') }}" style="height: 90px;">
                    </div>
                    <form class="signup-form" action="{{route('vendor.register')}}" method="post" id="RegisterForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="inp">
                                    <label class="fs-4 mb-3">اسم المكتب</label>
                                    <input type="text" name="name" class="input-text" required style="background-color: rgb(232, 240, 254);">
                                    <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>
                                </label>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label class="inp">
                                    <label class="fs-4 mb-3">رقم الجوال</label>
                                    <div style="display: flex; align-items: center; gap: 5px; position: relative;">
                                        <input type="tel" name="phone" class="input-text" required style="background-color: rgb(232, 240, 254); flex: 1;padding-left: 50px">
                                        <label class="country-code" style="    position: absolute;left: 10px;font-size: 15px;">966+</label>

                                        <span class="input-icon"><i class="fa-solid fa-phone"></i></span>
                                    </div>
                                </label>
                            </div>


                            <div class="col-md-6 col-12 mb-3">
                                <label class="inp">
                                    <label class="fs-4 mb-3">البريد الالكترونى</label>
                                    <input type="email" name="email" id="email" class="input-text" autocomplete="off" required style="background-color: rgb(232, 240, 254);">
                                    <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>
                                </label>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="inp">
                                    <label class="fs-4 mb-3">رقم السجل التجارى</label>
                                    <input type="number" name="commercial_number" class="input-text" required style="background-color: rgb(232, 240, 254);">
                                    <span class="input-icon"><i class="fa-solid fa-building"></i></span>
                                </label>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="inp">
                                    <label class="fs-4 mb-3">  المدينة</label>
                                    <select name="city_id"  class="input-text" required style="background-color: rgb(232, 240, 254);">
                                        <option value="" selected disabled>اختر المدينة</option>
                                        @foreach($cites as $city)
                                        <option value="{{$city->id}}">{{$city->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-icon"><i class="fa-solid fa-city"></i></span>
                                </label>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="inp">
                                    <label class="fs-4 mb-3">رقم الهوية</label>
                                    <input type="number" name="national_id" class="input-text" required id="password" style="background-color: rgb(232, 240, 254);">
                                </label>
                            </div>


                            <div class="col-md-6 col-12 mb-3">
                                <label class="inp">
                                    <label class="fs-4 mb-3">كلمة المرور</label>
                                    <input type="password" name="password" class="input-text" required id="password" style="background-color: rgb(232, 240, 254);">
                                    <span class="input-icon input-icon-password" data-password><i class="fa-solid fa-eye"></i></span>
                                </label>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="inp">
                                    <label class="fs-4 mb-3">تأكيد كلمة المرور</label>
                                    <input type="password" name="password_confirmation" required class="input-text" style="background-color: rgb(232, 240, 254);">
                                    <span class="input-icon input-icon-password" data-password><i class="fa-solid fa-eye"></i></span>
                                </label>
                            </div>

                            <div class="col-12">

                                <button class="btn btn-primary fs-3 p-3 mb-5 w-100 mt-5" id="registerButton" style="background-color: #3cb9c7; border-color: #3cb9c7;">دخول</button>
                                <div class="text-center">
                                    <p class="text-mute fs-4"> لديك حساب؟
                                        <a style="text-decoration: none; color: #3cb9c7;" href="{{url('/partner')}}">سجل الآن</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="col-lg-2 col-12"></div>
        </div>
    </div>
</body>

@include('vendor.auth.js')
<script>
    document.getElementById('toggleDarkMode').addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
    });

    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(() => {
            document.getElementById("password").value = "";
            document.getElementById("email").value = "";
        }, 100);
    });
</script>



</html>
