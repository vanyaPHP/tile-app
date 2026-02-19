<?php

namespace App\Enums\Order;

enum DeliveryCalculateType: int
{
    case MANUAL = 0;
    case AUTO = 1;
}
