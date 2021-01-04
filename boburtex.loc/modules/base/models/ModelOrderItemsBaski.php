<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "{{%model_order_items_baski}}".
 *
 * @property int $id
 * @property int $model_orders_items_id
 * @property int $model_var_baski_id
 * @property string $add_info
 *
 * @property ModelOrdersItems $modelOrdersItems
 * @property ModelVarBaski $modelVarBaski
 */
class ModelOrderItemsBaski extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_order_items_baski}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_items_id', 'model_var_baski_id'], 'integer'],
            [['add_info'], 'string', 'max' => 255],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
            [['model_var_baski_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelVarBaski::className(), 'targetAttribute' => ['model_var_baski_id' => 'id']],
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
            'model_var_baski_id' => Yii::t('app', 'Model Var Baski ID'),
            'add_info' => Yii::t('app', 'Add Info'),
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
    public function getModelVarBaski()
    {
        return $this->hasOne(ModelVarBaski::className(), ['id' => 'model_var_baski_id']);
    }
}
