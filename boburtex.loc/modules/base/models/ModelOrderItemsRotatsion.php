<?php

namespace app\modules\base\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%model_order_items_rotatsion}}".
 *
 * @property int $id
 * @property int $model_orders_items_id
 * @property int $model_var_rotatsion_id
 * @property string $add_info
 *
 * @property ModelOrdersItems $modelOrdersItems
 * @property ModelVarRotatsion $modelVarRotatsion
 */
class ModelOrderItemsRotatsion extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_order_items_rotatsion}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_items_id', 'model_var_rotatsion_id'], 'integer'],
            [['add_info'], 'string', 'max' => 255],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
            [['model_var_rotatsion_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelVarRotatsion::className(), 'targetAttribute' => ['model_var_rotatsion_id' => 'id']],
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
            'model_var_rotatsion_id' => Yii::t('app', 'Model Var Rotatsion ID'),
            'add_info' => Yii::t('app', 'Add Info'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItems()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'model_orders_items_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelVarRotatsion()
    {
        return $this->hasOne(ModelVarRotatsion::className(), ['id' => 'model_var_rotatsion_id']);
    }
}
