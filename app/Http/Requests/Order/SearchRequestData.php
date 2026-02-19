<?php

namespace App\Http\Requests\Order;

use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class SearchRequestData extends Data
{
    public function __construct(
        #[Required]
        public string $search,
    ) {}
}
