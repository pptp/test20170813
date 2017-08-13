<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
/*
function errHandle($errNo, $errStr, $errFile, $errLine) {
    $msg = "$errStr in $errFile on line $errLine";
    if ($errNo == E_NOTICE || $errNo == E_WARNING) {
        throw new ErrorException($msg, $errNo);
    } else {
        echo $msg;
    }
}
set_error_handler('errHandle');
*/

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);

(new yii\web\Application($config))->run();
