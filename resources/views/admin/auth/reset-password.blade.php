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

</head>

<body class="">
<div class="container">
    {{--    <div class="language-switcher">--}}
    {{--        <a href="{{ LaravelLocalization::getLocalizedURL(lang() == 'en' ? 'ar' : 'en', null, [], true) }}"--}}
    {{--           class="btn btn-language" style="background-color: #0285CE;">{{ lang() == 'en' ? trns('Arabic') : trns('English') }}</a>--}}
    {{--    </div>--}}
    {{--    <div class="dark-switcher">--}}
    {{--        <a id="toggleDarkMode" class="btn btn-language">{{ trns('dark_mode') }}</a>--}}
    {{--    </div>--}}

    <main class="signup-container" style="margin-top: 40px">
        <h1 class="heading-primary">منصة أجل<span class="span-blue">.</span></h1>

{{--        <form class="signup-form" action="{{route('admin.login')}}" method="POST" id="LoginForm">--}}
{{--            @csrf--}}
{{--            @method('POST')--}}
{{--            <label class="inp">--}}
{{--                <input type="text" name="input" class="input-text" placeholder="&nbsp;">--}}
{{--                <span class="label">إدخل البريد الإلكتروني أو رقم الهاتف</span>--}}
{{--                <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>--}}
{{--            </label>--}}
{{--            <label class="inp">--}}
{{--                <input type="password" name="password" class="input-text" placeholder="&nbsp;" id="password">--}}
{{--                <span class="label"> كلمة المرور</span>--}}
{{--                <span class="input-icon input-icon-password" data-password><i class="fa-solid fa-eye"></i></span>--}}
{{--            </label>--}}
{{--            <button class="btn btn-login" id="loginButton"> تسجيل الدخول</button>--}}
{{--        </form>--}}



        <form class="signup-form" action="{{route('vendor.login')}}" method="post" id="LoginForm">
            @csrf
            <label class="inp">
                <input type="email" name="input" class="input-text" placeholder="&nbsp;" id="inputField">
                <span class="label" id="placeHolder">البريد الالكتروني</span> <!-- Default placeholder -->
                <span class="input-icon">
        <i class="fa-solid fa-envelope"></i> <!-- Default icon -->
    </span>
            </label>
            <label class="inp">
                <input type="password" name="password" class="input-text" placeholder="&nbsp;" id="password">
                <span class="label">كلمة المرور</span>
                <span class="input-icon input-icon-password" data-password>
        <i class="fa-solid fa-eye"></i>
    </span>
            </label>
            {{--            <input class="inp">--}}
            {{--            <input type="text" name="input" class="input-text" placeholder="&nbsp;">--}}
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted">إرسال الكود التأكيدي عبر البريد الإلكتروني </span>
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="email" name="verificationType" id="verificationTypeEmail" checked>
                        <label class="form-check-label" for="verificationTypeEmail">Email</label>
                    </div>
{{--                    <div class="form-check">--}}
{{--                        <input class="form-check-input" type="radio" value="phone" name="verificationType" id="verificationTypePhone">--}}
{{--                        <label class="form-check-label" for="verificationTypePhone">Phone</label>--}}
{{--                    </div>--}}
                </div>
            </div>
            <button class="btn btn-login" id="loginButton">تسجيل </button>

        </form>
    </main>
    <div class="welcome-container" style="background-color: white !important;">

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

<script>
    document.querySelectorAll('input[name="verificationType"]').forEach((elem) => {
        elem.addEventListener("change", function (event) {
            const inputField = document.getElementById('inputField');
            const placeHolder = document.getElementById('placeHolder');
            const inputIcon = document.querySelector('.input-icon i'); // Get the icon element

            if (event.target.value === 'email') {
                inputField.type = 'email';
                placeHolder.textContent = 'البريد الالكتروني'; // Update placeholder text
                inputIcon.className = 'fa-solid fa-envelope'; // Change icon to email
            } else if (event.target.value === 'phone') {
                inputField.type = 'number';
                placeHolder.textContent = 'رقم الجوال'; // Update placeholder text
                inputIcon.className = 'fa-solid fa-phone'; // Change icon to phone
            }
        });
    });
</script>
</html>
