<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\GetOrdersStatsData;
use App\Http\Requests\Order\GetPriceRequestData;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Services\ScrapperService;
use Illuminate\Http\JsonResponse;

class OrderController
{
    public function getPrice(GetPriceRequestData $requestData, ScrapperService $scrapperService): JsonResponse
    {
        $price = $scrapperService->getPrice($requestData->factory, $requestData->collection, $requestData->article);

        return response()->json([
            'price' => $price,
            'factory' => $requestData->factory,
            'collection' => $requestData->collection,
            'article' => $requestData->article,
        ]);
    }

    public function getStats(GetOrdersStatsData $requestData, OrderRepository $orderRepository): JsonResponse
    {
        $query = $orderRepository->getGroupedByPeriodWithCount($requestData->getDateFormat());

        $groupsCount = (clone $query)->get()->count();
        $pagesCount = (int) ceil($groupsCount / $requestData->perPage);
        $orders = $query->with(['articles'])
            ->offset(($requestData->page - 1) * $requestData->perPage)
            ->limit($requestData->perPage)
            ->get();

        return response()->json([
            'meta' => [
                'page' => $requestData->page,
                'per_page' => $requestData->perPage,
                'total_pages' => $pagesCount,
                'total_groups' => $groupsCount,
            ],
            'data' => OrderResource::collection($orders),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $order = Order::with(['articles'])->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return new OrderResource($order);
    }
}
