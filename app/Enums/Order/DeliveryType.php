<?php

namespace App\Enums\Order;

enum DeliveryType: int
{
    case CLIENT = 0;
    case WAREHOUSE = 1;
}
