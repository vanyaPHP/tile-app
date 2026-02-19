<?php

namespace App\Enums\Order;

enum OffsetReason: int
{
    case HOLIDAY = 1;
    case CLARIFICATION_PROCESS = 2;
    case OTHER = 3;
}
