<?php

namespace App\Models;

use App\Enums\OrderArticle\MultiplePalletType;
use Database\Factories\OrderArticleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $orders_id
 * @property int|null $article_id ID коллекции
 * @property float $amount количество артикулов в ед. измерения
 * @property float $price Цена на момент оплаты заказа
 * @property float|null $price_eur Цена в Евро по заказу
 * @property string|null $currency Валюта для которой установлена цена
 * @property string|null $measure Ед. изм. для которой установлена цена
 * @property Carbon|null $delivery_time_min
 * @property Carbon|null $delivery_time_max
 * @property float $weight вес упаковки
 * @property MultiplePalletType|null $multiple_pallet Кратность палете, 1 - кратно упаковке, 2 - кратно палете, 3 - не меньше палеты
 * @property float $packaging_count Количество кратно которому можно добавлять товар в заказ
 * @property float $pallet количество в палете на момент заказа
 * @property float $packaging количество в упаковке
 * @property bool $swimming_pool Плитка специально для бассейна
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Order|null $order
 */
class OrderArticle extends Model
{
    /** @use HasFactory<OrderArticleFactory> */
    use HasFactory;

    protected $table = 'orders_article';

    public $timestamps = false;

    protected $casts = [
        'amount' => 'decimal:3',
        'price' => 'decimal:2',
        'price_eur' => 'decimal:2',
        'weight' => 'decimal:3',
        'packaging_count' => 'decimal:3',
        'pallet' => 'decimal:3',
        'packaging' => 'decimal:3',

        'multiple_pallet' => MultiplePalletType::class,

        'swimming_pool' => 'boolean',

        'delivery_time_min' => 'datetime:Y-m-d',
        'delivery_time_max' => 'datetime:Y-m-d',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }
}
