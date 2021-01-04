<?php

namespace app\modules\bichuv\models;

use app\modules\boyoq\models\Color;
use Yii;

/**
 * This is the model class for table "bichuv_mato_info".
 *
 * @property int $id
 * @property int $rm_id
 * @property int $ne_id
 * @property int $thread_id
 * @property int $pus_fine_id
 * @property int $color_id
 * @property string $en
 * @property string $gramaj
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property RawMaterial $mato
 * @property Ne $ne
 * @property Thread $thread
 * @property Color $color
 */
class BichuvMatoInfo extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_mato_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rm_id', 'ne_id', 'thread_id', 'pus_fine_id', 'color_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['en', 'gramaj'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'rm_id' => Yii::t('app', 'Rm ID'),
            'ne_id' => Yii::t('app', 'Ne ID'),
            'thread_id' => Yii::t('app', 'Thread ID'),
            'pus_fine_id' => Yii::t('app', 'Pus Fine ID'),
            'color_id' => Yii::t('app', 'Color ID'),
            'en' => Yii::t('app', 'En'),
            'gramaj' => Yii::t('app', 'Gramaj'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    public function getMato()
    {
        return $this->hasOne(RawMaterial::className(), ['id' => 'rm_id']);
    }
    public function getNe()
    {
        return $this->hasOne(Ne::className(), ['id' => 'ne_id']);
    }
    public function getThread()
    {
        return $this->hasOne(Thread::className(), ['id' => 'thread_id']);
    }
    public function getColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'color_id']);
    }
}
