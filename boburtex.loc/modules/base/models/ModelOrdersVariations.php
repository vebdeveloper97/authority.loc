<?php

namespace app\modules\base\models;

use app\modules\toquv\models\ToquvRawMaterials;
use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%model_orders_variations}}".
 *
 * @property int $id
 * @property int $model_orders_id
 * @property int $variant_no
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property ModelOrdersItems[] $modelOrdersItems
 * @property ModelOrders $modelOrders
 */
class ModelOrdersVariations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_orders_variations}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_id', 'variant_no', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['orders_items', 'models_list_id'], 'safe'],
            [['model_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['model_orders_id' => 'id']],
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
            'variant_no' => 'Variant No',
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
    public function getModelOrdersItems()
    {
        return $this->hasMany(ModelOrdersItems::className(), ['model_orders_variations_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrders()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'model_orders_id']);
    }

    public static function getVariantMaterials($model_orders_id, $var_status=1,$var_id=null,$status=3) {
        $variantQuery = ModelOrders::find()
            ->alias('mo')
            ->select([
                'trm.code as rcode',
                'trm.name as rname',
                'type.name as tname',
                'tn.name AS ne',
                'tt.name AS thread',
                'tpf.name AS pus_fine',
                /*'wc.color_name AS wc_name',
                'wc.color_code AS wc_code',
                'cp.name AS cp_name',
                'cp.code AS cp_code',*/
                'IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) as color_code',
                'IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) as color_name',
                'wmi.en',
                'wmi.gramaj',
                'wd.name AS desen_name',
                'wd.code AS desen_code',
                'wbt.name AS baski_name',
                'moi.model_orders_variations_id AS model_orders_variations',
                'moim.add_info AS material_info',
            ])
            ->leftJoin(['moi' => 'model_orders_items'], 'mo.id = moi.model_orders_id')
            ->leftJoin(['mov' => 'model_orders_variations'], 'moi.model_orders_variations_id = mov.id')
            ->leftJoin(['moim' => 'model_orders_items_material'], 'moi.id = moim.model_orders_items_id')
            ->leftJoin(['wmi' => 'wms_mato_info'], 'moim.mato_id = wmi.id')
            ->leftJoin(['trm' => 'toquv_raw_materials'], 'wmi.toquv_raw_materials_id = trm.id')
            ->leftJoin(['type' => 'raw_material_type'], 'trm.raw_material_type_id = type.id')
            ->leftJoin(['trmi' => 'toquv_raw_material_ip'], 'trm.id = trmi.toquv_raw_material_id')
            ->leftJoin(['tn' => 'toquv_ne'], 'trmi.ne_id = tn.id')
            ->leftJoin(['tt' => 'toquv_thread'], 'trmi.thread_id = tt.id')
            ->leftJoin(['tpf' => 'toquv_pus_fine'], 'wmi.pus_fine_id = tpf.id')
            ->leftJoin(['wc' => 'wms_color'], 'wmi.wms_color_id = wc.id')
            ->leftJoin(['cp' => 'color_pantone'], 'wc.color_pantone_id = cp.id')
            ->leftJoin(['wd' => 'wms_desen'], 'wmi.wms_desen_id = wd.id')
            ->leftJoin(['wbt' => 'wms_baski_type'], 'wd.wms_baski_type_id = wbt.id')
            ->andWhere([
                'mo.id' => $model_orders_id,
                'mo.status' => $status,
                'mov.status' => $var_status,
            ])
            ->andFilterWhere([
                'moi.model_orders_variations_id' => $var_id
            ])
            ->groupBy(['rname'])
            ->asArray()
            ->all();
        return $variantQuery;
    }

    public static function getVariationAcs($model_orders_id, $var_status=1,$var_id=null, $status=3) {
        $acsQuery = ModelOrders::find()
            ->alias('mo')
            ->select([
                'ba.sku AS artikul',
                'ba.name AS acs_name',
                "GROUP_CONCAT(bapl.name, ': ', bap.value SEPARATOR ', ') AS acs_properties",
                'moia.qty AS order_acs_qty',
                'u.name AS unit_name',
                'moia.add_info AS order_acs_info',
            ])
            ->leftJoin(['moi' => 'model_orders_items'], 'mo.id = moi.model_orders_id')
            ->leftJoin(['mov' => 'model_orders_variations'], 'moi.model_orders_variations_id = mov.id')
            ->leftJoin(['moia' => 'model_orders_items_acs'], 'moi.id = moia.model_orders_items_id')
            ->leftJoin(['ba' => 'bichuv_acs'], 'moia.bichuv_acs_id = ba.id')
            ->leftJoin(['u' => 'unit'], 'ba.unit_id = u.id')
            ->leftJoin(['bap' => 'bichuv_acs_properties'], 'ba.id = bap.bichuv_acs_id')
            ->leftJoin(['bapl' => 'bichuv_acs_property_list'], 'bap.bichuv_acs_property_list_id = bapl.id')
            ->andWhere([
                'mo.id' => $model_orders_id,
                'mo.status' => $status,
                'mov.status' => $var_status,
            ])
            ->andFilterWhere([
                'moi.model_orders_variations_id' => $var_id
            ])
            ->groupBy('mo.id, moia.id')
            ->asArray()
            ->all();

        return $acsQuery;
    }

    public static function getVariationToquvAcs($model_orders_id, $var_status=1,$var_id=null, $status=3) {
        $acsQuery = ModelOrders::find()
            ->alias('mo')
            ->select([
                'trm.name AS trmname',
                'rmt.name AS rmt_name',
                'wmi.wms_desen_id',
                'wmi.wms_color_id',
                'wmi.pus_fine_id',
                'wmi.toquv_raw_materials_id',
                'wmi.en',
                'wmi.gramaj',
                'wmi.ne_id',
                'wd.name as wdname','wd.code as wdcode', 'wbt.name as wbtname',
                'wc.color_pantone_id', 'wc.color_code', 'wc.color_name', 'wc.color_palitra_code',
                'cp.name as cpname', 'cp.code as cpcode',
                'tpf.name as tpf_name',
                'tn.name as tn_name',
                'moita.count'
            ])
            ->leftJoin(['moi' => 'model_orders_items'], 'mo.id = moi.model_orders_id')
            ->leftJoin(['mov' => 'model_orders_variations'], 'moi.model_orders_variations_id = mov.id')
            ->leftJoin(['moita' => 'model_orders_items_toquv_acs'], 'moi.id = moita.model_orders_items_id')
            ->leftJoin(['wmi' => 'wms_mato_info'], 'moita.wms_mato_info_id = wmi.id')
            ->leftJoin(['wd' => 'wms_desen'], 'wmi.wms_desen_id = wd.id')
            ->leftJoin(['wc' => 'wms_color'], 'wmi.wms_color_id = wc.id')
            ->leftJoin(['cp' => 'color_pantone'], 'wc.color_pantone_id = cp.id')
            ->leftJoin(['tpf' => 'toquv_pus_fine'], 'wmi.pus_fine_id = tpf.id')
            ->leftJoin(['tn' => 'toquv_ne'], 'wmi.ne_id = tn.id')
            ->leftJoin(['trm' => 'toquv_raw_materials'], 'wmi.toquv_raw_materials_id = trm.id')
            ->leftJoin(['rmt' => 'raw_material_type'], 'trm.raw_material_type_id = rmt.id')
            ->leftJoin(['wbt' => 'wms_baski_type'],'wd.wms_baski_type_id = wbt.id')
            ->andWhere([
                'mo.id' => $model_orders_id,
                'mo.status' => $status,
                'mov.status' => $var_status,
                'wmi.type' => ToquvRawMaterials::ACS
            ])
            ->andFilterWhere([
                'moi.model_orders_variations_id' => $var_id
            ])
            ->groupBy('mo.id, moita.id')
            ->asArray()
            ->all();

        return $acsQuery;
    }
}
