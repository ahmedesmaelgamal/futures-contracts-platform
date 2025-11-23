<?php

namespace App\Enums;

enum OrderStatus : int
{
    // Admin Dashboard
    case NEW = 0;
    case PARTIALLY_PAID = 1;
    case LIMITED = 2;
    case COMPLETELY_PAID = 3;



    public function lang(): string
    {
        return match ($this) {
            self::NEW => 'غير مسدد',
            self::PARTIALLY_PAID => 'مسدد جزئيا',
            self::LIMITED => 'ممهل لمده محدود',
            self::COMPLETELY_PAID => 'مسدد بالكامل',
        };

    }


}
