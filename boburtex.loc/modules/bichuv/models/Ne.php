<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "{{%ne}}".
 *
 * @property int $id
 * @property string $name
 * @property string $add_info
 * @property int $user_id
 * @property string $created_time
 */
class Ne extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ne}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'add_info', 'user_id'], 'required'],
            [['add_info'], 'string'],
            [['user_id'], 'integer'],
            [['created_time'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'add_info' => Yii::t('app', 'Add Info'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_time' => Yii::t('app', 'Created Time'),
        ];
    }
}
