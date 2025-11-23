<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('admin.auth.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css">
    <link href="{{asset('assets/admin')}}/assets/plugins/bootstrap5/css/bootstrap.min.css" rel="stylesheet"/>

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

<body class="d-flex align-items-center" style="background-image: linear-gradient(rgba(33,33,34,0.4), rgba(33,33,34,0.8)),url('{{asset('logo.webp')}}'); background-size: cover;
    background-repeat: no-repeat; height: 100vh">
<div class="container-fluid">
<div class="row">
<div class="col-lg-3 col-12"></div>
<div class="col-lg-6 col-12">
<div class="signup-container" style="background-color: white; width: 100%;">
        <div class="text-center w-100">
        <div class="d-flex justify-content-center w-100">
    <img src="{{ asset('logo 1.png') }}" style="height: 90px;">
    </div>
        </div>

        <h4>إرسال الكود التأكيدي عبر البريد الإلكتروني</h4>

        <form class="signup-form" action="{{route('vendor.verifyResetPassword')}}" method="post" id="ResetPasswordForm">
            @csrf
            <label class="inp">
                <input type="email" name="input" class="input-text" placeholder="&nbsp;" id="inputField" style="background-color: rgb(232, 240, 254);">
                <span class="label" id="placeHolder">البريد الالكتروني</span> <!-- Default placeholder -->
                <span class="input-icon">
        <i class="fa-solid fa-envelope"></i> <!-- Default icon -->
    </span>
            </label>
            <div class="d-flex align-items-center">
            </div>
            <button class="btn btn-primary fs-3 p-3" id="ResetPasswordButton" style="background-color: #3cb9c7; border-color: #3cb9c7;">إرسال</button>

        </form>
    </div>
</div>
<div class="col-lg-3 col-12"></div>
</div>

</div>
</body>

@include('vendor.auth.js')
<script>
    document.getElementById('toggleDarkMode').addEventListener('click', function () {
        document.body.classList.toggle('dark-mode');
    });
</script>

</html>
