<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function getGroupedByPeriodWithCount(string $dateFormat): Collection
    {
        return Order::query()
            ->get()
            ->countBy(fn ($order) => Carbon::parse($order->create_date)->format($dateFormat))
            ->sortKeysDesc()
            ->map(function ($count, $period) {
                return [
                    'period' => $period,
                    'count' => $count,
                ];
            })
            ->values();
    }

    public function getByIds(array $ids, array $relations = []): Collection
    {
        return Order::query()
            ->with($relations)
            ->whereIn('id', $ids)
            ->get();
    }
}
