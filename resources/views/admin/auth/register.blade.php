<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('admin.auth.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>
    <style>
        .swal2-popup,
        .swal2-modal {
            font-size: 16px !important;
        }

        body.dark-mode {
            color: #c5c9e6;
            background-color: #212741; }
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

</head>

<body class="">
<div class="container">


    <main class="signup-container">
        <h1 class="heading-primary"> منصة أجل<span class="span-blue">.</span></h1>
        <p class="text-mute">قم بإدخال بيانات التأكيد</p>

        <form class="signup-form" action="{{route('admin.register')}}" method="post" id="RegisterForm">
            @csrf
            <div class="input-row">
                <label class="inp">
                    <input type="number" name="phone" class="input-text" required>
                    <span class="label">رقم الهاتف</span>
                    <span class="input-icon"><i class="fa-solid fa-phone"></i></span>
                </label>
            </div>

            <div class="input-row">
                <label class="inp">
                    <input type="email" name="email" class="input-text" required>
                    <span class="label">البريد الإلكتروني</span>
                    <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>
                </label>
{{--                <label class="inp">--}}
{{--                    <input type="number" name="commercial_number" class="input-text" required>--}}
{{--                    <span class="label">رقم السجل التجاري</span>--}}
{{--                    <span class="input-icon"><i class="fa-solid fa-building"></i></span>--}}
{{--                </label>--}}
            </div>

            <div class="input-row">
                <label class="inp">
                    <select name="region_id" class="input-text" required>
                        @foreach($regions as $region)
                            <option value="{{$region->id}}">{{$region->name}}</option>
                        @endforeach
                    </select>
                    <span class="label">الحي</span>
                    <span class="input-icon"><i class="fa-solid fa-city"></i></span>
                </label>
                <label class="inp">
                    <input type="number" name="national_id" class="input-text" required  id="password">
                    <span class="label">رقم الهوية</span>
                </label>
            </div>

            <div class="input-row">
                <label class="inp">
                    <input type="password" name="password" class="input-text" required  id="password">
                    <span class="label">كلمة المرور</span>
                    <span class="input-icon input-icon-password" data-password><i class="fa-solid fa-eye"></i></span>
                </label>

                <label class="inp">
                    <input type="password" name="password_confirmation" required class="input-text" >
                    <span class="label">تأكيد كلمة المرور</span>
                    <span class="input-icon input-icon-password" data-password><i class="fa-solid fa-eye"></i></span>
                </label>
            </div>

            <button class="btn btn-login" id="registerButton">دخول</button>
            <p class="text-mute"> لديك حساب؟
                <a href="{{url('/admin/login')}}">سجل الآن</a>
            </p>
        </form>

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
        </style>

    </main>
    <div class="welcome-container" style="background-color: white !important;"
         >

        <img style="border-radius: 10%" src="{{asset('logo.webp')}}">
    </div>
</div>
</body>

@include('admin.auth.js')
    <script>
        document.getElementById('toggleDarkMode').addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
        });
    </script>
</html>
