<?php

namespace app\modules\base\models;

use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\wms\models\WmsMatoInfo;
use Yii;

/**
 * This is the model class for table "{{%model_orders_items_toquv_acs}}".
 *
 * @property int $id
 * @property int $model_orders_id
 * @property int $model_orders_items_id
 * @property int $toquv_raw_materials_id
 * @property string $quantity
 * @property int $count
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ModelOrdersItems $modelOrdersItems
 * @property ToquvRawMaterials $toquvRawMaterials
 * @property WmsMatoInfo $wmsMatoInfo
 */
class ModelOrdersItemsToquvAcs extends BaseModel
{
    public $wms_color_id;
    public $wms_desen_id;
    public $pus_fine_id;
    public $en;
    public $gramaj;
    public $ne_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_orders_items_toquv_acs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_id', 'status', 'model_orders_items_id', 'wms_mato_info_id', 'toquv_raw_materials_id', 'count', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['quantity'], 'number'],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
            [['toquv_raw_materials_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRawMaterials::className(), 'targetAttribute' => ['toquv_raw_materials_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_orders_id' => Yii::t('app', 'Model Orders ID'),
            'model_orders_items_id' => Yii::t('app', 'Model Orders Items ID'),
            'toquv_raw_materials_id' => Yii::t('app', 'Toquv Raw Materials ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'count' => Yii::t('app', 'Count'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
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
    public function getToquvRawMaterials()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'toquv_raw_materials_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * */
    public function getWmsMatoInfo()
    {
        return $this->hasOne(WmsMatoInfo::class, ['id' => 'wms_mato_info_id']);
    }
}
