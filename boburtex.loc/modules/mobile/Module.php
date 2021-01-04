<?php

namespace app\modules\mobile;

use Yii;
use yii\web\ErrorHandler;
use yii\web\ForbiddenHttpException;

/**
 * mobile module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\mobile\controllers';

    protected $exceptedControllers = [ // permissionga tekshirilmaydigan kontrollerlar
        'default'
    ];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Is better used regex method, temporally i used this method
        $r = Yii::$app->urlManager->parseRequest(Yii::$app->request)[0];
        $r_array = explode('/',$r);

        if($r_array[0] === 'mobile') {
            Yii::configure($this, [
                'components' => [
                    'errorHandler' => [
                        'class' => ErrorHandler::class,
                        'errorAction' => '/mobile/default/error',
                    ]
                ],
            ]);

            $handler = $this->get('errorHandler');
            Yii::$app->set('errorHandler', $handler);
            $handler->register();
        }

    }

    /**
     * @param $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $slug = Yii::$app->request->get('slug');
        $flag = false;
        if (in_array($action->controller->id, $this->exceptedControllers)) {
            return parent::beforeAction($action);
        }

        if (
            Yii::$app->authManager->getPermission($action->controller->module->id . "/" . $action->controller->id . "/" . $action->id)
            || Yii::$app->authManager->getPermission($action->controller->module->id . "/" . $action->controller->id . "/" . $slug . "/" . $action->id)
            || Yii::$app->authManager->getPermission($action->controller->id . "/" . $action->id)
            || Yii::$app->authManager->getPermission($action->controller->id . "/" . $slug . "/" . $action->id)
        ) {
            if (
                Yii::$app->user->can($action->controller->module->id . "/" . $action->controller->id . "/" . $action->id)
                || Yii::$app->user->can($action->controller->module->id . "/" . $action->controller->id . "/" . $slug . "/" . $action->id)
                || Yii::$app->user->can($action->controller->id . "/" . $action->id)
                || Yii::$app->user->can($action->controller->id . "/" . $slug . "/" . $action->id)
            ) {
                $flag = true;
            }
        }

        if (!$flag) {
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
        }

        return parent::beforeAction($action);
    }
}
