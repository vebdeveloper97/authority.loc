<?php

namespace app\modules\admin;
use Yii;

/**
 * admin module definition class
 */
class admin extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::$app->errorHandler->errorAction = 'admin/default/error';

        // custom initialization code goes here
    }
}
