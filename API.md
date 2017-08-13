Регистраиця кошелька
====================

/v1/wallets


POST /v1/wallets
{
    "customerName": "Mikhail2",
    "countryName": "Russia",
    "cityName": "Tomsk",
    "currencyAlias": "btc"
}

currencyAlias - 3 символа

Ответ

{
    "id": 5,
    "balance": 0,
    "customerName": "Mikhail2",
    "currencyAlias": "btc",
    "cityName": "Tomsk",
    "countryName": "Russia",
    "customer": {
        "id": 8,
        "name": "Mikhail2",
        "cityId": 6
    },
    "currency": {
        "id": 3,
        "name": null,
        "alias": "btc",
        "rate": null
    }
}


Зачисление денежных средств
===========================

/v1/wallets/recharge

POST {
    "walletId": 1,
    "sum": 1
}

сумма в валюте кошелька

Ответ

{
    "senderWalletId": null,
    "receiverWalletId": 1,
    "currency": 1,
    "sum": 1
}


Перевод
=======

/v1/wallets/transfer

POST

{
    "receiverWalletId": 2,
    "senderWalletId": 1,
    "sum": 1,
    "inSenderCurrency": true
}

Ответ

{
    "senderWalletId": 1,
    "receiverWalletId": 2,
    "currency": 2,
    "sum": 1
}


загрузка котировки валюты к USD на дату
=======================================

/v1/rates/{date}

где date - дата в формате YYYY-MM-DD

PATCH [
    {
        "currencyAlias": "eur",
        "rate": 2.2
    },
    {
        "currencyAlias": "rub",
        "rate": 0.55
    }
]

ответ

boolean