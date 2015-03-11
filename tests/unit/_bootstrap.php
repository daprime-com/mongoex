<?php

// ensure we get report on all possible php errors
error_reporting(-1);

define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_DEBUG', true);

$vendor = __DIR__ . '/../../vendor';
require_once($vendor . '/autoload.php');
require_once($vendor . '/yiisoft/yii2/Yii.php');
require_once(__DIR__ . '/TestCase.php');

Yii::setAlias('@tests', dirname(__DIR__));