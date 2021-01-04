<?php

namespace app\modules\api\common\controllers;

use Yii;
use yii\web\Response;
use yii\rest\Controller;

class BaseController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => Response::FORMAT_JSON,
        ];

        return $behaviors;
    }

    public function actionPing()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        return 'pong';
    }
}
