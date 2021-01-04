<?php

namespace common\components;

use Yii;
use tune\nibbd\Nibbd as MasterNibbd;

class Nibbd
{
    public static function build(): MasterNibbd
    {
        return new MasterNibbd(Yii::$app->params['nibbd']['config']);
    }
}
