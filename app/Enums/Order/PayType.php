<?php

namespace App\Enums\Order;

enum PayType: int
{
    case CASH = 1;
    case CASHLESS = 2;
}
