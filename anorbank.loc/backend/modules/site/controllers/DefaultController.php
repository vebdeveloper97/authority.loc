<?php

namespace backend\modules\site\controllers;

use Yii;
use yii\web\Controller;
use yii\base\Exception;

class DefaultController extends Controller
{
    public function actionError(): string
    {
        $this->layout = 'error';
        $exception = Yii::$app->errorHandler->exception;

        if ($exception !== null) {
            return $this->render('error', [
                'exception' => $exception,
            ]);
        }

        return $this->renderContent('null');
    }
}
