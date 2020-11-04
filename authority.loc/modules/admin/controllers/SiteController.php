<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Response;

class SiteController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
