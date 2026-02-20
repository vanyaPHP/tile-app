<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\GetOrdersStatsData;
use App\Http\Requests\Order\GetPriceRequestData;
use App\Http\Requests\Order\SearchRequestData;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Services\ScrapperService;
use App\Services\SearchService;
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
        $data = $orderRepository->getGroupedByPeriodWithCount($requestData->getDateFormat());

        $groupsCount = $data->count();
        $pagesCount = (int) ceil($groupsCount / $requestData->perPage);
        $orders = $data->skip(($requestData->page - 1) * $requestData->perPage)
            ->take($requestData->perPage);

        return response()->json([
            'meta' => [
                'page' => $requestData->page,
                'per_page' => $requestData->perPage,
                'total_pages' => $pagesCount,
                'total_groups' => $groupsCount,
            ],
            'data' => $orders,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $order = Order::with(['articles'])->findOrFail($id);

        return response()->json(['data' => new OrderResource($order)]);
    }

    public function search(SearchRequestData $requestData, SearchService $searchService): JsonResponse
    {
        $result = $searchService->searchOrders($requestData->search);

        if (array_key_exists('error', $result)) {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }
}
