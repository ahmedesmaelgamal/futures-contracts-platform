<?php

namespace App\Enums;

enum VendorModuleEnum: string
{
    case BRANCH = 'branch';
    case CATEGORY = 'category';
    case STOCK = 'stock';
    case VENDOR = 'vendor';
    case CLIENT = 'client';
    case INVESTOR = 'investor';
    case ORDER = 'order';
    case SETTING = 'setting';
    case ACTIVITY_LOG = 'activity_log';
    case PLANS = 'plans';
    case VENDOR_WALLET = 'vendor_wallets';
    case INVESTOR_WALLET = 'investor_wallets';
    case UNSURPASSED = 'unsurpassed';
    public function lang(): string
    {
        return $this->value;
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
}
