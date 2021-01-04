<?php

namespace app\modules\base\models;

use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\wms\models\WmsMatoInfo;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%model_orders_items_material}}".
 *
 * @property int $id
 * @property int $model_orders_id
 * @property int $model_orders_items_id
 * @property int $mato_id
 * @property string $add_info
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property ToquvRawMaterials $mato
 * @property ModelOrders $modelOrders
 * @property ModelOrdersItems $modelOrdersItems
 * $property WmsMatoInfo $wmsMatoInfo
 */
class ModelOrdersItemsMaterial extends BaseModel
{
    // mato info properties
    public $toquv_raw_materials_id;
    public $wms_color_id;
    public $pus_fine_id;
    public $wms_desen_id;
    public $gramaj;
    public $en;
    public $basename;
    /** WmsDocumentItems uchun*/
    public $quantity;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_orders_items_material}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_id', 'model_orders_items_id', 'mato_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'models_list_id', 'model_var_id'], 'integer'],
            [['add_info'], 'string'],
            [['mato_id'], 'exist', 'skipOnError' => true, 'targetClass' => WmsMatoInfo::className(), 'targetAttribute' => ['mato_id' => 'id']],
            [['model_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['model_orders_id' => 'id']],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],

            /** mato info attribute rules */
            [
                [
                    'toquv_raw_materials_id', 'pus_fine_id', 'gramaj', 'en', 'wms_color_id', 'wms_desen_id'
                ],
                'required', //'on' => self::SCENARIO_INCOMING_GENERAL_ORDER
            ],
            [
                ['quantity'], 'safe'
            ],
            [
                ['toquv_raw_materials_id', 'pus_fine_id', 'wms_desen_id', 'wms_color_id'],
                'integer', //'on' => self::SCENARIO_INCOMING_GENERAL_ORDER
            ],
            [
                ['gramaj', 'en'],
                'number', //'on' => self::SCENARIO_INCOMING_GENERAL_ORDER
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_orders_id' => 'Model Orders ID',
            'model_orders_items_id' => 'Model Orders Items ID',
            'mato_id' => 'Mato ID',
            'add_info' => 'Add Info',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMato()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'mato_id']);
    }

    /**
     * @return ActiveQuery
     * */
    public function getWmsMatoInfo(){
        return $this->hasMany(WmsMatoInfo::class, ['id' => 'mato_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrders()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'model_orders_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersItems()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'model_orders_items_id']);
    }
}
