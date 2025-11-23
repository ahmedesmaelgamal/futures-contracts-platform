<?php

namespace App\Enums;

enum AdminModuleEnum: string
{
    //----------------------
    // System Admin Modules
    //----------------------

    case ADMIN = 'admin';
    case PLAN = 'plan';
    case PLAN_SUBSCRIPTION = 'plan_subscription';
    case INVESTOR = 'investor';
    case CATEGORY = 'category';
    case ORDER = 'order';
    case CLIENT = 'client';
    case VENDOR = 'vendor';
    case SETTING = 'setting';
    case ACTIVITY_LOG = 'activity_log';
    case UNSURPASSED = 'unsurpassed';
    public function lang(): string
    {
        return match ($this) {
            self::ADMIN => ' المشرفين',
            self::PLAN => ' الخطط',
            self::PLAN_SUBSCRIPTION => ' الاشتراكات',
            self::INVESTOR => ' المستثمرين',
            self::CATEGORY => ' الفئات',
            self::ORDER => ' الطلبات',
            self::CLIENT => ' العملاء',
            self::UNSURPASSED => 'المتعثرين',
            self::VENDOR => ' المكاتب',
            self::SETTING => 'إعدادات النظام',
            self::ACTIVITY_LOG => 'سجل النظام',
        };
    }

    public function permissions(): array
    {
        return [
            'read_' . $this->value,
            'create_' . $this->value,
            'update_' . $this->value,
            'delete_' . $this->value
        ];
    }

    public function langPermissions()
    {
        return [
            'read_' . $this->value => "اظهار " . $this->value,
            'create_' . $this->value => "أنشاء " . $this->value,
            'update_' . $this->value => " تحديث" . $this->value,
            'delete_' . $this->value => " حذف" . $this->value
        ];
    }
}
