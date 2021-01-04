<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "size_col_rel_size".
 *
 * @property int $id
 * @property int $sc_id
 * @property int $size_id
 * @property int $type
 * @property int $created_by
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property SizeCollections $sc
 * @property Size $size
 */
class SizeColRelSize extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'size_col_rel_size';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sc_id', 'size_id', 'type', 'created_by', 'status', 'created_at', 'updated_at'], 'integer'],
            [['sc_id'], 'exist', 'skipOnError' => true, 'targetClass' => SizeCollections::className(), 'targetAttribute' => ['sc_id' => 'id']],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sc_id' => Yii::t('app', 'Sc ID'),
            'size_id' => Yii::t('app', 'Size ID'),
            'type' => Yii::t('app', 'Type'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSc()
    {
        return $this->hasOne(SizeCollections::className(), ['id' => 'sc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }
}
