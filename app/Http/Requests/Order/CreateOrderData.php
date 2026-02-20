<?php

namespace App\Http\Requests\Order;

use App\Enums\Order\OrderStatus;
use App\Enums\Order\PayType;
use App\Enums\Order\VatType;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class CreateOrderData extends Data
{
    public function __construct(
        #[Required, Max(200)]
        public string $name,

        #[Max(255)]
        public ?string $clientName,

        #[Max(255)]
        public ?string $clientSurname,

        #[Email, Max(100)]
        public ?string $email,

        #[Max(255)]
        public ?string $companyName,

        #[Max(300)]
        public ?string $deliveryAddress,

        #[Numeric, Min(0)]
        public ?float $delivery,

        #[IntegerType, Min(0), Max(100)]
        public ?int $discount,

        #[Numeric, Min(0)]
        public ?float $weightGross,

        public ?OrderStatus $status,

        public ?PayType $payType,

        public ?VatType $vatType,

        #[BooleanType]
        public ?bool $addressEqual,

        #[BooleanType]
        public ?bool $acceptPay,

        #[Date]
        public ?string $createDate,
    ) {}
}