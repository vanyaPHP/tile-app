<?php

namespace App\Services;

use App\Http\Requests\Order\CreateOrderData;
use App\Models\Order;

class OrderService
{
    public function createOrder(CreateOrderData $validatedData): Order
    {
        $orderData = [
            'hash' => md5(uniqid()),
            'name' => $validatedData->name,
            'client_name' => $validatedData->clientName,
            'client_surname' => $validatedData->clientSurname,
            'email' => $validatedData->email,
            'company_name' => $validatedData->companyName,
            'delivery_address' => $validatedData->deliveryAddress,
            'delivery' => $validatedData->delivery ?: 0.00,
            'discount' => $validatedData->discount ?: 0.00,
            'weight_gross' => $validatedData->weightGross ?: null,
            'status' => $validatedData->status?->value,
            'pay_type' => $validatedData->payType?->value,
            'vat_type' => $validatedData->vatType?->value,
            'address_equal' => $validatedData->addressEqual ?? true,
            'accept_pay' => $validatedData->acceptPay ?? false,
            'create_date' => $validatedData->createDate ?? now(),
            'currency' => 'EUR',
        ];

        return Order::query()->create($orderData);
    }
}