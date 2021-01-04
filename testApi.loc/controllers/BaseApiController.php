<?php


namespace app\controllers;

use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\rest\Serializer;
use yii\web\Response;

class BaseApiController extends ActiveController
{
    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items'
    ];

    public function checkAccess($action, $model=null, $params = [])
    {
        return true;
    }

    public function behaviors()
    {
        return [
            'contentNegotiotor' => [
                'class' => ContentNegotiator::class,
                'formatParam' => '_format',
                'formats' => [
                    'application/xml' => Response::FORMAT_XML,
                    'application/json' => Response::FORMAT_JSON
                ]
            ]
        ];
    }
}