<?php

namespace Tests\Feature\Controllers;

use App\Models\Order;
use App\Models\OrderArticle;
use App\Services\ScrapperService;
use App\Services\SearchService;
use Mockery;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    public function testGetPriceEndpointUsesScraperService(): void
    {
        $mockService = Mockery::mock(ScrapperService::class);
        $mockService->shouldReceive('getPrice')
            ->once()
            ->with('marca-corona', 'arteseta', 'k263')
            ->andReturn(59.99);

        $this->app->instance(ScrapperService::class, $mockService);

        $response = $this->get(route('api.orders.price', [
            'factory' => 'marca-corona',
            'collection' => 'arteseta',
            'article' => 'k263',
        ]));

        $response->assertStatus(200)
            ->assertJson([
                'price' => 59.99,
                'factory' => 'marca-corona',
                'collection' => 'arteseta',
                'article' => 'k263'
            ]);
    }

    public function testStatsGroupingByMonthReturnsCorrectStructure(): void
    {
        Order::factory()->count(2)->lastMonth()->create();
        Order::factory()->count(1)->create(['create_date' => now()]);

        $response = $this->get(route('api.orders.stats', [
            'group_by' => 'month',
            'page' => 1,
            'per_page' => 10,
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'meta' => ['page', 'per_page', 'total_pages', 'total_groups'],
                'data' => ['*' => ['period', 'count']]
            ]);

        $data = $response->json('data');
        $this->assertGreaterThanOrEqual(1, count($data));
    }

    public function testStatsPaginationWorks(): void
    {
        Order::factory()->count(15)->create();

        $response = $this->get('/api/orders/stats?group_by=month&per_page=5');

        $response->assertStatus(200)
            ->assertJsonPath('meta.per_page', 5)
            ->assertJsonCount(5, 'data');
    }

    public function testGetSingleOrderIncludesArticlesAndUsesResource(): void
    {
        /** @var Order $order */
        $order = Order::factory()->create();
        OrderArticle::factory()->count(3)->create(['orders_id' => $order->id]);

        $response = $this->get(route('api.orders.show', ['id' => $order->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id', 'hash', 'client', 'delivery', 'payment', 'articles'
                ]
            ]);

        $this->assertCount(3, $response->json('data.articles'));
    }

    public function testSearchEndpointIsAccessible(): void
    {
        $mockService = Mockery::mock(SearchService::class);

        $mockService->shouldReceive('searchOrders')
            ->once()
            ->with('test')
            ->andReturn([
                'hits' => [
                    ['id' => 1, 'client_name' => 'Mocked Order']
                ],
                'total' => 1
            ]);

        $this->app->instance(SearchService::class, $mockService);

        $response = $this->get(route('api.orders.search', ['search' => 'test']));
        $response->assertStatus(200);
    }
}