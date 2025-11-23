<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityObserver
{
    public function created(Model $model)
    {
        $this->logActivity($model, 'إضافة');
    }

    public function updated(Model $model)
    {
        $this->logActivity($model, 'تحديث');
    }

    public function deleted(Model $model)
    {
        $this->logActivity($model, 'حذف');
    }

    private function logActivity(Model $model, $action)
    {
        $user = Auth::guard('admin')->user() ?? Auth::guard('vendor')->user();
        if (!$user) return;

        $tableName = $this->getTableNameInArabic($model->getTable());

        // جلب اسم الكيان إن وُجد
        $entityName = $model->name ?? $model->title ?? '';

        $message = "قام {$user->name} بـ{$action} {$entityName} في جدول {$tableName}.";

        ActivityLog::create([
            'userable_id'   => $user->id,
            'userable_type' => get_class($user),
            'action'        => $message,
            'ip_address'    => request()->ip(),
        ]);
    }




    private function getTableNameInArabic($tableName)
    {
        $tables = [
            'admins' => 'المشرفين',
            'vendors' => 'المكاتب',
            'users' => 'المستخدمين',
            'products' => 'المنتجات',
            'orders' => 'الطلبات',
            'categories' => 'الاصناف',
            'cities' => 'المدن',
            'branches' => 'الفروع',
            'clients' => 'العملاء',
            'stocks' => 'المخزون',
            'regions' => 'المناطق',
            'countries' => 'الدول',
            'plans' => 'الخطط',
            'settings' => 'الإعدادات',
            'areas' => 'الأحياء',
            'plan_details' => 'تفاصيل الخطط',
            'vendor_branches' => 'فروع المكاتب',
            'investors' => 'المستثمرين',
            'activity_logs' => 'سجل النشاطات',
            'operations' => 'العمليات',
            'stock_details' => 'تفاصيل المخزون',
            'order_installments' => 'أقساط الطلبات',
            'unsurpassedes' => 'غير المصروفة',
            'order_details' => 'تفاصيل الطلب',
            'order_statuses' => 'حالات الطلب',
            'plan_subscriptions' => 'اشتراكات الخطط',
            'permissions' => 'الصلاحيات',
        ];


        return $tables[$tableName] ?? $tableName;
    }
}
