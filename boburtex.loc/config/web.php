<?php

use kartik\tree\Module;
use yii\helpers\Url;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$container = require __DIR__ . '/container.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'uz',
    'layout' => 'wbm',
    'timeZone' => 'Asia/Tashkent',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@api'   =>  dirname(dirname(__DIR__)) . '/api',
    ],
    'container' => $container,
    'modules' => [
        'mobile' => [
            'class' => 'app\modules\mobile\Module',
            'layout' => '@app/modules/mobile/views/layouts/main',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'except' => ['default/login'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    return Yii::$app->response->redirect(['/mobile/default/login']);
                },
            ],
        ],
        'hr' => [
            'class' => 'app\modules\hr\Module',
        ],
        'rbac' => [
            'class' => 'yii2mod\rbac\Module',
        ],
        'eav' => [
            'class' => 'mirocow\eav\Module',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
             'i18n' => [
                 'class' => 'yii\i18n\PhpMessageSource',
                 'basePath' => '@app/messages',
                 'forceTranslation' => true
             ]
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module'
        ],
        'settings' => [
            'class' => 'app\modules\settings\settings',
            'layout' => '@app/views/layouts/wbm',
            'defaultRoute' => 'currency/index',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['settings'],

                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    return Yii::$app->response->redirect(['/site/access-denied']);
                },
            ],
        ],
        'boyoq' => [
            'class' => 'app\modules\boyoq\Boyoq',
            'layout' => '@app/views/layouts/wbm',
            'defaultRoute' => 'toquv-directory/index',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['boyoq'],
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    return Yii::$app->response->redirect(['/site/access-denied']);
                },
            ],
        ],
        'toquv' => [
            'class' => 'app\modules\toquv\Toquv',
            'layout' => '@app/views/layouts/wbm',
            'defaultRoute' => 'toquv-directory/index',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['toquv'],
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    return Yii::$app->response->redirect(['/site/access-denied']);
                },
            ],
        ],
        'admin' => [
            'class' => 'app\modules\admin\admin',
            'layout' => '@app/views/layouts/wbm',
            'defaultRoute' => 'auth-item/index',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    return Yii::$app->response->redirect(['/site/access-denied']);
                },
            ],
        ],
        'bichuv' => [
            'class' => 'app\modules\bichuv\Bichuv',
            'layout' => '@app/views/layouts/wbm',
            'defaultRoute' => 'bichuv-acs/index',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['bichuv'],
                    ],
                    [
                        'actions' => ['preview'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    return Yii::$app->response->redirect(['/site/access-denied']);
                },
            ],
        ],
        'base' => [
            'class' => 'app\modules\base\BaseModule',
            'layout' => '@app/views/layouts/wbm',
            'defaultRoute' => 'models-list/index',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['base', 'modelxona'],
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    return Yii::$app->response->redirect(['/site/access-denied']);
                },
            ],
        ],
        'wms' => [
            'class' => 'app\modules\wms\Wms',
            'layout' => '@app/views/layouts/wbm',
            'defaultRoute' => 'models-list/index',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['wms', 'tmo'],
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    return Yii::$app->response->redirect(['/site/access-denied']);
                },
            ],
        ],
        'tikuv' => [
            'class' => 'app\modules\tikuv\Tikuv',
        ],
        'usluga' => [
            'class' => 'app\modules\usluga\Usluga',
        ],
        'mechanical' => [
            'class' => 'app\modules\mechanical\Mechanical',
        ],
        'treemanager' =>  [
            'class' => '\kartik\tree\Module',
        ]
    ],
    'components' => [
        'authManager' => [
            // Run migration
            // php yii migrate/up --migrationPath=@yii/rbac/migrations

            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => [],
        ],
        'assetManager' => [
//            'bundles' => [
//                // we will use bootstrap css from our theme
//                'yii\bootstrap\BootstrapAsset' => [
//                    'css' => [], // do not use yii default one
//                ],
//            ],
            'appendTimestamp' => true,
//             'linkAssets' => true,
            'bundles' => [
                'kartik\form\ActiveFormAsset' => [
                    'bsDependencyEnabled' => true // do not load bootstrap assets for a specific asset bundle
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'd.m.Y',
            'datetimeFormat' => 'd.m.Y H:i:s',
            'timeFormat' => 'H:i:s',
            'nullDisplay' => '',
        ],
        'i18n' => [
            'translations' => [
                'yii2mod.rbac' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/rbac/messages',
                ],
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'uz-UZ',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/auth' => 'auth.php'
                    ],
                ],
                'eav' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@mirocow/eav/messages',
                ],
            ],
        ],
        'request' => [
            'baseUrl' => '',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '-DQnANUPCgfoarP0H63fB55G5hC-94-G',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['save'],
                    'logFile' => '@runtime/logs/save.log',
                    'logVars' => []
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'class' => 'app\widgets\MultiLang\components\UrlManager',
            'languages' => ['uz','ru','tr'],
            'enableLanguageDetection' => false,
            'enableDefaultLanguageUrlCode' => true,
            'rules' => [

                'wms/wms-document/<slug:\w+>/<action>' => 'wms/wms-document/<action>',
                'mechanical/spare-inspection/<slug:\w+>/<action>' => 'mechanical/spare-inspection/<action>',
                'wms/tmo/<slug:\w+>/<action>' => 'wms/tmo/<action>',
                'base/variation-acs-material/<slug:\w+>/<action>' => 'base/variation-acs-material/<action>',

                'tikuv/doc/<slug:\w+>/<action>' => 'tikuv/doc/<action>',
                'bichuv/doc/<slug:\w+>/<action>' => 'bichuv/doc/<action>',
                'mobile/tikuv/<slug:\w+>/<action>' => 'mobile/tikuv/<action>',

                'bichuv/bichuv-nastel-details/<slug:[A-Za-z0-9-_.]+>/<type:[A-Za-z0-9-_.]+>/<table:[A-Za-z0-9-_.]+>/<id:\d+>/<action>' => 'bichuv/bichuv-nastel-details/<action>',
                'bichuv/bichuv-nastel-details/<slug:[A-Za-z0-9-_.]+>/<type:[A-Za-z0-9-_.]+>/<table:[A-Za-z0-9-_.]+>/<action>' => 'bichuv/bichuv-nastel-details/<action>',
                'bichuv/bichuv-nastel-details/<slug:[A-Za-z0-9-_.]+>/<type:[A-Za-z0-9-_.]+>/<action>' => 'bichuv/bichuv-nastel-details/<action>',
                'bichuv/spare-item-doc/<slug:[A-Za-z0-9-_.]+>/<action>' => 'bichuv/spare-item-doc/<action>',
                'bichuv/tayyorlov/<slug:[A-Za-z0-9-_.]+>/<action>' => 'bichuv/tayyorlov/<action>',

                'toquv/toquv-documents/<slug:\w+>/<action>' => 'toquv/toquv-documents/<action>',
                'toquv/toquv-documents/<slug:\w+>/view/<id>' => 'toquv/toquv-documents/view',
                'toquv/toquv-pricing-doc/<slug:\w+>/<action>' => 'toquv/toquv-pricing-doc/<action>',
                'toquv/toquv-pricing-doc/<slug:\w+>/view/<id>' => 'toquv/toquv-pricing-doc/view',
                'toquv/roll-info/<slug:\w+>/<action>' => 'toquv/roll-info/<action>',
                'toquv/toquv-documents/<slug:\w+>/<action>/<id>' => 'toquv/toquv-documents/<action>',

                'usluga/usluga-doc/<slug:\w+>/<action>' => 'usluga/usluga-doc/<action>',
                'usluga/usluga-doc/<slug:\w+>/<action>/<id>' => 'usluga/usluga-doc/<action>',

                'base/wh-document/<slug:[A-Za-z0-9-_.]+>/<action>' => 'base/wh-document/<action>',
                'base/spa/<action>/<slug:\w+>' => 'base/spa/<action>',
                'hr/hr-services/<slug:[A-Za-z0-9-_.]+>/<action>' => 'hr/hr-services/<action>',

                '<module:\w+>/<controller:\w+>/<action:(\w|-)+>' => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:(\w|-)+>/<id:\d+>' => '<module>/<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'app\components\CustomGii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
