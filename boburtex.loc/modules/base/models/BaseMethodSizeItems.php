<?php

namespace app\modules\base\models;

use Yii;
use app\modules\base\models\Size;


/**
 * This is the model class for table "{{%base_method_size_items}}".
 *
 * @property int $id
 * @property int $base_method_id
 * @property int $size_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BaseMethod $baseMethod
 * @property Size $size
 * @property BaseMethodSizeItemsChilds[] $baseMethodSizeItemsChilds
 */
class BaseMethodSizeItems extends BaseModel
{
    public $description;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%base_method_size_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['size_id'], 'required'],
            [['base_method_id', 'size_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['base_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseMethod::className(), 'targetAttribute' => ['base_method_id' => 'id']],
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
            'base_method_id' => Yii::t('app', 'Base Method ID'),
            'size_id' => Yii::t('app', 'Size ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if($this->status != $this::STATUS_SAVED){
            $this->status = BaseModel::STATUS_ACTIVE;
        }
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseMethod()
    {
        return $this->hasOne(BaseMethod::className(), ['id' => 'base_method_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseMethodSizeItemsChilds()
    {
        return $this->hasMany(BaseMethodSizeItemsChilds::className(), ['base_method_size_items_id' => 'id']);
    }
}