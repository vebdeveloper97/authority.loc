<?php


namespace app\modules\api\controllers;


use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use Yii;
use yii\rest\Controller;
use yii\rest\Serializer;
use yii\web\Response;

class BaseApiController extends Controller
{
    /**
     * @var Serializer
     * */
    public $serializer = [
        'class'              => Serializer::class,
        'collectionEnvelope' => 'items'
    ];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        Yii::$app->user->enableSession = false;
        parent::init();
    }

    /**
     * @return array
     * @var Behavior
     *
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class'       => ContentNegotiator::class,
            'formatParam' => 'format',
            'formats'     => [
                'application/json' => Response::FORMAT_JSON,
                'xml'              => Response::FORMAT_XML,
            ]
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    /* Controller qaytarish uchun  */
    public static function getController()
    {
        return Yii::$app->controller->id;
    }

    /* Action qaytarish uchun */
    public static function getAction()
    {
        return Yii::$app->controller->action->id;
    }
}