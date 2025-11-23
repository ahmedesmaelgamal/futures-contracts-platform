<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\InvestorController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\PlanSubscriptionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\UnsurpassedController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/


Route::get('/send-otp', [AdminController::class, 'sendOtp']);


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {

        Route::get('/', function () {
            return view('admin.auth.select');
        })->name('select');

        Route::get('admin', [AuthController::class, 'index']);


        Route::group(['prefix' => 'admin'], function () {
            Route::get('/login', [AuthController::class, 'index'])->name('admin.login');

            Route::POST('login', [AuthController::class, 'login'])->name('admin.login');

            Route::get('/verify-otp/{email}/{type}/{resetPassword}', [AuthController::class, 'showOtpForm'])->name('admin.otp.verify');
            Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('admin.otp.check');


            Route::POST('/verify-reset-password', [AuthController::class, 'verifyResetPassword'])->name('admin.verifyResetPassword');
            Route::get('/reset-password', [AuthController::class, 'resetPasswordForm'])->name('admin.resetPasswordForm');
            Route::get('/new-password/{email}', [AuthController::class, 'newPasswordForm'])->name('admin.newPasswordForm');
            Route::POST('/reset-password/{email}', [AuthController::class, 'ResetPassword'])->name('admin.resetPassword');


            Route::group(['middleware' => 'admin'], function () {
                Route::get('/', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('adminHome');


                Route::resourceWithDeleteSelected('roles', RoleController::class, [
                    'as' => 'admin'  // Prefix "admin." to all route names
                ]);
                Route::post('roles/delete-selected', [RoleController::class, 'deleteSelected'])->name('admin.roles.deleteSelected');
                Route::get('activity_logs', [ActivityLogController::class, 'index'])->name('admin.activity_logs.index');
                Route::delete('activity_logs/{id}', [ActivityLogController::class, 'destroy'])->name('admin.activity_logs.destroy');
                Route::post('activity_logs/delete-selected', [ActivityLogController::class, 'deleteSelected'])->name('admin.activity_logs.deleteSelected');


                #============================ vendors ====================================
//                    Route::resourceWithDeleteSelected('vendors', VendorController::class);
                Route::get('vendors/index', [VendorController::class, 'index'])->name('admin.vendors.index');
                Route::get('vendors/create', [VendorController::class, 'create'])->name('admin.vendors.create');
                Route::get('vendors/LoginAsVendor/{id}', [VendorController::class, 'LoginAsVendor'])->name('admin.vendors.LoginAsVendor');
                Route::post('vendors', [VendorController::class, 'store'])->name('admin.vendors.store');
                Route::put('vendors/update', [VendorController::class, 'update'])->name('admin.vendors.update');
                Route::delete('vendors/{id}', [VendorController::class, 'destroy'])->name('admin.vendors.destroy');
                Route::get('vendors/{id}/edit', [VendorController::class, 'edit'])->name('admin.vendors.edit');
                Route::post('vendors/delete-selected', [VendorController::class, 'deleteSelected'])->name('admin.vendors.deleteSelected');
                Route::post('vendors/update-column-selected', [VendorController::class, 'updateColumnSelected'])->name('admin.vendors.updateColumnSelected');
                Route::get('getToDate', [PlanSubscriptionController::class, 'getToDate'])->name('getToDate');

                #============================ Admin ====================================
                Route::resourceWithDeleteSelected('admins', AdminController::class);
                #============================ countries ==================================
                Route::resourceWithDeleteSelected('countries', CountryController::class);
                #============================ cities ==================================
                Route::resourceWithDeleteSelected('cities', CityController::class);
                #============================ Plans ==================================
                Route::resourceWithDeleteSelected('Plans', PlanController::class);
                #============================ planSubscription ==================================
                Route::resourceWithDeleteSelected('planSubscription', PlanSubscriptionController::class);
                #============================ settings ==================================
                Route::resourceWithDeleteSelected('planSubscription', PlanSubscriptionController::class);
                Route::post('subscriptions/reject/{id}', [PlanSubscriptionController::class, 'rejectSubscription'])->name('subscriptions.reject');
                Route::post('subscriptions/activate/{id}', [PlanSubscriptionController::class, 'activateSubscription'])->name('subscriptions.activate');
                Route::get('settings', [SettingController::class, 'index'])->name('settings.index');


                Route::get('investors', [InvestorController::class, 'index'])->name('admin.investors.index');
                Route::get('clients', [ClientController::class, 'index'])->name('admin.clients.index');
                Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
                Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories.index');

                Route::get('my_profile', [AdminController::class, 'myProfile'])->name('myProfile');
                Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');

                Route::get('setting', [SettingController::class, 'index'])->name('admin.setting');
                Route::put('settings', action: [SettingController::class, 'update'])->name('settings.update');


                Route::POST('setting/store', [SettingController::class, 'store'])->name('setting.store');
                Route::POST('setting/update/{id}/', [SettingController::class, 'update'])->name('settingUpdate');

                Route::resourceWithDeleteSelected('unsurpasseds', UnsurpassedController::class, ['as' => 'admin']);
                Route::post('unsurpasseds/delete-selected', [UnsurpassedController::class, 'deleteSelected'])->name('admin.unsurpasseds.deleteSelected');
                Route::post('unsurpasseds/update-column-selected', [UnsurpassedController::class, 'updateColumnSelected'])->name('admin.unsurpasseds.updateColumnSelected');
                Route::get('unsurpasseds', [UnsurpassedController::class, 'index'])->name('admin.unsurpasseds.index');

            });
//        });

            #=======================================================================
            #============================ ROOT =====================================
            #=======================================================================
            Route::get('/clear', function () {

                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('optimize:clear');
                return response()->json(['status' => 'success', 'code' => 1000000000]);
            });

        }
        );
    }
);


