<?php
// bu yerda settings admin uchun dostup
// bu yerda simple oddiy agent uchun dostup
$db = require(__DIR__ . '/../../config/db.php');
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'name' => 'RESTFull',
    'basePath' => dirname(__DIR__) . '/..',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@app/runtime/logs/api.log',
                ],
            ],
        ],
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/product','v1/good','v1/transfer','v1/wms'],
                    'extraPatterns' => [

                        'POST pack' => 'pack',
                        'GET pack' => 'pack',
                        'OPTIONS pack' => 'pack',

                        'POST list' => 'list',
                        'GET list' => 'list',
                        'OPTIONS list' => 'list',

                        'POST accepted' => 'accepted',
                        'GET accepted' => 'accepted',
                        'OPTIONS accepted' => 'accepted',

                        'POST total-pack' => 'total-pack',
                        'GET total-pack' => 'total-pack',
                        'OPTIONS total-pack' => 'total-pack',

                        'POST search' => 'search',
                        'GET search' => 'search',
                        'OPTIONS search' => 'search',

                        'POST filters' => 'filters',
                        'GET filters' => 'filters',
                        'OPTIONS filters' => 'filters',

                        'POST color' => 'color',
                        'GET color' => 'color',
                        'OPTIONS color' => 'color',

                        'POST add' => 'add',
                        'GET add' => 'add',
                        'OPTIONS add' => 'add',

                        'POST goods-item' => 'goods-item',
                        'GET goods-item' => 'goods-item',
                        'OPTIONS goods-item' => 'goods-item',

                        'POST save-barcode' => 'save-barcode',
                        'GET save-barcode' => 'save-barcode',
                        'OPTIONS save-barcode' => 'save-barcode',

                        'POST wrapper-item' => 'wrapper-item',
                        'GET wrapper-item' => 'wrapper-item',
                        'OPTIONS wrapper-item' => 'wrapper-item',

                        'POST list-docs' => 'list-docs',
                        'GET list-docs' => 'list-docs',
                        'OPTIONS list-docs' => 'list-docs',

                        'POST nastel-remain' => 'nastel-remain',
                        'GET nastel-remain' => 'nastel-remain',
                        'OPTIONS nastel-remain' => 'nastel-remain',

                        'POST create-doc'    => 'create-doc',
                        'GET create-doc'     => 'create-doc',
                        'OPTIONS create-doc' => 'create-doc',

                        'POST combined-nastel'    => 'combined-nastel',
                        'GET combined-nastel'     => 'combined-nastel',
                        'OPTIONS combined-nastel' => 'combined-nastel',

                        'POST returned'    => 'returned',
                        'GET returned'     => 'returned',
                        'OPTIONS returned' => 'returned',

                        'GET brands' => 'brands',
                        'POST brands' => 'brands',
                        'OPTIONS brands' => 'brands',

                        'GET get-goods-via-size' => 'get-goods-via-size',
                        'POST get-goods-via-size' => 'get-goods-via-size',
                        'OPTIONS get-goods-via-size' => 'get-goods-via-size',

                        'GET save-new-barcode' => 'save-new-barcode',
                        'POST save-new-barcode' => 'save-new-barcode',
                        'OPTIONS save-new-barcode' => 'save-new-barcode',

                        'POST save-properties' => 'save-properties',
                        'OPTIONS save-properties' => 'save-properties',

                        'POST save-some-data' => 'save-some-data',
                        'OPTIONS save-some-data' => 'save-some-data',

                        'GET search-nastel' => 'search-nastel',
                        'OPTIONS search-nastel' => 'search-nastel',

                        //newreactjs
                        'GET rm-remain-list' => 'rm-remain-list',
                        'OPTIONS rm-remain-list' => 'rm-remain-list',

                        'GET dept-list' => 'dept-list',
                        'OPTIONS dept-list' => 'dept-list',

                        'POST save-rm-request' => 'save-rm-request',
                        'OPTIONS save-rm-request' => 'save-rm-request',

                        'GET process-list' => 'process-list',
                        'OPTIONS  process-list' => 'process-list',

                        'GET nastel-list' => 'nastel-list',
                        'OPTIONS  nastel-list' => 'nastel-list',

                        'POST nastel-children' => 'nastel-children',
                        'OPTIONS  nastel-children' => 'nastel-children',

                        'POST children-cards' => 'children-cards',
                        'OPTIONS  children-cards' => 'children-cards',

                        'POST combine-details' => 'combine-details',
                        'OPTIONS  combine-details' => 'combine-details',

                        'GET ready-work-list' => 'ready-work-list',
                        'OPTIONS  ready-work-list' => 'ready-work-list',

                        'GET selected-ready-list' => 'selected-ready-list',
                        'POST selected-ready-list' => 'selected-ready-list',
                        'OPTIONS  selected-ready-list' => 'selected-ready-list',

                        'GET wms-actions' => 'wms-actions',
                        'POST wms-actions' => 'wms-actions',
                        'OPTIONS  wms-actions' => 'wms-actions',

                    ],
                ],
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'db' => $db,
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\api\modules\v1\Module'
        ],
    ],
    'params' => $params,
];

return $config;