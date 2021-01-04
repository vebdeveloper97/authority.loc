<?php

namespace app\modules\toquv\models;

use app\models\ColorPantone;
use app\modules\base\models\ModelOrdersItems;
use app\modules\boyoq\models\Color;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "toquv_rm_order".
 *
 * @property int $id
 * @property int $toquv_orders_id
 * @property int $toquv_raw_materials_id
 * @property int $priority
 * @property int $rm_type
 * @property string $price
 * @property string $price_fakt
 * @property int $pb_id
 * @property int $discount
 * @property string $percentage
 * @property string $quantity
 * @property int $unit_id
 * @property string $done_date
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property string $thread_length
 * @property string $finish_en
 * @property string $finish_gramaj
 * @property int $moi_id
 * @property string $planed_date
 * @property string $finished_date
 * @property int $type_weaving
 * @property double $count
 * @property int $color_pantone_id
 * @property int $model_musteri_id
 * @property string $model_code
 * @property int $color_id
 * @property int $order_type
 * @property int $toquv_pus_fine_id
 *
 * @property ToquvInstructionRm[] $toquvInstructionRms
 * @property ToquvKalite[] $toquvKalites
 * @property ToquvMakineProcesses[] $toquvMakineProcesses
 * @property Color $color
 * @property ToquvOrders $toquvOrders
 * @property ToquvPusFine $pusFine
 * @property ToquvAksModel $toquvAks
 * @property ToquvRawMaterials $toquvRawMaterials
 * @property ToquvRmOrderItems[] $toquvRmOrderItems
 * @property float $service
 * @property mixed $unit
 * @property ActiveQuery $colorPantone
 * @property mixed $unitList
 * @property mixed $moi
 * @property string $add_info
 * @property mixed $toquvRmOrderMoi
 * @property string $size_list_name [varchar(200)]
 */
class ToquvRmOrder extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_rm_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_orders_id', 'toquv_raw_materials_id', 'priority', 'rm_type', 'pb_id', 'discount', 'unit_id', 'status', 'created_by', 'created_at', 'updated_at', 'moi_id', 'type_weaving', 'color_pantone_id', 'model_musteri_id', 'color_id', 'order_type', 'toquv_pus_fine_id'], 'integer'],
            [['price', 'price_fakt', 'percentage', 'quantity', 'count'], 'number'],
            ['size_list_name','string','max' => 200],
            ['add_info','string'],
            [['done_date', 'planed_date', 'finished_date'], 'safe'],
            [['thread_length', 'finish_en', 'finish_gramaj'], 'string', 'max' => 30],
            [['model_code'], 'string', 'max' => 50],
            [['color_id'], 'exist', 'skipOnError' => true, 'targetClass' => Color::className(), 'targetAttribute' => ['color_id' => 'id']],
            [['toquv_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvOrders::className(), 'targetAttribute' => ['toquv_orders_id' => 'id']],
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
            'toquv_orders_id' => Yii::t('app', 'Toquv Orders ID'),
            'toquv_raw_materials_id' => Yii::t('app', 'Toquv Raw Materials ID'),
            'priority' => Yii::t('app', 'Priority'),
            'rm_type' => Yii::t('app', 'Rm Type'), 
            'price' => Yii::t('app', 'Price'),
            'price_fakt' => Yii::t('app', 'Price Fakt'),
            'pb_id' => Yii::t('app', 'Pb Id'),
            'discount' => Yii::t('app', 'Discount'),
            'percentage' => Yii::t('app', 'Percentage'),
            'quantity' => Yii::t('app', 'Quantity'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'done_date' => Yii::t('app', 'Done Date'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'thread_length' => Yii::t('app', 'Thread Length'), 
            'finish_en' => Yii::t('app', 'Finish En'), 
            'finish_gramaj' => Yii::t('app', 'Finish Gramaj'),
            'moi_id' => Yii::t('app', 'Moi ID'),
            'planed_date' => Yii::t('app', 'Planed Date'),
            'finished_date' => Yii::t('app', 'Finished Date'),
            'type_weaving' => Yii::t('app', 'Type Weaving'),
            'count' => Yii::t('app', 'Count'),
            'color_pantone_id' => Yii::t('app', 'Color Pantone ID'),
            'model_musteri_id' => Yii::t('app', 'Model buyurtmachisi'),
            'model_code' => Yii::t('app', 'Model kodi'),
            'color_id' => Yii::t('app', "Rang(Bo'yoq)"),
            'order_type' => Yii::t('app', 'Buyurtma turi'),
            'toquv_pus_fine_id' => Yii::t('app', 'Pus/Fine'),
        ];
    }
    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getPriorityList($key = null){
        $list = [
            1 => Yii::t('app','Low'),
            2 => Yii::t('app','Normal'),
            3 => Yii::t('app','High'),
            4 => Yii::t('app','Urgent')
        ];
        $options = [
            1 => ['style'=>'background:#ccc;color:white;padding:2px;font-weight:bold'],
            2 => ['style' => 'background:green;color:white;padding:2px;font-weight:bold'],
            3 => ['style' => 'background:#CC7722;color:white;padding:2px;font-weight:bold'],
            4 => ['style' => 'background:red;color:white;padding:2px;font-weight:bold'],
        ];
        if($key && $key != 'options'){
            return $list[$key];
        }
        if($key && $key == 'options'){
            return $options;
        }
        return $list;
    }

     /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $date = date('Y-m-d');
            if(!empty($this->done_date)){
                $date = date('Y-m-d', strtotime($this->done_date));
            }
            $this->done_date = $date;
            return true;
        }else{
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->done_date = date('d.m.Y', strtotime($this->done_date));

    }
    /**
     * @return ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'color_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getColorPantone()
    {
        return $this->hasOne(ColorPantone::className(), ['id' => 'color_pantone_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getToquvInstructionRms()
    {
        return $this->hasMany(ToquvInstructionRm::className(), ['toquv_rm_order_id' => 'id']);
    }
    public function getToquvKalites()
    {
        return $this->hasMany(ToquvKalite::className(), ['toquv_rm_order_id' => 'id']);
    }
    public function getToquvMakineProcesses()
    {
        return $this->hasMany(ToquvMakineProcesses::className(), ['toquv_order_item_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getToquvOrders()
    {
        return $this->hasOne(ToquvOrders::className(), ['id' => 'toquv_orders_id']);
    }
    public function getMoi()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'moi_id']);
    }
    public function getToquvRmOrderMoi()
    {
        return $this->hasMany(ToquvRmOrderMoi::className(), ['toquv_rm_order_id' => 'id']);
    }

    public static function getOrderInfo($id)
    {
        $sql ="SELECT trom.quantity,trom.start_date,trom.end_date,moi.model,CONCAT('<div class=\"flex-container flex-baski\">',moi.baski,'</div>') baski FROM toquv_rm_order_moi trom
                LEFT JOIN toquv_rm_order tro on trom.toquv_rm_order_id = tro.id
                JOIN (SELECT moi.id moi_id,CONCAT(ml.article,' (',ml.name,')') model,GROUP_CONCAT(IF(a.path!='',CONCAT('<div class=\"flex-div\"><span class=\"thumbnail text-center no-margin\"><img class=\"imgPreview imgSize\" src=\"/web/',a.path,'\">',mvb.name,' (',mvb.code,')</span></div>'), CONCAT('<span class=\"thumbnail text-center no-margin\">',mvb.name,' (',mvb.code,')</span>')) SEPARATOR ' ') baski FROM model_order_items_baski moib
                    LEFT JOIN model_orders_items moi on moib.model_orders_items_id = moi.id
                    LEFT JOIN models_list ml ON ml.id = moi.models_list_id
                    LEFT JOIN model_var_baski mvb on moib.model_var_baski_id = mvb.id
                    LEFT JOIN model_var_baski_rel_attach mvbra on mvb.id = mvbra.model_var_baski_id
                    LEFT JOIN attachments a on mvbra.attachment_id = a.id
                    WHERE (mvbra.is_main = 1 OR mvbra.is_main) GROUP BY moi.id) moi ON moi.moi_id = trom.model_orders_items_id
                    AND trom.toquv_rm_order_id = %d
        ";
        $sql = sprintf($sql,$id);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        if($results){
            return $results;
        }
        return null;
    }
    /**
     * @return ActiveQuery
     */
    public function getToquvRawMaterials()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'toquv_raw_materials_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getToquvAks()
    {
        return $this->hasOne(ToquvAksModel::className(), ['id' => 'toquv_raw_materials_id']);
    }
    public function getPusFine()
    {
        return $this->hasOne(ToquvPusFine::className(), ['id' => 'toquv_pus_fine_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getToquvRmOrderItems()
    {
        return $this->hasMany(ToquvRmOrderItems::className(), ['toquv_rm_order_id' => 'id']);
    }
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }
    public function getUnitList()
    {
        $rawMaterial = Unit::find()->select(['id','name'])->asArray()->all();
        return ArrayHelper::map($rawMaterial,'id','name');
    }
    public static function getToquvRawMaterialList($type=1)
    {
        $rawMaterial = ToquvRawMaterials::find()->select(['id','name'])->where(['type' => $type])->asArray()->all();
        return ArrayHelper::map($rawMaterial,'id','name');
    }
    public function getService()
    {
        $consist = ToquvRawMaterialConsist::find()->where(['fabric_type_id' => 3,'raw_material_id' => $this->toquv_raw_materials_id])->one();
        $service = ($consist)?0.35:0.25;
        return $service;
    }
}
