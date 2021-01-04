<?php


namespace app\controllers;
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

    public function checkAccess($action, $model=null, $params=[])
    {
        return true;
    }

    public function behaviors()
    {
        return [
          'contentNegotiator' => [
              'class' => ContentNegotiator::class,
              'formatParam' => '_format',
              'formats' => [
                  'application/json' => Response::FORMAT_JSON,
                  'xml' => Response::FORMAT_XML
              ]
          ]
        ];
    }
}