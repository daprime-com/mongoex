<?php

$basePath = dirname(dirname(__DIR__));
return [
    'id' => 'test-app',
    'basePath' => $basePath,
    'vendorPath' => $basePath . '/vendor',
    'components' => [
        'mongodb' => [
            'class' => 'mongoex\Connection',
            'dsn' => 'mongodb://test-user:test-pass@localhost:27017/test_db'
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['*'],
                    'logFile' => $basePath . '/tests/logs/mongodb.log'
                ],
            ],
        ]
    ]
];