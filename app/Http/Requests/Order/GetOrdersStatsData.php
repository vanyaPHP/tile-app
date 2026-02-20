<?php

namespace App\Http\Requests\Order;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class GetOrdersStatsData extends Data
{
    public function __construct(
        #[In(['day', 'month', 'year'])]
        public string $groupBy,

        #[Min(1)]
        public int $page = 1,

        #[Min(1), Max(100)]
        public int $perPage = 10,
    ) {}

    public function getDateFormat(): string
    {
        return match($this->groupBy) {
            'day' => 'Y-m-d',
            'month' => 'Y-m',
            'year' => 'Y'
        };
    }
}
