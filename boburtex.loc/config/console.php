<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
        '@wmsm' => '@app/modules/wms/migrations',
        '@bichuvm' => '@app/modules/bichuv/migrations',
        '@hrm' => '@app/modules/hr/migrations',
        '@basem' => '@app/modules/base/migrations',
        '@tikuvm' => '@app/modules/tikuv/migrations',
        '@mobilem' => '@app/modules/mobile/migrations',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            // Run migration
            // php yii migrate/up --migrationPath=@yii/rbac/migrations

            'class' => 'yii\rbac\DbManager',
            //'defaultRoles' => ['guest', 'user'],
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
