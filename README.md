Tile Expert API
Документация к REST/SOAP API на базе Laravel для управления заказами, парсинга цен и статистики.

Приложение будет доступно по адресу: http://localhost:8080
Эндпоинты API

1. Получение цены (Парсинг)
Получает актуальную цену плитки с сайта tile.expert.

Endpoint: GET /api/orders/price

Параметры (Query):

"factory": string, обязательный -	Название фабрики (например, marca-corona)

"collection": string, обязательный, Название коллекции (например, arteseta)

"article": string, обязательный, Артикул (например, k263-arteseta-camoscio-s000628660)

Пример запроса:

curl "http://localhost:8080/api/price?factory=marca-corona&collection=arteseta&article=k263-arteseta-camoscio-s000628660"

Пример ответа (200 OK):

json

{
  "price": 59.99,
  "factory": "marca-corona",
  "collection": "arteseta",
  "article": "k263-arteseta-camoscio-s000628660"
}

2. Статистика заказов
Возвращает количество заказов, сгруппированных по дате (день/месяц/год), с поддержкой пагинации.

Endpoint: GET /api/orders/stats

Параметры (Query):

group_by: string, обязательный, тип группировки: day, month, year

page: integer, необязательный, номер страницы (по умолчанию 1)

per_page: integer, необязательный, количество элементов на странице (по умолчанию 10, max 100)

Пример запроса:

bash

curl "http://localhost:8080/api/orders/stats?group_by=month&page=1&per_page=5"

Пример ответа (200 OK):

json

{
  "meta": {
    "page": 1,
    "per_page": 5,
    "total_pages": 3,
    "total_groups": 12
  },
  "data": [
    {
      "period": "2023-03",
      "count": 15
    },
    {
      "period": "2023-02",
      "count": 8
    }
  ]
}

3. Поиск заказов
Полнотекстовый поиск по заказам (использует Manticore Search).

Endpoint: GET /api/orders/search

Параметры (Query):

search:	string, обязательный, поисковый запрос (имя клиента, email, компания)

Пример запроса:

bash

curl "http://localhost:8080/api/search?q=Ivan"

Пример ответа (200 OK):

json

{
  "query": "Ivan",
  "count": 1,
  "results": [
    {
      "id": 1,
      "client_name": "Ivan",
      "email": "ivan@example.com",
      "status": 1
    }
  ]
}

4. Получение заказа
Получение детальной информации о конкретном заказе вместе с артикулами.

Endpoint: GET /api/orders/{id}

Параметры (URL):

id: integer, ID заказа

Пример запроса:

bash

curl "http://localhost:8080/api/orders/1"

Пример ответа (200 OK):


json

{
  "data": {
    "id": 1,
    "hash": "...",
    "name": "Order Name",
    "status": 1,
    "client": {
      "name": "Ivan",
      "surname": "Petrov",
      "email": "ivan@example.com"
    },
    "delivery": {
      "city": "Rome",
      "cost": 50.00
    },
    "articles": [
      {
        "id": 10,
        "amount": 10.500,
        "price": 25.99
      }
    ]
  }
}

5. Создание заказа (SOAP)
Создание заказа через SOAP запрос в режиме non-WSDL.

Endpoint: POST /api/soap

Headers:

Content-Type: text/xml; charset=UTF-8
Тело запроса (XML):
Важно: Пространство имен xmlns="http://localhost/api/soap" должно совпадать с настройками сервера.

xml

<?xml version="1.0" encoding="UTF-8"?>
<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
    <Body>
        <createOrder xmlns="http://localhost/api/soap">
            <data>
                <name>SOAP Test Order</name>
                <client_name>Ivan</client_name>
                <client_surname>Petrov</client_surname>
                <email>ivan.petrov@example.com</email>
                <city>Kyiv</city>
                <delivery>150.50</delivery>
                <status>1</status>
            </data>
        </createOrder>
    </Body>
</Envelope>

Пример ответа (200 OK):

xml

<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
  <SOAP-ENV:Body>
    <ns1:createOrderResponse>
      <return>
        <status>success</status>
        <order_id>123</order_id>
        <message>Order created via SOAP</message>
      </return>
    </ns1:createOrderResponse>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>

Ошибки валидации (SOAP Fault):
Если данные не прошли валидацию (например, неверный Email), вернется SOAP-ENV:Fault.

Тестирование

Запуск полного набора тестов:

docker-compose exec app php artisan test

База данных

Проект использует оптимизированную схему БД:

Используются типы DECIMAL для денежных значений.
Статусы и типы данных хранятся как TINYINT UNSIGNED (0-255) для экономии места.
Настроены Foreign Keys для целостности данных.
Поддерживается полнотекстовый поиск.
Дамп находится в файле dump.sql.