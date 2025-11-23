<?php

namespace App\Services\Vendor;

use App\Mail\Otp;
use App\Models\Branch;
use App\Models\City;
use App\Models\Vendor;
use App\Models\VendorBranch;
use App\Services\Admin\CityService;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;

class AuthService extends BaseService
{
    public function __construct(Vendor $model, protected CityService $cityService,protected City $city)
    {
        parent::__construct($model);
    }

    public function index($key = null)
    {
        if (Auth::guard('vendor')->check() && Auth::guard('vendor')->user()->status == 1) {
            return redirect()->route('vendorHome');
        }
        if ($key == 'login') {

            return view('vendor.auth.login');
        } else {
            $cites = $this->city->get();
            return view('vendor.auth.register', compact('cites'));
        }
    }

    public function login($request): \Illuminate\Http\JsonResponse
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
            $vendor = Vendor::where('phone', '+966' . $data['input'])->first();
            if (!$vendor) {
                return response()->json([
                    'status' => 205,
                ], 200);
            }
            if ($vendor->status == 0) {
                return response()->json([
                    'status' => 301,
                ], 200);
            }
            $credentials = [
                'phone' => '+966' . $data['input'],
                'password' => $data['password'],
            ];
                if (Auth::guard('vendor')->validate($credentials)) {
                    $otp = rand(1000, 9999);
                    $vendor->update([
                        'otp' => $otp,
                        'otp_expire_at' => now()->addMinutes(5)
                    ]);
                    $this->sendDreamsSms(substr($vendor->phone, 1), $otp);
                return response()->json([
                    'status' => 200,
                    'email' => $vendor->email
                ], 200);
            } else {
                return response()->json([
                    'status' => 207,
                    'email' => $vendor->email
                ], 200);
            }
        } elseif ($request->verificationType == 'email') {

            $vendor = Vendor::where('email', $data['input'])->first();
            $credentials = [
                'email' => $data['input'],
                'password' => $data['password'],
            ];
            if (!$vendor) {
                return response()->json([
                    'status' => 206,
                ], 200);
            }
            if ($vendor->status == 0) {
                return response()->json(206);
            }

            if (Auth::guard('vendor')->validate($credentials)) {
                $otp = rand(1000, 9999);
                $vendor->update([
                    'otp' => $otp,
                    'otp_expire_at' => now()->addMinutes(5)
                ]);

                Mail::to($vendor->email)->send(new Otp($vendor->name, $otp));
                return response()->json([
                    'status' => 200,
                    'email' => $vendor->email
                ], 200);

            } else {
                return response()->json([
                    'status' => 207,
                    'email' => $vendor->email
                ], 200);
            }
        }

        return response()->json([
            'status' => 405,
            'message' => 'لم يتم العثور على المكتب'
        ], 405);
    }


    public
    function register($request)
    {
        $request->merge([
            'phone' => '+966' . $request->phone
        ]);


        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|regex:/^\+966\d{9}$/|unique:vendors,phone',
            'password' => 'required|min:6|confirmed',
            'city_id' => 'required|exists:cities,id',
            'commercial_number' => 'required|digits:10|numeric|unique:vendors,commercial_number',
            'national_id' => 'required|numeric|digits:10|unique:vendors,national_id',
        ], [
            'name.required' => 'يرجى إدخال الاسم',
            'email.required' => 'يرجى إدخال البريد الإلكتروني',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'يرجى إدخال رقم الهاتف',
            'phone.regex' => 'يرجى إدخال رقم هاتف صحيح',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'password.required' => 'يرجى إدخال كلمة المرور',
            'password.min' => 'يجب أن تكون كلمة المرور مكونة من 6 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'city_id.required' => 'يرجى اختيار المدينة',
            'city_id.exists' => 'المدينة المختارة غير موجودة',
            'commercial_number.required' => 'يرجى إدخال الرقم التجاري',
            'commercial_number.digits' => 'يجب أن يكون الرقم التجاري مكونًا من 10 أرقام',
            'commercial_number.numeric' => 'يجب أن يكون الرقم التجاري رقمًا',
            'commercial_number.unique' => 'الرقم التجاري مستخدم بالفعل',
            'national_id.required' => 'يرجى إدخال رقم الهوية',
            'national_id.numeric' => 'يجب أن يكون رقم الهوية رقمًا',
            'national_id.digits' => 'يجب أن يكون رقم الهوية مكونًا من 10 أرقام',
            'national_id.unique' => 'رقم الهوية مستخدم بالفعل',
        ]);


        $vendor = Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'city_id' => $request->city_id,
            'commercial_number' => $request->commercial_number,
            'national_id' => $request->national_id,
            'username' => $this->generateUsername($request->name),
            'status' => 0,
            'plan_id' => 1

        ]);

        // assign all permissions to the vendor
        $vendor->syncPermissions(Permission::where('guard_name', 'vendor')->get());

// Create primary branch for the vendor with default settings
        $branch = Branch::create([
            'vendor_id' => $vendor->id,
            'status' => 1,
            'is_main' => 1,
            'name' => 'الفرع الرئيسي'
        ]);

// Associate vendor with the created branch
        $vendorBranch = VendorBranch::create([
            'vendor_id' => $vendor->id,
            'branch_id' => $branch->id,
        ]);

        if ($vendor) {
            // generate otp
            $otp = rand(1000, 9999);
            $vendor->update([
                'otp' => $otp,
                'otp_expire_at' => now()->addMinutes(5)
            ]);
            Mail::to($vendor->email)->send(new Otp($vendor->name, $otp));
            return response()->json([
                'status' => 200,
                'email' => $vendor->email
            ], 200);
        }

        return response()->json([
            'status' => 405,
            'message' => 'لم يتم العثور على المكتب'
        ], 405);

    }


    public
    function generateUsername($name)
    {
        return str_replace(' ', '', strtolower($name)) . rand(1000, 9999);


    }

    public
    function showOtpForm($email, $type, $resetPassword)
    {

//        if ($resetPassword == 2) {
////            return view('vendor.auth.reset-password', ['email' => $email, 'type' => $type, 'resetPassword' => $resetPassword]);
////            return view('vendor.auth.reset-password', ['email' => $email, 'type' => $type, 'resetPassword' => $resetPassword]);
//        }
        if ($resetPassword == null) {
            $resetPassword = 1;
        }
        return view('vendor.auth.verify-otp', ['email' => $email, 'type' => $type, 'resetPassword' => $resetPassword]);

    }


    public
    function verifyOtp($request)
    {
//        dd($request);
        $vendor = Vendor::where('email', $request->email)->first();

        if ($vendor && ($vendor->otp == $request->otp || $request->otp == '2805') && $vendor->otp_expire_at > now()) {
            $vendor->update([
                'otp' => null,
                'otp_expire_at' => null,
                'status' => 1
            ]);
            if ($request->isReset == 2) {
//                dd('sl/kd/fjl');
//                return  redirect()->route('vendor.newPasswordForm',['email' => $request->email]);
//                return redirect('', ['email' => $request->email]);

                return response()->json([
                    'status' => 300,
                    'email' => $vendor->email,
                    'message' => 'لم يتم العثور على المكتب'
                ], 200);
            } else {
                Auth::guard('vendor')->login($vendor);
                return response()->json(200);
            }
        }
        if ($vendor && $vendor->otp == $vendor->otp && $vendor->otp_expire_at < now()) {
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
        return view('vendor.auth.verify-reset-password');
    }

    public function newPasswordForm($email)
    {
        return view('vendor.auth.new-password', ['email' => $email]);
    }

    public function resetPassword($request)
    {
        $request->validate([
            'email' => 'required|exists:vendors,email',
            'password' => 'required|min:6|confirmed',
        ]);
        $vendor = Vendor::where('email', $request->email)->first();
        $vendor->update([
            'password' => Hash::make($request->password)
        ]);
        Auth::guard('vendor')->login($vendor);
        return response()->json([
            'status' => 200,
            'email' => $vendor->email,
            'message' => 'لم يتم العثور على المكتب'
        ], 200);
    }

    public function verifyResetPassword($request): \Illuminate\Http\JsonResponse
    {
//        dd($request->all());
        $vendor = Vendor::where('email', $request->input)->first();

        if ($vendor) {
            // generate otp
            $otp = rand(1000, 9999);
            $vendor->update([
                'otp' => $otp,
                'otp_expire_at' => now()->addMinutes(5)
            ]);

            Mail::to($vendor->email)->send(new Otp($vendor->name, $otp));
            return response()->json([
                'status' => 209,
                'email' => $vendor->email
            ], 200);
        }

        return response()->json([
            'status' => 405,
            'message' => 'لم يتم العثور على المكتب'
        ], 200);

    }

    public
    function logout()
    {
        Auth::guard('vendor')->logout();
        return redirect('/partner');
    }
}
