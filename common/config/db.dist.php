<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=databasename',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
    'tablePrefix' => 'tb_',

    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
