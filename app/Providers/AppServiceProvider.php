<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Branch;
use App\Models\Setting;
use App\Models\Vendor;
use App\Observers\ActivityObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {




        
        \App\Models\Admin::observe(ActivityObserver::class);
        \App\Models\Vendor::observe(ActivityObserver::class);
        \App\Models\Stock::observe(ActivityObserver::class);
        \App\Models\Branch::observe(ActivityObserver::class);
        \App\Models\Category::observe(ActivityObserver::class);
        \App\Models\Client::observe(ActivityObserver::class);
        \App\Models\Investor::observe(ActivityObserver::class);
        \App\Models\Order::observe(ActivityObserver::class);
        \App\Models\OrderInstallment::observe(ActivityObserver::class);
        \App\Models\Plan::observe(ActivityObserver::class);
        \App\Models\PlanDetail::observe(ActivityObserver::class);
        \App\Models\PlanSubscription::observe(ActivityObserver::class);
        \App\Models\VendorBranch::observe(ActivityObserver::class);



        App::setLocale('ar');
        LaravelLocalization::setLocale('ar');

        Model::unguard();
        Route::macro('resourceWithDeleteSelected', function ($name, $controller, array $options = []) {

            Route::resource($name, $controller, $options);

            Route::post("$name/delete-selected", [
                'uses' => "$controller@deleteSelected",
                'as' => "$name.deleteSelected",
            ]);

            Route::post("$name/update-column-selected", [
                'uses' => "$controller@updateColumnSelected",
                'as' => "$name.updateColumnSelected",
            ]);
        });


        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {
            // $setting = Setting::where('vendor_id', Auth::guard('vendor')->user()->id)->get();

            // if ($setting->isEmpty()) {
            //     $setting = Setting::where('vendor_id', Auth::guard('vendor')->user()->parent_id)->get();
            // }


            // $view->with('setting', $setting);
        });
    }
}
