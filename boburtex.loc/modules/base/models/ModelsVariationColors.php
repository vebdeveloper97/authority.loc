<?php

namespace app\modules\base\models;

use app\modules\boyoq\models\Color;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\wms\models\WmsColor;
use app\modules\wms\models\WmsDesen;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use app\models\ColorPantone;

/**
 * This is the model class for table "models_variation_colors".
 *
 * @property int $id
 * @property int $model_var_id
 * @property int $color_pantone_id
 * @property int $is_main
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ColorPantone $colorPantone
 * @property ModelsVariations $modelVar
 * @property BaseDetailLists $baseDetailList
 * @property ToquvRawMaterials $rawMaterial
 * @property int $toquv_raw_material_id [int(11)]
 * @property int $base_detail_list_id [int(11)]
 * @property int $color_boyoqhona_id [int(11)]
 */
class ModelsVariationColors extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'models_variation_colors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_var_id', 'color_boyoqhona_id','color_pantone_id','base_detail_list_id','toquv_raw_material_id','is_main', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['color_pantone_id'], 'exist', 'skipOnError' => true, 'targetClass' => ColorPantone::className(), 'targetAttribute' => ['color_pantone_id' => 'id']],
            [['wms_color_id'], 'exist', 'skipOnError' => true, 'targetClass' => WmsColor::className(), 'targetAttribute' => ['wms_color_id' => 'id']],
            [['wms_desen_id'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDesen::className(), 'targetAttribute' => ['wms_desen_id' => 'id']],
            [['model_var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['model_var_id' => 'id']],
            [['base_detail_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseDetailLists::className(), 'targetAttribute' => ['base_detail_list_id' => 'id']],
            [['toquv_raw_material_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRawMaterials::className(), 'targetAttribute' => ['toquv_raw_material_id' => 'id']],
            [['color_boyoqhona_id'], 'exist', 'skipOnError' => true, 'targetClass' => Color::className(), 'targetAttribute' => ['color_boyoqhona_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_var_id' => Yii::t('app', 'Model Var ID'),
            'color_pantone_id' => Yii::t('app', 'Color Pantone ID'),
            'color_boyoqhona_id' => Yii::t('app', 'Boyoqhona Rangi'),
            'wms_color_id' => Yii::t('app', 'Wms Color ID'),
            'wms_desen_id' => Yii::t('app', 'Wms Desen ID'),
            'base_detail_list_id' => Yii::t('app', 'Base Detail List ID'),
            'toquv_raw_material_id' => Yii::t('app', 'Toquv Raw Material ID'),
            'is_main' => Yii::t('app', 'Is Main'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorPantone()
    {
        return $this->hasOne(ColorPantone::className(), ['id' => 'color_pantone_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVar()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'model_var_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorBoyoqhona()
    {
        return $this->hasOne(Color::className(), ['id' => 'color_boyoqhona_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseDetailList()
    {
        return $this->hasOne(BaseDetailLists::className(), ['id' => 'base_detail_list_id']);
    }
    /**
     * @return ActiveQuery
     * */
    public function getWmsColor()
    {
        return $this->hasOne(WmsColor::class,['id' => 'wms_color_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRawMaterial()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'toquv_raw_material_id']);
    }

    public function getColorData()
    {
        $parentId = $this->modelVar->id;

        $sql = "SELECT clp.id,
                clp.name as cname, clp.code as ccode, r, g, b, type.name as tname
                FROM color_pantone as clp
                LEFT JOIN color_panton_type as type 
                ON clp.color_panton_type_id = type.id
                WHERE clp.id IN 
                      (select color_pantone_id 
                      from models_variation_colors 
                      where model_var_id = {$parentId})
        ";
        $acs = Yii::$app->db->createCommand($sql)->queryAll();
        $res = [];
        foreach ($acs as $item) {
            $res[$item['id']] = [
                'id' => $item['id'],
                'name' => "<span style='background:rgb(".$item['r'].",".$item['g'].",".$item['b']."); width:80px;padding-left:5px;padding-right:5px;border:1px solid'><span style='opacity:0;'>".$item['tname'] . "</span></span> ".$item['ccode'] . " - <b>" . $item['cname'] . "</b>",
                'group' => $item['tname']
            ];
        }
        $result = ArrayHelper::map($res,'id','name','group');
        return $result;
    }

    public function getColorDataBoyoqhona()
    {
        $parentId = $this->modelVar->id;

        $sql = "SELECT c.id, c.color_id, ct.name, c.color
                FROM color c
                LEFT JOIN color_type as ct ON ct.id = c.color_tone
                WHERE c.id IN (select color_boyoqhona_id from models_variation_colors where model_var_id = {$parentId})";
        $acs = Yii::$app->db->createCommand($sql)->queryAll();
        $res = [];
        foreach ($acs as $item) {
            $res[$item['id']] = [
                'id' => $item['id'],
                'name' => "<span style='background:".$item['color']."; width:80px;padding-left:5px;padding-right:5px;border:1px solid'><span style='opacity:0;'>".$item['name'] . "</span></span> ".$item['color_id'],
                'group' => $item['name']
            ];
        }
        $result = ArrayHelper::map($res,'id','name','group');
        return $result;
    }

    public static function getColorList($q=null,$id=null)
    {
        $sql = "SELECT clp.id,
                clp.name as cname, clp.code as ccode, r, g, b, type.name as tname
                FROM color_pantone as clp
                LEFT JOIN color_panton_type as type ON clp.color_panton_type_id = type.id
                WHERE ";
        if ($id) {
            $sql .= "clp.id = {$id} ";
        } else {
            $sql .= "clp.color_panton_type_id = 3 AND clp.status = 1";
        }
        //$where = (!is_null($id))?"clp.id = {$id}":"clp.color_panton_type_id = 3";
        if($q){
            $sql .= " AND (clp.name LIKE '%{$q}%' OR clp.code LIKE '%{$q}%')";
        }
        //$sql = sprintf($sql,$where);
        $acs = Yii::$app->db->createCommand($sql)->queryAll();
        return $acs;
    }
    public static function getColorBoyoqhonaList($q=null,$id=null)
    {
        $sql = "SELECT c.id, c.color, c.color_id, ct.name, tone.name tone
                FROM color as c
                LEFT JOIN color_type ct ON ct.id = c.color_tone
                LEFT JOIN color_tone as tone ON c.color_tone = tone.id
                WHERE 1=1 ";
        if($q){
            $sql .= " AND (c.name LIKE '%{$q}%' OR c.color_id LIKE '%{$q}%' OR c.pantone LIKE '%{$q}%' OR ct.name LIKE '%{$q}%' OR tone.name LIKE '%{$q}%' OR c.color LIKE '%{$q}%')";
        }
        if($id){
            $sql .= " AND (c.id = {$id})";
        }
        $acs = Yii::$app->db->createCommand($sql)->queryAll();
        return $acs;
    }

    public static function getBoyoqColorList($q=null,$id=null)
    {
        $sql = "SELECT c.id,
                c.name as cname, c.color_id color_id, c.pantone as pantone, c.color, ct.name as tname
                FROM color as c
                LEFT JOIN color_tone as ct ON c.color_tone = ct.id
                WHERE 1=1 ";
        if($q){
            $sql .= " AND (c.name LIKE '%{$q}%' OR c.color_id LIKE '%{$q}%' OR c.pantone LIKE '%{$q}%' OR ct.name LIKE '%{$q}%' OR c.color LIKE '%{$q}%')";
        }
        if($id){
            $sql .= " AND (c.id = {$id})";
        }
        $acs = Yii::$app->db->createCommand($sql)->queryAll();
        return $acs;
    }

    public function getBoyoqServiceList($id=null)
    {
        $sql = "SELECT bs.id,bs.name 
                FROM models_variation_colors mvc
                    LEFT JOIN models_variations mv ON mvc.model_var_id = mv.id
                    LEFT JOIN models_list ml on mv.model_list_id = ml.id
                    LEFT JOIN models_raw_materials mrm ON ml.id = mrm.model_list_id
                    LEFT JOIN model_rm_boyoq_service mrbs ON mrbs.models_raw_materials_id = mrm.id
                    LEFT JOIN boyahane_services bs ON bs.id = mrbs.boyahane_service_id
                    WHERE mvc.toquv_raw_material_id = mrm.rm_id
                ";
        if($id){
            $sql .= " AND (mvc.id = {$id})";
        }else{
            $sql .= " AND (mvc.id = {$this->id})";
        }
        $sql .= "GROUP BY bs.id";
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($result, 'id', 'name');
    }
}
