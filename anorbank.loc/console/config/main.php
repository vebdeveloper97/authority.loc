<?php

use yii\queue\db\Queue;
use yii\mutex\PgsqlMutex;
use yii\console\controllers\MigrateController;
use console\modules\elma\Module as ElmaModule;

$params = array_replace_recursive(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/../config/params.php',
    require __DIR__ . '/../config/params-local.php'
);

return [
    'id'            => 'master-template-console',
    'basePath'      => dirname(__DIR__),
    'controllerNamespace' => 'console\commands',
    'bootstrap'     => [
        'queue' => 'queue',
    ],
    'controllerMap' => [
        'migrate' => [
            'class'               => MigrateController::class,
            'migrationPath'       => [
                'common/migrations',
                'common/modules/rabbitmq/migrations',
            ],
            'migrationNamespaces' => [
                'yii\queue\db\migrations',
            ],
        ],
    ],
    'components'    => [
        'queue' => [
            'class'     => Queue::class,
            'db'        => 'db', // DB connection component or its config
            'tableName' => '{{%queue}}', // Table name
            'channel'   => 'default', // Queue channel key
            'mutex'     => PgsqlMutex::class, // Mutex used to sync queries
        ],
    ],
    'modules'       => [
        'elma' => ElmaModule::class,
    ],
    'params'        => $params,
];
