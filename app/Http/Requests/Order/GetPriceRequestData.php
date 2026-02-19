<?php

namespace App\Http\Requests\Order;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class GetPriceRequestData extends Data
{
    public function __construct(
        #[Required]
        public string $factory,
        #[Required]
        public string $collection,
        #[Required]
        public string $article,
    ) {}
}
