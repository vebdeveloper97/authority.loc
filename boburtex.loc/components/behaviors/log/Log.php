<?php

/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 05.05.20 12:35
 */

namespace app\components\behaviors\log;

use Yii;
use yii\helpers\Json;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%change_log}}".
 *
 * @property integer $id
 * @property string $old_attributes
 * @property string $new_attributes
 * @property integer $user_id
 * @property string $event
 * @property string $object
 * @property string $user_name [varchar(255)]
 * @property string $user_login [varchar(255)]
 * @property string $table [varchar(255)]
 * @property string $date [datetime]
 */
class Log extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%change_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['old_attributes', 'new_attributes'], 'string'],
            [['user_id'], 'integer'],
            [['date'], 'safe'],
            [['event', 'object', 'user_name', 'user_login', 'table'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'old_attributes' => 'Old Attributes',
            'new_attributes' => 'New Attributes',
            'user_id' => 'User ID',
            'event' => 'Event',
            'object' => 'Object',
            'user_name' => 'User Name',
            'user_login' => 'User Login',
            'table' => 'Table'
        ];
    }

    static function saveLog(array $oldAttributes, array $newAttributes, $event, $object, $uid = false, $uname = false, $ulogin = false, $table = false) {
        $model = new self;
        $sender = $event->sender;
        if (isset($sender->logIgnoredAttributes) && is_array($sender->logIgnoredAttributes) && count($sender->logIgnoredAttributes) > 0) {
            foreach ($sender->logIgnoredAttributes as $attr) {
                unset($oldAttributes[$attr]);
                unset($newAttributes[$attr]);
            }

        }
        $diff = array_diff($newAttributes,$oldAttributes);
        if(!empty($diff)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $saved = false;
                $model->old_attributes = Json::encode($oldAttributes);
                $model->new_attributes = Json::encode($diff);
                $model->event = $event->name;
                $model->object = $object;
                $model->user_id = $uid;
                $model->user_name = $uname;
                $model->user_login = $ulogin;
                $model->table = $table;
                $model->date = new \yii\db\Expression('NOW()');
                if($model->save()) {
                    $saved = true;
                }
                if($saved){
                    $transaction->commit();
                }else{
                    $transaction->rollBack();
                }
            }catch (\Exception $e){
                $transaction->rollBack();
            }
        }
    }
}