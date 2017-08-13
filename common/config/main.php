<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\MemCache',
            'useMemcached' => true,
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
        ],
        'db' => require(__DIR__ . '/db.php')
    ],
];
