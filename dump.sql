CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hash VARCHAR(32) NOT NULL COMMENT 'hash заказа',
    user_id INT UNSIGNED NULL,
    token VARCHAR(64) NOT NULL COMMENT 'уникальный хеш пользователя',
    number VARCHAR(10) NULL COMMENT 'Номер заказа',
    
    -- Использовать тип данных tinyint для статусов (0-255)
    status TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Статус заказа',
    vat_type TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Частное лицо или плательщик НДС',
    delivery_type TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Тип доставки: 0 - адрес клиента, 1 - адрес склада',
    sex TINYINT UNSIGNED NULL COMMENT 'Пол клиента',
    pay_type TINYINT UNSIGNED NOT NULL COMMENT 'Выбранный тип оплаты',
    offset_reason TINYINT UNSIGNED NULL COMMENT 'тип причина сдвига сроков 1 - каникулы на фабрике, 2 - фабрика уточняет сроки пр-ва, 3 - другое',
    delivery_calculate_type TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Тип расчета: 0 - ручной, 1 - автоматический',
    
    email VARCHAR(100) NULL COMMENT 'контактный E-mail',
    client_name VARCHAR(255) NULL COMMENT 'Имя клиента',
    client_surname VARCHAR(255) NULL COMMENT 'Фамилия клиента',
    company_name VARCHAR(255) NULL COMMENT 'Название компании',
    
    -- Использование DECIMAL для точности вычислений финансов
    discount TINYINT UNSIGNED NULL COMMENT 'Процент скидки',
    delivery DECIMAL(10, 2) NULL COMMENT 'Стоимость доставки',
    delivery_price_euro DECIMAL(10, 2) NULL COMMENT 'Стоимость доставки в евро',
    cur_rate DECIMAL(10, 6) NULL COMMENT 'курс на момент оплаты',
    vat_number VARCHAR(100) NULL COMMENT 'НДС-номер',
    tax_number VARCHAR(50) NULL COMMENT 'Индивидуальный налоговый номер налогоплательщика',
    full_payment_date DATE NULL COMMENT 'Дата полной оплаты заказа',
    bank_details LONGTEXT NULL COMMENT 'Реквизиты банка для возврата средств',
    
    delivery_time_min DATE NULL COMMENT 'Минимальный срок доставки',
    delivery_time_max DATE NULL COMMENT 'Максимальный срок доставки',
    delivery_time_confirm_min DATE NULL COMMENT 'Минимальный срок доставки подтверждённый производителем',
    delivery_time_confirm_max DATE NULL COMMENT 'Максимальный срок доставки подтверждённый производителем',
    delivery_time_fast_pay_min DATE NULL COMMENT 'Минимальный срок доставки',
    delivery_time_fast_pay_max DATE NULL COMMENT 'Максимальный срок доставки',
    delivery_old_time_min DATE NULL COMMENT 'Прошлый минимальный срок доставки',
    delivery_old_time_max DATE NULL COMMENT 'Прошлый максимальный срок доставки',
    
    delivery_index VARCHAR(20) NULL,
    delivery_country INT UNSIGNED NULL,
    delivery_region VARCHAR(50) NULL,
    delivery_city VARCHAR(200) NULL,
    delivery_address VARCHAR(300) NULL,
    delivery_building VARCHAR(200) NULL,
    delivery_apartment_office VARCHAR(30) NULL,
    delivery_phone_code VARCHAR(20) NULL,
    delivery_phone VARCHAR(20) NULL,
    
    tracking_number VARCHAR(50) NULL COMMENT 'Номер треккинга',
    carrier_name VARCHAR(50) NULL COMMENT 'Название транспортной компании',
    carrier_contact_data VARCHAR(255) NULL COMMENT 'Контактные данные транспортной компании',
    weight_gross DECIMAL(10, 3) NULL COMMENT 'Общий вес брутто заказа',
    
    manager_name VARCHAR(20) NULL COMMENT 'Имя менеджера сопровождающего заказ',
    manager_email VARCHAR(30) NULL COMMENT 'Email менеджера сопровождающего заказ',
    manager_phone VARCHAR(20) NULL COMMENT 'Телефон менеджера сопровождающего заказ',
    
    locale VARCHAR(5) NOT NULL COMMENT 'локаль из которой был оформлен заказ',
    currency VARCHAR(3) NOT NULL DEFAULT 'EUR' COMMENT 'валюта при которой был оформлен заказ',
    measure VARCHAR(3) NOT NULL DEFAULT 'm' COMMENT 'ед. изм. в которой был оформлен заказ',
    name VARCHAR(200) NOT NULL COMMENT 'Название заказа',
    description TEXT NULL COMMENT 'Дополнительная информация',
    warehouse_data LONGTEXT NULL COMMENT 'Данные склада: адрес, название, часы работы',
    
    create_date DATETIME NOT NULL COMMENT 'Дата создания',
    update_date DATETIME NULL COMMENT 'Дата изменения',
    pay_date_execution DATETIME NULL COMMENT 'Дата до которой действует текущая цена заказа',
    offset_date DATETIME NULL COMMENT 'Дата сдвига предполагаемого расчета доставки',
    proposed_date DATETIME NULL COMMENT 'Предполагаемая дата поставки',
    ship_date DATETIME NULL COMMENT 'Предполагаемая дата отгрузки',
    cancel_date DATETIME NULL COMMENT 'Конечная дата согласования сроков поставки',
    sending_date DATETIME NULL COMMENT 'Расчетная дата поставки',
    fact_date DATETIME NULL COMMENT 'Фактическая дата поставки',
    
    -- Флаги с доп данными о заказах (TINYINT(1)
    step TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'если true то заказ не будет сброшен в следствии изменений',
    address_equal TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Адреса плательщика и получателя совпадают (false - разные, true - одинаковые )',
    bank_transfer_requested TINYINT(1) NULL COMMENT 'Запрашивался ли счет на банковский перевод',
    accept_pay TINYINT(1) NULL COMMENT 'Если true то заказ отправлен в работу',
    product_review TINYINT(1) NULL COMMENT 'Оставлен отзыв по коллекциям в заказе',
    mirror TINYINT UNSIGNED NULL COMMENT 'Метка зеркала на котором создается заказ',
    process TINYINT(1) NULL COMMENT 'метка массовой обработки',
    entrance_review TINYINT UNSIGNED NULL COMMENT 'Фиксирует вход клиента на страницу отзыва и последующие клики',
    payment_euro TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Если true, то оплату посчитать в евро',
    spec_price TINYINT(1) NULL COMMENT 'установлена спец цена по заказу',
    show_msg TINYINT(1) NULL COMMENT 'Показывать спец. сообщение',
    
    address_payer INT UNSIGNED NULL,

    -- Индексы
    INDEX idx_orders_delivery_country (delivery_country),
    INDEX idx_orders_user_id (user_id),
    INDEX idx_orders_create_date_status (create_date, status),
    INDEX idx_orders_hash (hash)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Хранит информацию о заказах';

CREATE TABLE orders_article (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    orders_id INT UNSIGNED NULL,
    article_id INT UNSIGNED NULL COMMENT 'ID коллекции',
    
    -- Использование DECIMAL для точности вычислений
    amount DECIMAL(10, 3) NOT NULL COMMENT 'количество артикулов в ед. измерения',
    price DECIMAL(10, 2) NOT NULL COMMENT 'Цена на момент оплаты заказа',
    price_eur DECIMAL(10, 2) NULL COMMENT 'Цена в Евро по заказу',
    weight DECIMAL(10, 3) NOT NULL COMMENT 'вес упаковки',
    packaging_count DECIMAL(10, 3) NOT NULL COMMENT 'Количество кратно которому можно добавлять товар в заказ',
    pallet DECIMAL(10, 3) NOT NULL COMMENT 'количество в палете на момент заказа',
    packaging DECIMAL(10, 3) NOT NULL COMMENT 'количество в упаковке',
    
    currency VARCHAR(3) NULL COMMENT 'Валюта для которой установлена цена',
    measure VARCHAR(2) NULL COMMENT 'Ед. изм. для которой установлена цена',
    
    delivery_time_min DATE NULL COMMENT 'Минимальный срок доставки',
    delivery_time_max DATE NULL COMMENT 'Максимальный срок доставки',
    
    multiple_pallet TINYINT UNSIGNED NULL COMMENT 'Кратность палете, 1 - кратно упаковке, 2 - кратно палете, 3 - не меньше палеты',
    swimming_pool TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Плитка специально для бассейна',
    
    -- Индексы
    INDEX idx_orders_article_article_id (article_id),
    INDEX idx_orders_article_orders_id (orders_id),
    
    -- Внешний ключ
    CONSTRAINT fk_orders_article_orders 
        FOREIGN KEY (orders_id) 
        REFERENCES orders(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Хранит информацию об артикулах заказа';
