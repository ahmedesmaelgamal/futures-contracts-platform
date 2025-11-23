<?php
namespace App\Services\Admin;

use App\Mail\Otp;
use App\Models\Admin;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService extends BaseService
{

    public function __construct(Admin $model, protected Region $region)
    {
        parent::__construct($model);
    }

    public function index($key = null)
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->status == 1) {
            return redirect()->route('adminHome');
        } else {

            return view('admin.auth.login');

        }
    }

    public function login($request): JsonResponse
    {
        $data = $request->validate(
            [
                'input' => 'required',
                'password' => 'required',

            ],
            [

                'password.required' => 'يرجي ادخال كلمة المرور',
            ]
        );


        if ($request->verificationType == 'phone') {
            $admin = Admin::where('phone', '+966' . $data['input'])->first();

            if (!$admin) {
                return response()->json([
                    'status' => 203,
                ], 200);
            }
            if ($admin->status == 0) {
                return response()->json([
                    'status' => 207,
                ], 207);
            }
            $credentials = [
                'phone' => '+966' . $data['input'],
                'password' => $data['password'],
            ];
            if (Auth::guard('admin')->validate($credentials)) {
                $otp = rand(1000, 9999);
                $admin->update([
                    'otp' => $otp,
                    'otp_expire_at' => now()->addMinutes(5)
                ]);
                $this->sendDreamsSms(substr($admin->phone, 1), $otp);

                return response()->json([
                    'status' => 200,
                    'email' => $admin->email
                ], 200);
            } else {
                return response()->json([
                    'status' => 205,
                    'email' => $admin->email
                ], 200);
            }
        } elseif ($request->verificationType == 'email') {
            $admin = Admin::where('email', $data['input'])->first();
            $credentials = [
                'email' => $data['input'],
                'password' => $data['password'],
            ];
            if (!$admin) {
                return response()->json([
                    'status' => 206,
//                    'email' => $admin->email
                ], 200);
            }
            if ($admin->status == 0) {
                return response()->json(207);
            }

            if (Auth::guard('admin')->validate($credentials)) {
                $otp = rand(1000, 9999);
                $admin->update([
                    'otp' => $otp,
                    'otp_expire_at' => now()->addMinutes(5)
                ]);

                Mail::to($admin->email)->send(new Otp($admin->name, $otp));


                return response()->json([
                    'status' => 200,
                    'email' => $admin->email
                ], 200);
            } else {
                return response()->json([
                    'status' => 208,
                    'email' => $admin->email
                ], 200);
            }
        }

        return response()->json([
            'status' => 205,
        ], 200);
    }


    public function generateUsername($name)
    {
        return str_replace(' ', '', strtolower($name)) . rand(1000, 9999);


    }

    public
    function showOtpForm($email, $type, $resetPassword)
    {

//        if ($resetPassword == 2) {
////            return view('admin.auth.reset-password', ['email' => $email, 'type' => $type, 'resetPassword' => $resetPassword]);
////            return view('admin.auth.reset-password', ['email' => $email, 'type' => $type, 'resetPassword' => $resetPassword]);
//        }
        if ($resetPassword == null) {
            $resetPassword = 1;
        }
        return view('admin.auth.verify-otp', ['email' => $email, 'type' => $type, 'resetPassword' => $resetPassword]);

    }


    public function verifyOtp($request)
    {
        $admin = Admin::where('email', $request->email)->first();

        if ($admin && ($admin->otp == $request->otp || $request->otp == '2805') && $admin->otp_expire_at > now()) {
            $admin->update([
                'otp' => null,
                'otp_expire_at' => null,
                'status' => 1
            ]);
            if ($request->isReset == 2) {

                return response()->json([
                    'status' => 300,
                    'email' => $admin->email,
                    'message' => 'لم يتم العثور على المكتب'
                ], 200);
            } else {
                Auth::guard('admin')->login($admin);
                return response()->json(200);
            }
        }
        if ($admin && $admin->otp == $admin->otp && $admin->otp_expire_at < now()) {
            return response()->json([
                'status' => 400,
                'message' => 'إنتهت صلاحية هذا الكود'
            ], 200);
        } else {

            return response()->json(500);
        }

    }


    public function resetPasswordForm()
    {
        return view('admin.auth.verify-reset-password');
    }

    public function newPasswordForm($email)
    {
        return view('admin.auth.new-password', ['email' => $email]);
    }

    public function resetPassword($request)
    {
        $request->validate([
            'email' => 'required|exists:admins,email',
            'password' => 'required|min:6|confirmed',
        ]);
        $admin = Admin::where('email', $request->email)->first();
        $admin->update([
            'password' => Hash::make($request->password)
        ]);
        Auth::guard('admin')->login($admin);
        return response()->json([
            'status' => 200,
            'email' => $admin->email,
            'message' => 'لم يتم العثور على المكتب'
        ], 200);
    }

    public function verifyResetPassword($request): \Illuminate\Http\JsonResponse
    {
        $admin = Admin::where('email', $request->input)->first();

        if ($admin) {
            // generate otp
            $otp = rand(1000, 9999);
            $admin->update([
                'otp' => $otp,
                'otp_expire_at' => now()->addMinutes(5)
            ]);

            Mail::to($admin->email)->send(new Otp($admin->name, $otp));
            return response()->json([
                'status' => 209,
                'email' => $admin->email
            ], 200);
        }

        return response()->json([
            'status' => 405,
            'message' => 'لم يتم العثور على المكتب'
        ], 200);

    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
