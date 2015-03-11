<?php

$basePath = dirname(dirname(__DIR__));
return [
    'id' => 'test-app',
    'basePath' => $basePath,
    'vendorPath' => $basePath . '/vendor',
    'components' => [
        'mongodb' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://test-user:test-pass@localhost:27017/test_db'
        ]
    ]
];