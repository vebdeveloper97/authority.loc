<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 05.05.20 12:32
 */
namespace app\components\behaviors\log;
use Yii;
use yii\base\Behavior;
use \yii\db\ActiveRecord;

class LogBehavior extends Behavior {

    public function events()
    {
        return [
            /*ActiveRecord::EVENT_AFTER_INSERT => 'handleLog',*/
            ActiveRecord::EVENT_BEFORE_UPDATE => 'handleLog',
            ActiveRecord::EVENT_AFTER_DELETE => 'handleLog',
        ];
    }

    public function handleLog($event) {
        /** @var  $model ActiveRecord*/
        $model = $event->sender;

        Log::saveLog(
            $model->oldAttributes,
            $model->attributes,
            $event,
            $model::className(),
            Yii::$app->user->id ?? null,
            Yii::$app->user->identity->user_fio ?? null,
            Yii::$app->user->identity->username ?? null,
            $model::tableName()
        );
    }
}
