<?php

namespace App\Http\Resources\Order;

use App\Models\OrderArticle;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin OrderArticle
 */
class OrderArticleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'article_id' => $this->article_id,
            'amount' => $this->amount,
            'price' => $this->price,
            'price_eur' => $this->price_eur,

            'currency' => $this->currency,
            'measure' => $this->measure,

            'delivery_time_min' => $this->delivery_time_min?->format('Y-m-d'),
            'delivery_time_max' => $this->delivery_time_max?->format('Y-m-d'),

            'weight' => $this->weight,
            'packaging' => $this->packaging,
            'pallet' => $this->pallet,

            'is_swimming_pool' => $this->swimming_pool,
        ];
    }
}
