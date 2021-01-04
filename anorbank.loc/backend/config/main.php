<?php

use backend\components\Formatter;
use common\modules\user\models\User;
use backend\modules\site\Module as SiteModule;
use backend\modules\admin\Module as AdminModule;

$params = array_replace_recursive(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'         => 'belt-master-app',
    'basePath'   => dirname(__DIR__),
    'layout'     => 'main',
    'layoutPath' => '@backend/modules/adminlte/layouts',
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/default/error',
        ],
        'formatter'    => [
            'class' => Formatter::class,
        ],
        'request'      => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'l<HPove[FL[D+8pq%;nr=lb(I#6pO&%v',
            'baseUrl'             => '',
        ],
        'urlManager'   => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => false,
            'showScriptName'      => false,
            'rules'               => [
                'GET api/ping' => 'api/base/ping',
            ],
        ],
        'user'         => [
            'identityClass'   => User::class,
            'enableAutoLogin' => false,
            'enableSession'   => false,
        ],
    ],
    'modules'    => [
        'admin' => AdminModule::class,
        'site'  => SiteModule::class,
    ],
    'params'     => $params,
];
