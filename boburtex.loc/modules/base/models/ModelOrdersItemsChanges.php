<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "model_orders_items_changes".
 *
 * @property int $id
 * @property int $model_orders_items_id
 * @property string $add_info
 * @property int $type
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ModelOrdersItems $modelOrdersItems
 * @property ModelOrdersItemsSize[] $modelOrdersItemsSizes
 * @property ModelOrdersPlanning[] $modelOrdersPlannings
 */
class ModelOrdersItemsChanges extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_orders_items_changes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_items_id', 'type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['add_info'], 'string'],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_orders_items_id' => Yii::t('app', 'Model Orders Items ID'),
            'add_info' => Yii::t('app', 'Add Info'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersItems()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'model_orders_items_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersItemsSizes()
    {
        return $this->hasMany(ModelOrdersItemsSize::className(), ['model_orders_items_changes_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersPlannings()
    {
        return $this->hasMany(ModelOrdersPlanning::className(), ['model_orders_items_changes_id' => 'id']);
    }
}
