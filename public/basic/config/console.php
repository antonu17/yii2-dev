<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
Yii::setAlias('@common', dirname(__DIR__) . '/common');

//$params = require(__DIR__ . '/params.php');
//$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],

            ],
        ],
        //'db' => $db,
        'sms' => [
            'class' => 'common\components\Sms',
            'host' => 'gate.iqsms.ru',
            'login' => 'z1453783839357',
            'password' => '840549',
            'sender' => 'inform',
        ],
        'user' => 'app\models\User',
    ],
    //'params' => $params,
];
