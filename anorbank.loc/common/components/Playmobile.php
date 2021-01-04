<?php

namespace common\components;

use Yii;
use tune\playmobile\Playmobile as MasterPlaymobile;

class Playmobile
{
    public static function build(): MasterPlaymobile
    {
        return new MasterPlaymobile(Yii::$app->params['playmobile']['config']);
    }
}
