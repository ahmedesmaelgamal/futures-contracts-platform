<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد الحساب</title>
</head>

<body style="margin: 0; padding: 0; background-color: #F8F5EF; font-family: Arial, sans-serif;">

<table width="100%" dir="rtl" style="background-color: #F8F5EF; padding: 20px; text-align: right;">
    <tr>
        <td align="center">
            <table width="600" dir="rtl"
                   style="background-color: #ffffff; border-radius: 5px; overflow: hidden; text-align: right; direction: rtl;">

                <!-- Logo Section -->
                <tr>
                    <td align="center" style="background-color: #F8F5EF; padding: 30px;">
                        <img src="{{ asset('logo.webp') }}" style="height: 70px;">
                    </td>
                </tr>

                <!-- Content Section -->
                <tr>
                    <td style="padding: 30px;" dir="rtl" align="right">
                        <h5 style="font-size: 24px; color: #25343E; margin-bottom: 15px;">
                            مرحبًا {{ $name }} 👋
                        </h5>
                        <p style="font-size: 18px; color: #667178; line-height: 1.6;">
                            لقد تلقيت هذا البريد الإلكتروني لأنك تحاول تسجيل الدخول أو تفعيل حسابك.
                            يرجى استخدام رمز التحقق التالي لإتمام العملية:
                        </p>

                        <div style="background-color: #F8F5EF; padding: 15px; text-align: center;
                                    font-size: 24px; font-weight: bold; color: #25343E;
                                    border-radius: 8px; margin: 20px auto; width: 200px;">
                            {{ $otp }}
                        </div>

                        <p style="font-size: 16px; color: #667178; line-height: 1.6;">
                            إذا لم تطلب هذا الرمز، يمكنك تجاهل هذه الرسالة بأمان.
                        </p>

                        <p style="font-size: 14px; color: #667178;">
                            مع خالص التحية،<br>
                            فريق أجل
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>

</html>

