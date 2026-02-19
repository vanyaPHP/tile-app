<?php

namespace App\Services;

use App\Http\Resources\Order\OrderResource;
use App\Repositories\OrderRepository;
use GuzzleHttp\Client;

class SearchService
{
    protected Client $client;

    public function __construct(private readonly OrderRepository $orderRepository)
    {
        $this->client = new Client([
            'host' => config('manticore.host'),
            'port' => config('manticore.port'),
        ]);
    }

    public function searchOrders(string $query): array
    {
        if (empty(trim($query))) {
            return [
                'query' => $query,
                'count' => 0,
                'items' => [],
            ];
        }

        try {
            $results = $this->client->search([
                'index' => 'orders_idx',
                'query' => $query,
                'limit' => 100
            ]);

            return [
                'query' => $query,
                'count' => count($results['hits'] ?? []),
                'items' => OrderResource::collection($this->orderRepository->getByIds($results['hits'] ?? [], ['articles'])),
            ];
        } catch (\RuntimeException $e) {
            \Log::error("Manticore Search Error: " . $e->getMessage());

            return [
                'error' => 'Search service unavailable',
            ];
        }
    }
}
