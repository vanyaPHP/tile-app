<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class ScrapperService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getPrice(string $factory, string $collection, string $article): float
    {
        $url = "https://tile.expert/it/tile/{$factory}/{$collection}/a/{$article}";

        try {
            $response = $this->client->get($url, ['timeout' => 15]);
            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);

            $priceNode = $crawler->filter('.js-price-tag')->first();

            if ($priceNode->count()) {
                $rawPrice = $priceNode->attr('data-price-raw');

                return (float) $rawPrice;
            }

            Log::warning("Price not found via .js-price-tag for URL: $url");

            return 0.0;

        } catch (\Exception $e) {
            Log::error("Scraping error for $url: " . $e->getMessage());
            return 0.0;
        }
    }
}
