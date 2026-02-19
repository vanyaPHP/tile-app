<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 32)->unique()->comment('hash заказа');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('token', 64)->comment('уникальный хеш пользователя');
            $table->string('number', 10)->nullable()->comment('Номер заказа');
            $table->unsignedTinyInteger('status')->default(1)->comment('Статус заказа');

            $table->string('email', 100)->nullable()->comment('контактный E-mail');
            $table->unsignedTinyInteger('vat_type')->default(0)->comment('Частное лицо или плательщик НДС');
            $table->string('vat_number', 100)->nullable()->comment('НДС-номер');
            $table->string('tax_number', 50)->nullable()->comment('Индивидуальный налоговый номер налогоплательщика');
            $table->unsignedTinyInteger('discount')->nullable()->comment('Процент скидки');

            $table->decimal('delivery', 10, 2)->nullable()->comment('Стоимость доставки');
            $table->unsignedTinyInteger('delivery_type')->default(0)->nullable()->comment('Тип доставки: 0 - адрес клинта, 1 - адрес склада');
            $table->date('delivery_time_min')->nullable()->comment('Минимальный срок доставки');
            $table->date('delivery_time_max')->nullable()->comment('Максимальный срок доставки');
            $table->date('delivery_time_confirm_min')->nullable()->comment('Минимальный срок доставки подтверждённый производителем');
            $table->date('delivery_time_confirm_max')->nullable()->comment('Максимальный срок доставки подтверждённый производителем');
            $table->date('delivery_time_fast_pay_min')->nullable()->comment('Минимальный срок доставки');
            $table->date('delivery_time_fast_pay_max')->nullable()->comment('Максимальный срок доставки');
            $table->date('delivery_old_time_min')->nullable()->comment('Прошлый минимальный срок доставки');
            $table->date('delivery_old_time_max')->nullable()->comment('Прошлый максимальный срок доставки');

            $table->string('delivery_index', 20)->nullable();
            $table->unsignedInteger('delivery_country')->nullable();
            $table->string('delivery_region', 50)->nullable();
            $table->string('delivery_city', 200)->nullable();
            $table->string('delivery_address', 300)->nullable();
            $table->string('delivery_building', 200)->nullable();
            $table->string('delivery_phone_code', 20)->nullable();
            $table->string('delivery_phone', 20)->nullable();

            $table->unsignedTinyInteger('sex')->nullable()->comment('Пол клиента');
            $table->string('client_name', 255)->nullable()->comment('Имя клиента');
            $table->string('client_surname', 255)->nullable()->comment('Фамилия клиента');
            $table->string('company_name', 255)->nullable()->comment('Название компании');

            $table->unsignedTinyInteger('pay_type')->comment('Выбранный тип оплаты');
            $table->dateTime('pay_date_execution')->nullable()->comment('Дата до которой действует текущая цена заказа');
            $table->dateTime('offset_date')->nullable()->comment('Дата сдвига предполагаемого расчета доставки');
            $table->unsignedTinyInteger('offset_reason')->nullable()->comment('тип причина сдвига сроков 1 - каникулы на фабрике, 2 - фабрика уточняет сроки пр-ва, 3 - другое');
            $table->dateTime('proposed_date')->nullable()->comment('Предполагаемая дата поставки');
            $table->dateTime('ship_date')->nullable()->comment('Предполагаемая дата отгрузки');

            $table->string('tracking_number', 50)->nullable()->comment('Номер треккинга');
            $table->string('manager_name', 20)->nullable()->comment('Имя менеджера сопровождающего заказ');
            $table->string('manager_email', 30)->nullable()->comment('Email менеджера сопровождающего заказ');
            $table->string('manager_phone', 20)->nullable()->comment('Телефон менеджера сопровождающего заказ');
            $table->string('carrier_name', 50)->nullable()->comment('Название транспортной компании');
            $table->string('carrier_contact_data', 255)->nullable()->comment('Контактные данные транспортной компании');

            $table->string('locale', 5)->comment('локаль из которой был оформлен заказ');
            $table->decimal('cur_rate', 10, 6)->default(1)->nullable()->comment('курс на момент оплаты');
            $table->string('currency', 3)->default('EUR')->comment('валюта при которой был оформлен заказ');
            $table->string('measure', 3)->default('m')->comment('ед. изм. в которой был оформлен заказ');
            $table->string('name', 200)->comment('Название заказа');
            $table->text('description')->nullable()->comment('Дополнительная информация');

            $table->dateTime('create_date')->comment('Дата создания')->index();
            $table->dateTime('update_date')->nullable()->comment('Дата изменения');
            $table->longText('warehouse_data')->nullable()->comment('Данные склада: адрес, название, часы работы');

            $table->boolean('step')->default(1)->comment('если true то заказ не будет сброшен в следствии изменений');
            $table->boolean('address_equal')->default(1)->nullable()->comment('Адреса плательщика и получателя совпадают (false - разные, true - одинаковые )');
            $table->boolean('bank_transfer_requested')->nullable()->comment('Запрашивался ли счет на банковский перевод');
            $table->boolean('accept_pay')->nullable()->comment('Если true то заказ отправлен в работу');

            $table->dateTime('cancel_date')->nullable()->comment('Конечная дата согласования сроков поставки');
            $table->decimal('weight_gross', 10, 3)->nullable()->comment('Общий вес брутто заказа');

            $table->boolean('product_review')->nullable()->comment('Оставлен отзыв по коллекциям в заказе');
            $table->unsignedTinyInteger('mirror')->nullable()->comment('Метка зеркала на котором создается заказ');
            $table->boolean('process')->nullable()->comment('метка массовой обработки');
            $table->dateTime('fact_date')->nullable()->comment('Фактическая дата поставки');
            $table->unsignedTinyInteger('entrance_review')->nullable()->comment('Фиксирует вход клиента на страницу отзыва и последующие клики');
            $table->boolean('payment_euro')->default(0)->comment('Если true, то оплату посчитать в евро');
            $table->boolean('spec_price')->nullable()->comment('установлена спец цена по заказу');
            $table->boolean('show_msg')->nullable()->comment('Показывать спец. сообщение');
            $table->decimal('delivery_price_euro', 10, 2)->nullable()->comment('Стоимость доставки в евро');
            $table->unsignedInteger('address_payer')->nullable();
            $table->dateTime('sending_date')->nullable()->comment('Расчетная дата поставки');
            $table->unsignedTinyInteger('delivery_calculate_type')->default(0)->comment('Тип расчета: 0 - ручной, 1 - автоматический');
            $table->date('full_payment_date')->nullable()->comment('Дата полной оплаты заказа');
            $table->longText('bank_details')->nullable()->comment('Реквизиты банка для возврата средств');
            $table->string('delivery_apartment_office', 30)->nullable()->comment('Квартира/офис');

            $table->fullText(['client_name', 'client_surname', 'email', 'company_name']);
        });

        Schema::create('orders_article', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orders_id')->nullable();
            $table->unsignedBigInteger('article_id')->nullable()->comment('ID коллекции');

            $table->decimal('amount', 10, 3)->comment('количество артикулов в ед. измерения');
            $table->decimal('price', 10, 2)->comment('Цена на момент оплаты заказа');
            $table->decimal('price_eur', 10, 2)->nullable()->comment('Цена в Евро по заказу');
            $table->string('currency', 3)->nullable()->comment('Валюта для которой установлена цена');
            $table->string('measure', 2)->nullable()->comment('Ед. изм. для которой установлена цена');
            $table->date('delivery_time_min')->nullable();
            $table->date('delivery_time_max')->nullable();
            $table->decimal('weight', 10, 3)->comment('вес упаковки');

            $table->unsignedTinyInteger('multiple_pallet')->nullable()->comment('Кратность палете, 1 - кратно упаковке, 2 - кратно палете, 3 - не меньше палеты');

            $table->decimal('packaging_count', 10, 3)->comment('Количество кратно которому можно добавлять товар в заказ');
            $table->decimal('pallet', 10, 3)->comment('количество в палете на момент заказа');
            $table->decimal('packaging', 10, 3)->comment('количество в упаковке');

            $table->boolean('swimming_pool')->default(0)->comment('Плитка специально для бассейна');

            $table->index('article_id');
            $table->index('orders_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders_article');
        Schema::dropIfExists('orders');
    }
};
