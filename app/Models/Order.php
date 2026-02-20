<?php

namespace App\Models;

use App\Enums\Client\Sex;
use App\Enums\Order\DeliveryCalculateType;
use App\Enums\Order\DeliveryType;
use App\Enums\Order\OffsetReason;
use App\Enums\Order\PayType;
use App\Enums\Order\OrderStatus;
use App\Enums\Order\VatType;
use Database\Factories\OrderFactory;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $hash hash заказа
 * @property int|null $user_id
 * @property string $token уникальный хеш пользователя
 * @property string|null $number Номер заказа
 * @property OrderStatus $status Статус заказа
 * @property string|null $email контактный E-mail
 * @property VatType $vat_type Частное лицо или плательщик НДС
 * @property string|null $vat_number НДС-номер
 * @property string|null $tax_number Индивидуальный налоговый номер налогоплательщика
 * @property int|null $discount Процент скидки
 * @property float|null $delivery Стоимость доставки
 * @property DeliveryType|null $delivery_type Тип доставки: 0 - адрес клинта, 1 - адрес склада
 * @property Carbon|null $delivery_time_min Минимальный срок доставки
 * @property Carbon|null $delivery_time_max Максимальный срок доставки
 * @property Carbon|null $delivery_time_confirm_min Минимальный срок доставки подтверждённый производителем
 * @property Carbon|null $delivery_time_confirm_max Максимальный срок доставки подтверждённый производителем
 * @property Carbon|null $delivery_time_fast_pay_min Минимальный срок доставки
 * @property Carbon|null $delivery_time_fast_pay_max Максимальный срок доставки
 * @property Carbon|null $delivery_old_time_min Прошлый минимальный срок доставки
 * @property Carbon|null $delivery_old_time_max Прошлый максимальный срок доставки
 * @property string|null $delivery_index
 * @property int|null $delivery_country
 * @property string|null $delivery_region
 * @property string|null $delivery_city
 * @property string|null $delivery_address
 * @property string|null $delivery_building
 * @property string|null $delivery_phone_code
 * @property string|null $delivery_phone
 * @property Sex|null $sex Пол клиента
 * @property string|null $client_name Имя клиента
 * @property string|null $client_surname Фамилия клиента
 * @property string|null $company_name Название компании
 * @property PayType $pay_type Выбранный тип оплаты
 * @property Carbon|null $pay_date_execution Дата до которой действует текущая цена заказа
 * @property Carbon|null $offset_date Дата сдвига предполагаемого расчета доставки
 * @property OffsetReason|null $offset_reason тип причина сдвига сроков 1 - каникулы на фабрике, 2 - фабрика уточняет сроки пр-ва, 3 - другое
 * @property Carbon|null $proposed_date Предполагаемая дата поставки
 * @property Carbon|null $ship_date Предполагаемая дата отгрузки
 * @property string|null $tracking_number Номер треккинга
 * @property string|null $manager_name Имя менеджера сопровождающего заказ
 * @property string|null $manager_email Email менеджера сопровождающего заказ
 * @property string|null $manager_phone Телефон менеджера сопровождающего заказ
 * @property string|null $carrier_name Название транспортной компании
 * @property string|null $carrier_contact_data Контактные данные транспортной компании
 * @property string $locale локаль из которой был оформлен заказ
 * @property float|null $cur_rate курс на момент оплаты
 * @property string $currency валюта при которой был оформлен заказ
 * @property string $measure ед. изм. в которой был оформлен заказ
 * @property string $name Название заказа
 * @property string|null $description Дополнительная информация
 * @property Carbon $create_date Дата создания
 * @property Carbon|null $update_date Дата изменения
 * @property string|null $warehouse_data Данные склада: адрес, название, часы работы
 * @property bool $step если true то заказ не будет сброшен в следствии изменений
 * @property bool|null $address_equal Адреса плательщика и получателя совпадают (false - разные, true - одинаковые )
 * @property bool|null $bank_transfer_requested Запрашивался ли счет на банковский перевод
 * @property bool|null $accept_pay Если true то заказ отправлен в работу
 * @property Carbon|null $cancel_date Конечная дата согласования сроков поставки
 * @property float|null $weight_gross Общий вес брутто заказа
 * @property bool|null $product_review Оставлен отзыв по коллекциям в заказе
 * @property int|null $mirror Метка зеркала на котором создается заказ
 * @property bool|null $process метка массовой обработки
 * @property Carbon|null $fact_date Фактическая дата поставки
 * @property int|null $entrance_review Фиксирует вход клиента на страницу отзыва и последующие клики
 * @property bool $payment_euro Если true, то оплату посчитать в евро
 * @property bool|null $spec_price установлена спец цена по заказу
 * @property bool|null $show_msg Показывать спец. сообщение
 * @property float|null $delivery_price_euro Стоимость доставки в евро
 * @property int|null $address_payer
 * @property Carbon|null $sending_date Расчетная дата поставки
 * @property DeliveryCalculateType $delivery_calculate_type Тип расчета: 0 - ручной, 1 - автоматический
 * @property Carbon|null $full_payment_date Дата полной оплаты заказа
 * @property string|null $bank_details Реквизиты банка для возврата средств
 * @property string|null $delivery_apartment_office Квартира/офис
 *
 * @property-read Collection<int, OrderArticle> $articles
 */
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id';

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'update_date';

    protected $casts = [
        'status' => OrderStatus::class,
        'discount' => 'integer',
        'vat_type' => VatType::class,
        'delivery_type' => DeliveryType::class,
        'sex' => Sex::class,
        'pay_type' => PayType::class,
        'offset_reason' => OffsetReason::class,
        'mirror' => 'integer',
        'entrance_review' => 'integer',
        'delivery_calculate_type' => DeliveryCalculateType::class,

        'step' => 'boolean',
        'product_review' => 'boolean',
        'address_equal' => 'boolean',
        'bank_transfer_requested' => 'boolean',
        'accept_pay' => 'boolean',
        'process' => 'boolean',
        'payment_euro' => 'boolean',
        'spec_price' => 'boolean',
        'show_msg' => 'boolean',

        'create_date' => 'datetime',
        'update_date' => 'datetime',
        'delivery_time_min' => 'datetime:Y-m-d',
        'delivery_time_max' => 'datetime:Y-m-d',
        'delivery_time_confirm_min' => 'datetime:Y-m-d',
        'delivery_time_confirm_max' => 'datetime:Y-m-d',
        'delivery_time_fast_pay_min' => 'datetime:Y-m-d',
        'delivery_time_fast_pay_max' => 'datetime:Y-m-d',
        'delivery_old_time_min' => 'datetime:Y-m-d',
        'delivery_old_time_max' => 'datetime:Y-m-d',
        'pay_date_execution' => 'datetime',
        'offset_date' => 'datetime',
        'proposed_date' => 'datetime',
        'ship_date' => 'datetime',
        'cancel_date' => 'datetime',
        'fact_date' => 'datetime',
        'sending_date' => 'datetime',
        'full_payment_date' => 'datetime',

        'delivery' => 'decimal:2',
        'cur_rate' => 'decimal:6',
        'weight_gross' => 'decimal:3',
        'delivery_price_euro' => 'decimal:2',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(OrderArticle::class, 'orders_id');
    }
}
