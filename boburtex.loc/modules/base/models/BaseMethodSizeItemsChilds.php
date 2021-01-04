<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "{{%base_method_size_items_childs}}".
 *
 * @property int $id
 * @property int $base_method_size_items_id
 * @property int $base_method_seam_id
 * @property double $time
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BaseMethodSeam $baseMethodSeam
 * @property BaseMethodSizeItems $baseMethodSizeItems
 */
class BaseMethodSizeItemsChilds extends BaseModel
{
    public $size;
    public $description;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%base_method_size_items_childs}}';
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->status = BaseModel::STATUS_ACTIVE;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['base_method_size_items_id', 'base_method_seam_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['time', 'base_method_seam_id'], 'required'],
            [['time'], 'number'],
            [['base_method_seam_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseMethodSeam::className(), 'targetAttribute' => ['base_method_seam_id' => 'id']],
            [['base_method_size_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseMethodSizeItems::className(), 'targetAttribute' => ['base_method_size_items_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'base_method_size_items_id' => Yii::t('app', 'Base Method Size Items ID'),
            'base_method_seam_id' => Yii::t('app', 'Base Method Seam ID'),
            'time' => Yii::t('app', 'Time'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseMethodSeam()
    {
        return $this->hasOne(BaseMethodSeam::className(), ['id' => 'base_method_seam_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseMethodSizeItems()
    {
        return $this->hasOne(BaseMethodSizeItems::className(), ['id' => 'base_method_size_items_id']);
    }
}