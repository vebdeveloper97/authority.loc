<?php

namespace app\modules\base\models;

use app\modules\boyoq\models\ColorPantone;
use app\modules\wms\models\WmsMatoInfo;
use Yii;
use app\modules\toquv\models\ToquvRawMaterials;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "models_raw_materials".
 *
 * @property int $id
 * @property int $model_list_id
 * @property int $rm_id
 * @property int $is_main
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property string $thread_length
 * @property string $finish_en
 * @property string $finish_gramaj
 * @property int $for_all_sizes
 *
 * @property ModelsList $modelList
 * @property ToquvRawMaterials $rm
 * @property ToquvRawMaterials $listWithType
 * @property ModelsRawMaterialsSizes[] $modelsRawMaterialsSizes
 */
class ModelsRawMaterials extends \yii\db\ActiveRecord
{
    public $sizes;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%models_raw_materials}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_list_id', 'color_id', 'rm_id', 'is_main', 'status', 'created_by', 'created_at', 'updated_at', 'for_all_sizes'], 'integer'],
            [['add_info'], 'string'],
            [['thread_length', 'finish_en', 'finish_gramaj'], 'string', 'max' => 30],
            [['model_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_list_id' => 'id']],
            [['color_id'], 'exist', 'skipOnError' => true, 'targetClass' => ColorPantone::className(), 'targetAttribute' => ['color_id' => 'id']],
            [['rm_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRawMaterials::className(), 'targetAttribute' => ['rm_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_list_id' => Yii::t('app', 'Model List ID'),
            'rm_id' => Yii::t('app', 'Rm ID'),
            'is_main' => Yii::t('app', 'Is Main'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'thread_length' => Yii::t('app', 'Uzunligi'),
            'finish_en' => Yii::t('app', 'Fin. En / Eni'),
            'color_id' => Yii::t('app', 'ColorId'),
            'finish_gramaj' => Yii::t('app', 'Fin. Gramaj / Qavati'),
            'for_all_sizes' => Yii::t('app', 'For All Sizes'),
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->sizes = $this->sizeList;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRm()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'rm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsRawMaterialsSizes()
    {
        return $this->hasMany(ModelsRawMaterialsSizes::className(), ['models_raw_materials_id' => 'id']);
    }

    public function getListWithType()
    {
        return ToquvRawMaterials::find()->select(['id','name','code'])->where(['toquv_raw_materials.raw_material_type_id'=>$this->rm->raw_material_type_id])->asArray()->all();
    }

    public static function getMaterialList($entity_type=null)
    {
        $color = ($entity_type==ToquvRawMaterials::ACS)?",trmc.name color":"";
        $color_join = ($entity_type==ToquvRawMaterials::ACS)?"LEFT JOIN toquv_raw_material_color trmc ON raw.color_id = trmc.id":"";
        $sql = "SELECT
                    raw.id,
                    raw.code,
                    raw.name as rname,
                    type.name as tname,
                    ne_id,
                    tn.name ne,
                    tt.name thread 
                    %s
                FROM
                    toquv_raw_materials as raw          
                LEFT JOIN
                    raw_material_type as type                    
                        ON raw.raw_material_type_id = type.id     
                LEFT JOIN
                    toquv_raw_material_ip trmi 
                        on raw.id = trmi.toquv_raw_material_id     
                LEFT JOIN
                    toquv_ne tn 
                        on trmi.ne_id = tn.id     
                LEFT JOIN
                    toquv_thread tt 
                        on trmi.thread_id = tt.id
                %s
                %s
        ";
        $type = ($entity_type)?" WHERE raw.type = {$entity_type}":"";
        $sql = sprintf($sql,$color,$color_join,$type);
        $acs = Yii::$app->db->createCommand($sql)->queryAll();
        $res = [];
        $ip = [];
        foreach ($acs as $item) {
            $color = (!empty($item['color']))?$item['color']:'';
            $ip[$item['id']]['ip'] .= " (".$item['ne']."-".$item['thread'] . ")";
            $res[$item['tname']][$item['id']] = $item['code'] ." - {$color} <b>". $item['rname'] . " - " . $item['tname'] ."</b>" . $ip[$item['id']]['ip'];
        }
        return $res;
    }
    public function getSizeList($is_view=false){
        if($is_view){
            $sizes = ModelsRawMaterialsSizes::find()->select('size_id,size.name')->joinWith('size')->where(['models_raw_materials_id'=>$this->id])->asArray()->all();
            $result = '<code>';
            if(!empty($sizes)){
                foreach ($sizes as $key => $size) {
                    $result .= ($key!=0)?", ".$size['name']:$size['name'];
                }
            }else{
                $result .= Yii::t('app', "Barcha o'lchamlar");
            }
            return $result."</code>";
        }
        $sizes = ModelsRawMaterialsSizes::find()->select('size_id')->where(['models_raw_materials_id'=>$this->id])->asArray()->all();
        $list = [];
        if(!empty($sizes)){
            return ArrayHelper::getColumn($sizes,'size_id');
        }
        return $list;
    }
}
