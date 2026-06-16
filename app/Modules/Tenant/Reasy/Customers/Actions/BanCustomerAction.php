<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Customers\Actions;

use App\Modules\Tenant\Reasy\Customers\Models\Customer;

class BanCustomerAction
{
    public function execute(string $customerId): Customer
    {
        $customer = Customer::findOrFail($customerId);
        $customer->update(['status' => 'banned']);
        return $customer;
    }
}
