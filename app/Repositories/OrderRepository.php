<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function getGroupedByPeriodWithCount(string $dateFormat): Builder
    {
        return Order::query()
            ->select(DB::raw("DATE_FORMAT(create_date, '$dateFormat') as period"), DB::raw('COUNT(*) as count'))
            ->groupBy('period')
            ->orderBy('period', 'desc');
    }
}
