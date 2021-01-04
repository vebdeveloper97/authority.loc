<?php


namespace app\modules\tikuv\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use app\components\PermissionHelper as P;

class BaseController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST', 'GET'],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (Yii::$app->authManager->getPermission(Yii::$app->controller->id . "/" . Yii::$app->controller->action->id)) {
            if (!P::can(Yii::$app->controller->id . "/" . Yii::$app->controller->action->id)) {
                throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
            }
        }

        return parent::beforeAction($action);
    }
}