<?php

namespace App\Enums\Order;

enum OrderStatus: int
{
    case NEW = 1;
    case PROCESSING = 2;
}
