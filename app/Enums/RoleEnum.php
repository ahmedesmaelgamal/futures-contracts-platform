<?php

namespace App\Enums;

enum RoleEnum : int
{
    // Admin Dashboard
    case SUPER_ADMIN = 1;
    case ADMIN_FINANCE = 2;
    case ADMIN_MARKETING = 3;
    case ADMIN_SUPPORT = 4;

    // Partner Dashboard
    case PARTNER_ADMIN = 5;
    case PARTNER_FINANCE = 6;
    case PARTNER_RESERVATION = 7;

    // Client Dashboard
    case CLIENT = 8;


    public function lang(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'مشرف عام',
            self::ADMIN_FINANCE => 'مسؤول مالي',
            self::ADMIN_MARKETING => 'مسؤول تسويق',
            self::ADMIN_SUPPORT => 'مسؤول دعم',
            self::PARTNER_ADMIN => 'مدير شريك',
            self::PARTNER_FINANCE => 'المالية للشريك',
            self::PARTNER_RESERVATION => 'الحجوزات للشريك',
            self::CLIENT => 'عميل',
        };

    }

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'super_admin',
            self::ADMIN_FINANCE => 'admin_finance',
            self::ADMIN_MARKETING => 'admin_marketing',
            self::ADMIN_SUPPORT => 'admin_support',
            self::PARTNER_ADMIN => 'partner_admin',
            self::PARTNER_FINANCE => 'partner_finance',
            self::PARTNER_RESERVATION => 'partner_reservation',
            self::CLIENT => 'client',
        };
    }
}
