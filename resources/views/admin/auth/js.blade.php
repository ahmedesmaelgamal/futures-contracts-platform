<script src="{{asset('assets/admin')}}/assets/js/jquery-3.4.1.min.js"></script>
@toastr_js
@toastr_render
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>
<style>
    /* Change Toastr Background Color */
    #toast-container > .toast-error {
        background-color: #38bcc7 !important;
        color: #fff !important;
    }

    /* Change Toastr Progress Bar Color */
    #toast-container > .toast-error .toast-progress {
        background-color: #fff !important;
    }

</style>
<script>
    function expand(lbl) {
        var elemId = lbl.getAttribute("for");
        document.getElementById(elemId).style.height = "45px";
        document.getElementById(elemId).classList.add("my-style");
        lbl.style.transform = "translateY(-45px)";
    }

    $("form#LoginForm").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var url = $('#LoginForm').attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                $('#loginButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                    ' ></span> <span style="margin-left: 4px;">أنتظر قليلا</span>').attr('disabled', true);

            },
            complete: function () {


            },
            success: function (data) {



                // }
                if (data.status === 300) {
                    toastr.error('تم تأكيد الكود , قم بإدخال كلمة المرور الجديده');
                    $('#ResetPasswordButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    window.location.href = '{{route('admin.newPasswordForm',['email' => '__EMAIL__'])}}'.replace('__EMAIL__', encodeURIComponent(data.email));
                }
                if (data === 200 ) {
                    window.location.href = '{{route('adminHome')}}';
                }


                if (data === 500 ) {
                    $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);

                    toastr.error('الكود الذي ادخلته غبر صحيح او منتهي الصلاحية يرجى المحاوله مره اخرى');
                }
                if (data.status === 205 ) {
                    $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    toastr.error(' حدث خطأ أثناء محاولة تسجيل الدخول بهذا الحساب من فضلك تأكد من كلمة المرور ثم أعد المحاوله');
                }
                if (data.status === 203 ) {
                    $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    toastr.error('رقم الجوال هذا غير مسجل بالنظام');
                }
                if (data.status === 206 ) {
                    $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    toastr.error('هذا البريد الإلكتروني غير مسجل بالنظام');
                }
                if (data.status === 207 ) {
                    $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    toastr.error('هذا البريد الإلكتروني غير مفعل الرجاء تفعيله ثم إعاده المحاوله');
                }
                if (data.status === 208 ) {
                    $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    toastr.error('خطاء في كلمة المرور الرجاء التأكد من كلمة المرور و إعادة المحاوله');
                }

                if (data.status == 204 ) {
                    $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    window.location.href = '{{route('adminHome')}}';
                }else if (data.status === 500) {
                    toastr.error('لقد قمت بإدخال الكود الذي تم إرساله بشكل خاطئ');

                    window.location.href = '/';
                }


                if (data.status == 200) {

                    $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    window.location.href = '{{ route('admin.otp.verify', ['email' => '__EMAIL__','type'=>'login','resetPassword'=>1]) }}'.replace('__EMAIL__', encodeURIComponent(data.email));
                }
                // else {
                //     // toastr.error('خطأ في  بيانات الدخول');
                //     $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);
                // }

            },




            cache: false,
            contentType: false,
            processData: false
        });
    });

    $("form#RegisterForm").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var url = $('#RegisterForm').attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                $('#registerButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                    ' ></span> <span style="margin-left: 4px;">أنتظر قليلا</span>').attr('disabled', true);

            },
            complete: function () {


            },


            success: function (data) {

                if (data.status === 200) {

                    swal.fire({
                        title: "من فضلك قم بتفعيل حسابك",
                        icon: "success"
                    }).then(function () {
                        window.location.href = '{{ route('admin.otp.verify', ['email' => '__EMAIL__','type'=>'register','resetPassword'=>false]) }}'.replace('__EMAIL__', encodeURIComponent(data.email));
                    });

                } else {
                    toastr.error('خطأ في  بيانات الدخول');
                    $('#registerButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);
                }

            },
            error: function (data) {
                if (data.status === 500) {
                    $('#registerButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);
                    toastr.error('هناك خطأ ما');
                } else if (data.status === 422) {
                    $('#registerButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);
                    var errors = $.parseJSON(data.responseText);
                    $.each(errors, function (key, value) {
                        if ($.isPlainObject(value)) {
                            $.each(value, function (key, value) {
                                toastr.error(value);
                            });

                        } else {
                        }
                    });
                } else {
                    $('#registerButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);

                    toastr.error('خطأ في  بيانات الدخول');
                }
            },//end error method

            cache: false,
            contentType: false,
            processData: false
        });
    });

    $("form#ResetPasswordForm").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var url = $('#ResetPasswordForm').attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                $('#ResetPasswordButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                    ' ></span> <span style="margin-left: 4px;">أنتظر قليلا</span>').attr('disabled', true);

            },
            complete: function () {


            },
            success: function (data) {
                if (data.status === 405) {
                    toastr.error('هذا البريد الإلكتروني غير مسجل بالنظام');
                    $('#ResetPasswordButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    window.location.href = '/register';

                }
                if (data.status === 400 ) {
                    window.location.href = '{{route('admin.resetPasswordForm')}}';
                    sessionStorage.setItem('toastrMessage', 'الكود الذي أدخلته غير صحيح يرجى المحاوله مره أخرى');
                }
                if (data === 500) {
                    toastr.error('لقد قمت بإدخال الكود الذي تم إرساله بشكل خاطئ');
                    $('#ResetPasswordButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    window.location.href = '/';
                }
                if(data.status===209){
                    window.location.href = '{{ route('admin.otp.verify', ['email' => '__EMAIL__','type'=>'login','resetPassword'=>2]) }}'.replace('__EMAIL__', encodeURIComponent(data.email));
                    // $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    {{--window.location.href = '{{ route('vendor.newPasswordForm', ['email' => '__EMAIL__']) }}'.replace('__EMAIL__', encodeURIComponent(data.email));--}}
                }

                if (data.status === 200) {
                    $('#ResetPasswordButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    // window.location.href = '/register';

                    window.location.href = '{{ route('adminHome') }}';
                    // toastr.warning('من فضلك قم بإدخال الكود الذي تم إرساله على البريد الإلكتروني');
                }
                // else {
                //     // toastr.error('خطأ في  بيانات الدخول');
                //     $('#loginButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);
                // }

            },
            error: function (data) {
                if (data.status === 405) {
                    $('#ResetPasswordButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> تسجيل  `).attr('disabled', false);
                    window.location.href = '/register';
                    // toastr.error('هذا البريد الإلكتروني غير مسجل بالنظام');
                    sessionStorage.setItem('toastrMessage', 'هذا البريد الإلكتروني غير مسجل بالنظام');

                }
                if (data.status === 500) {
                    $('#ResetPasswordButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);
                    toastr.error('هناك خطأ ما');
                } else if (data.status === 422) {
                    $('#ResetPasswordButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);
                    var errors = $.parseJSON(data.responseText);
                    $.each(errors, function (key, value) {
                        if ($.isPlainObject(value)) {
                            $.each(value, function (key, value) {
                                toastr.error(value);
                            });

                        } else {
                        }
                    });
                } else {
                    $('#ResetPasswordButton').html(`<i id="lockId" class="fa fa-lock" style="margin-left: 6px"></i> دخول`).attr('disabled', false);

                    // toastr.error('خطأ في  بيانات الدخول');
                }
            },//end error method

            cache: false,
            contentType: false,
            processData: false
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toastrMessage = sessionStorage.getItem('toastrMessage');
        if (toastrMessage) {
            toastr.error(toastrMessage);
            sessionStorage.removeItem('toastrMessage');
        }
    });
    toastr.options = {
        toastClass: 'custom-toast',
        positionClass: 'toast-top-right',
        timeOut: 5000,
        closeButton: true,
        progressBar: true
    };
</script>

<style>
    .custom-toast {
        background-color: #0dcaf0 !important;
        color: #fff !important;
    }
</style>
