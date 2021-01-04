<?php

namespace app\modules\toquv;
use Yii;
/**
 * toquv module definition class
 */
class Toquv extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\toquv\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::$app->errorHandler->errorAction = 'toquv/default/error';

        // custom initialization code goes here
    }
}
