<?php

use yii\redis\Cache;
use yii\log\FileTarget;
use yii\redis\Connection;
use common\components\Ebp;
use common\components\Nibbd;
use common\components\Asoki;
use common\components\Soliq;
use common\components\Misoki;
use common\components\Playmobile;
use common\components\Billmaster;

$db = array_replace_recursive(
    require __DIR__ . '/../../common/config/db.php',
    require __DIR__ . '/../../common/config/db-local.php'
);

return [
    'timeZone'   => 'UTC',
    'language'   => 'ru-RU',
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'bootstrap'  => ['log' => 'log'],
    'components' => [
        'db'         => $db['master'],
        'cache'      => [
            'class' => Cache::class,
            'redis' => 'redis',
        ],
        'log'        => [
            'targets' => [
                'file' => [
                    'class'   => FileTarget::class,
                    'levels'  => ['error', 'warning'],
                    'logVars' => ['_GET', '_POST', '_SERVER'],
                    'except'  => [
                        'yii\web\HttpException:400',
                        'yii\web\HttpException:401',
                        'yii\web\HttpException:403',
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:426',
                    ],
                    'logFile' => '@runtime/logs/' . date('Y/m/d') . '/error.log',
                ],
            ],
        ],
        'redis'      => [
            'class'    => Connection::class,
            'hostname' => 'localhost',
            'port'     => 6379,
            'database' => 0,
        ],
        'nibbd'      => [Nibbd::class, 'build'],
        'playmobile' => [Playmobile::class, 'build'],
        'billmaster' => [Billmaster::class, 'build'],
        'asoki'      => Asoki::class,
        'misoki'     => Misoki::class,
        'soliq'      => [Soliq::class, 'build'],
        'ebp'        => [Ebp::class, 'build'],
    ],
];
