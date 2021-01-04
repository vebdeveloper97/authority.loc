<?php

namespace app\modules\base\models;

use app\models\ColorPantone;
use app\modules\boyoq\models\Color;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvRawMaterials;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%model_orders_planning}}".
 *
 * @property int $id
 * @property int $model_orders_items_id
 * @property int $toquv_raw_materials_id
 * @property double $work_weight
 * @property double $finished_fabric
 * @property double $raw_fabric
 * @property string $thread_length
 * @property string $finish_en
 * @property string $finish_gramaj
 * @property int $color_pantone_id
 * @property int $color_id
 * @property string $add_info
 * @property int $model_orders_id
 * @property int $parent_id
 * @property int $model_orders_items_changes_id
 * @property int $status
 *
 * @property Color $color
 * @property ModelOrdersItemsChanges $modelOrdersItemsChanges
 * @property ModelOrdersItems $modelOrdersItems
 * @property ToquvRawMaterials $toquvRawMaterials
 * @property MoiRelDept[] $moiRelDepts
 * @property ActiveQuery $colorPantone
 * @property mixed $modelOrdersOne
 * @property mixed $modelOrdersInfo
 * @property int $count [int(11)]
 * @property int $size_id [int(11)]
 * @property ActiveQuery $size
 * @property int $type [smallint(6)]
 * @property string $size_list [varchar(200)]
 * @property string $size_list_name [varchar(200)]
 * @property int $size_count [int(11)]
 */
class ModelOrdersPlanning extends ActiveRecord
{
    const SCENARIO_MATO = 'mato';
    const SCENARIO_AKS = 'aks';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_orders_planning}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_items_id', 'toquv_raw_materials_id', 'color_pantone_id', 'color_id', 'model_orders_id', 'parent_id', 'model_orders_items_changes_id', 'status', 'count', 'size_id','type','size_count'], 'integer'],
            [['color_pantone_id', 'work_weight', 'finished_fabric', 'raw_fabric', 'finish_gramaj'], 'required', 'on' => self::SCENARIO_MATO],
            [['raw_fabric'], 'required', 'on' => self::SCENARIO_AKS],
            [['work_weight', 'finished_fabric', 'raw_fabric'], 'number'],
            [['add_info'], 'string'],
            [['thread_length', 'finish_en', 'finish_gramaj'], 'string', 'max' => 30],
            [['size_list','size_list_name'], 'string', 'max' => 200],
            [['color_id'], 'exist', 'skipOnError' => true, 'targetClass' => Color::className(), 'targetAttribute' => ['color_id' => 'id']],
            [['model_orders_items_changes_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItemsChanges::className(), 'targetAttribute' => ['model_orders_items_changes_id' => 'id']],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
            [['toquv_raw_materials_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRawMaterials::className(), 'targetAttribute' => ['toquv_raw_materials_id' => 'id']],
        ];
    }
    public function getPriorityList($key = null){
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

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_MATO => ['model_orders_items_id', 'toquv_raw_materials_id', 'color_pantone_id', 'color_id', 'model_orders_id', 'parent_id', 'model_orders_items_changes_id', 'status', 'count', 'size_id','type','work_weight', 'finished_fabric', 'raw_fabric','add_info','thread_length', 'finish_en', 'finish_gramaj','size_count','size_list','size_list_name'],
            self::SCENARIO_AKS => ['model_orders_items_id', 'toquv_raw_materials_id', 'color_pantone_id', 'color_id', 'model_orders_id', 'parent_id', 'model_orders_items_changes_id', 'status', 'count', 'size_id','type','work_weight', 'finished_fabric', 'raw_fabric','add_info','thread_length', 'finish_en', 'finish_gramaj','size_count','size_list','size_list_name']
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_orders_items_id' => Yii::t('app', 'Model Orders Items ID'),
            'toquv_raw_materials_id' => Yii::t('app', 'Toquv Raw Materials ID'),
            'work_weight' => Yii::t('app', 'Work Weight'),
            'finished_fabric' => Yii::t('app', 'Finished Fabric'),
            'raw_fabric' => Yii::t('app', 'Raw Fabric'),
            'thread_length' => Yii::t('app', 'Thread Length'),
            'finish_en' => Yii::t('app', 'Finish En'),
            'finish_gramaj' => Yii::t('app', 'Finish Gramaj'),
            'color_pantone_id' => Yii::t('app', 'Color Pantone ID'),
            'color_id' => Yii::t('app', "Rang (Bo'yoqxona)"),
            'add_info' => Yii::t('app', 'Add Info'),
            'model_orders_id' => Yii::t('app', 'Model Orders ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'model_orders_items_changes_id' => Yii::t('app', 'Model Orders Items Changes ID'),
            'status' => Yii::t('app', 'Status'),
            'count' => Yii::t('app', 'Count'),
            'type' => Yii::t('app', 'Type'),
        ];
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
    public function getMoiRelDepts()
    {
        return $this->hasMany(MoiRelDept::className(), ['model_orders_planning_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItemsChanges()
    {
        return $this->hasOne(ModelOrdersItemsChanges::className(), ['id' => 'model_orders_items_changes_id']);
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
    public function getToquvRawMaterials()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'toquv_raw_materials_id']);
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
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }
    public function getModelOrdersOne(){
        $sql = "SELECT
                    trm.name,
                    CONCAT(ml.article, ' ',ml.name) model,
                    moi.id m_order_id,
                    mo.id,
                    toquv_raw_materials_id trm_id,
                    mop.thread_length,
                    finish_en,
                    finish_gramaj,
                    summa
                FROM model_orders_planning mop                      
                LEFT JOIN model_orders_items moi ON moi.id = mop.model_orders_items_id                         
                LEFT JOIN moi_rel_dept mrd ON mrd.model_orders_items_id = moi.id                         
                LEFT JOIN model_orders mo ON mo.id = moi.model_orders_id                     
                LEFT JOIN toquv_raw_materials trm on mop.toquv_raw_materials_id = trm.id       
                LEFT JOIN models_list ml on moi.models_list_id = ml.id
                LEFT JOIN (SELECT model_orders_items_id,SUM(count) summa FROM model_orders_items_size mois LEFT JOIN size s on mois.size_id = s.id GROUP BY mois.model_orders_items_id) mois on moi.id = mois.model_orders_items_id
                WHERE (mrd.toquv_departments_id=2) AND (mop.id= %d)";
        $sql = sprintf($sql,$this->id);
        return Yii::$app->db->createCommand($sql)->queryOne();
    }
    public function getMoiRel($token){
        $dept = ToquvDepartments::findOne(['token'=>$token])['id'];
        $rel = MoiRelDept::findOne(['model_orders_items_id'=>$this->model_orders_items_id,'toquv_departments_id'=>$dept]);
        if($rel){
            return $rel;
        }
        return false;
    }
    public function getModelOrdersInfo(){
        $sql = "SELECT
                    trm.name,
                    CONCAT(ml.article, ' ',ml.name) model,
                    moi.id m_order_id,
                    mo.id,
                    toquv_raw_materials_id trm_id,
                    mop.thread_length,
                    mop.finish_en,
                    mop.finish_gramaj,
                    mop.raw_fabric quantity,
                    summa
                FROM model_orders_planning mop                      
                LEFT JOIN model_orders_items moi ON moi.id = mop.model_orders_items_id                       
                LEFT JOIN model_orders mo ON mo.id = moi.model_orders_id                     
                LEFT JOIN toquv_raw_materials trm on mop.toquv_raw_materials_id = trm.id       
                LEFT JOIN models_list ml on moi.models_list_id = ml.id
                LEFT JOIN (SELECT model_orders_items_id,SUM(count) summa FROM model_orders_items_size mois LEFT JOIN size s on mois.size_id = s.id GROUP BY mois.model_orders_items_id) mois on moi.id = mois.model_orders_items_id
                WHERE (mop.id= %d)";
        $sql = sprintf($sql,$this->id);
        return Yii::$app->db->createCommand($sql)->queryOne();
    }
    public static function getList($id)
    {
        $list = ModelOrdersPlanning::find()->where(['model_orders_items_id'=>$id])->all();
        $result = ArrayHelper::map($list,'id', function ($m){
            $t = $m->modelOrdersInfo;
            return "{$t['name']}-{$t['model']} ({$m->colorPantone['code']}) - ({$t['quantity']} kg)";
        });
        return $result;
    }
}
