<?php

namespace app\modules\bichuv;

use Yii;

/**
 * bichuv module definition class
 */
class Bichuv extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\bichuv\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::$app->errorHandler->errorAction = 'bichuv/default/error';
        // custom initialization code goes here
    }
}
