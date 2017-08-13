<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/wallets'
    ],

    [
        'pattern' => 'v1/wallets/recharge',
        'verb' => 'POST',
        'route' => 'v1/wallets/recharge'
    ],
    [
        'pattern' => 'v1/wallets/transfer',
        'verb' => 'POST',
        'route' => 'v1/wallets/transfer'
    ],
    [
        'pattern' => 'v1/rates/<date:\d{4}\-\d{2}\-\d{2}>',
        'verb' => 'PATCH',
        'route' => 'v1/rates/updatebydate'
    ],

];