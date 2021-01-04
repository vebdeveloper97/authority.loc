<?php

use yii\web\User;
use yii\web\Request;
use yii\web\Response;
use app\bootstrap\Logger;
use app\modules\api\Module;
use yii\web\JsonResponseFormatter;
use yii\web\JsonParser;

$params = array_replace_recursive(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php',
);

return [
    'id'         => 'belt-master-app',
    'basePath'   => dirname(__DIR__),
    'aliases'    => [
        '@app' => dirname(__DIR__),
    ],
    'bootstrap'  => [
        'logger' => [
            'class' => Logger::class,
        ],
    ],
    'components' => [
        'request'    => [
            'class'   => Request::class,
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
        ],
        'response'   => [
            'class'      => Response::class,
            'format'     => Response::FORMAT_JSON,
            'charset'    => 'UTF-8',
            'formatters' => [
                Response::FORMAT_JSON => [
                    'class'         => JsonResponseFormatter::class,
                    'prettyPrint'   => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => false,
            'showScriptName'      => false,
            'rules'               => [
                'GET api/ping' => 'api/base/ping',
            ],
        ],
        'user'       => [
            'identityClass'   => User::class,
            'enableAutoLogin' => false,
            'enableSession'   => false,
        ],
    ],
    'modules'    => [
        'api' => Module::class,
    ],
    'params'     => $params,
];
