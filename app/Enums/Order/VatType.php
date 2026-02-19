<?php

namespace App\Enums\Order;

enum VatType: int
{
    case PRIVATE_INDIVIDUAL = 0;
    case VAT_PAYER = 1;
}
