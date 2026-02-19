<?php

namespace App\Enums\OrderArticle;

enum MultiplePalletType: int
{
    case MULTIPLE_PACKAGE = 1;
    case MULTIPLE_PALLET = 2;
    case NOT_LESS_THAN_PALLET = 3;
}
