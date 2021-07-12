# Тестовое задание, интернет магазин на RestApi

## Подготовка проекта
```
php bin/console doctrine:fixtures:load
```

Имеются пользователи в системе для тестирования с ид.: 1, 2

## 1. Создание заказа
Запрос: http://127.0.0.1:<port>/api/order
Метод: POST
Данные:
```
{"user":1, "products":[{"id":1,"count":1},{"id":2,"count":2}]}
```
Ответ:
```json
{
  "message": "Temporary order is created: 1",
  "code": 200,
  "data": {
    "order": 1,
    "status": false,
    "total": 15,
    "products": [
      {
        "sku": "SKU-0001",
        "count": 1
      },
      {
        "sku": "SKU-0002",
        "count": 2
      }
    ]
  }
}
```

## 2. Обновление заказа
Запрос: http://127.0.0.1:<port>/api/order/{order_id}
Метод: PUT
Данные:
```
{"products":[{"id":1,"count":0},{"id":2,"count":1}]}
```

Ответ:
```json
{
    "message": "Temporary order is updated: 1",
    "code": 200,
    "data": {
        "order": 1,
        "status": false,
        "total": 15,
        "products": [
            {
              "sku": "SKU-0001",
              "count": 1
            },
            {
              "sku": "SKU-0002",
              "count": 2
            }
        ]
    }
}
```

#3 Оплата заказа
Запрос: http://127.0.0.1:<port>/api/order/{order_id}/purchase
Метод: POST
```json
{
    "message": "Order is purchased: 1",
    "code": 200,
    "data": {
        "order": 1,
        "status": false,
        "total": 15,
        "products": [
            {
              "sku": "SKU-0001",
              "count": 1
            },
            {
              "sku": "SKU-0002",
              "count": 2
            }
        ]
    }
}
```

#4 Получение заказа
Запрос: http://127.0.0.1:<port>/api/order/{order_id}
Метод: GET
```json
{
    "message": "Get order info: 2",
    "code": 200,
    "data": {
        "order": 2,
        "status": true,
        "total": 5,
        "products": [
            {
                "sku": "SKU-0001",
                "count": 1
            },
            {
                "sku": "SKU-0002",
                "count": 2
            }
        ]
    }
}
```