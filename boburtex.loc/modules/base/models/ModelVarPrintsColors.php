<?php

namespace app\modules\base\models;

use app\models\ColorPantone;
use app\modules\wms\models\WmsColor;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%model_var_prints_colors}}".
 *
 * @property int $id
 * @property int $model_var_prints_id
 * @property int $color_pantone_id
 * @property int $wms_color_id
 * @property int $is_main
 * @property string $add_info
 *
 * @property ColorPantone $colorPantone
 * @property ModelVarPrints $modelVarPrints
 */
class ModelVarPrintsColors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_var_prints_colors}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wms_color_id'], 'required'],
            [['model_var_prints_id', 'color_pantone_id', 'is_main'], 'integer'],
            [['add_info'], 'string', 'max' => 255],
            [['color_pantone_id'], 'exist', 'skipOnError' => true, 'targetClass' => ColorPantone::class, 'targetAttribute' => ['color_pantone_id' => 'id']],
            [['wms_color_id'], 'exist', 'skipOnError' => true, 'targetClass' => WmsColor::class, 'targetAttribute' => ['wms_color_id' => 'id']],
            [['model_var_prints_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelVarPrints::class, 'targetAttribute' => ['model_var_prints_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_var_prints_id' => Yii::t('app', 'Model Var Prints ID'),
            'color_pantone_id' => Yii::t('app', 'Color Pantone ID'),
            'is_main' => Yii::t('app', 'Is Main'),
            'add_info' => Yii::t('app', 'Add Info'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorPantone()
    {
        return $this->hasOne(ColorPantone::class, ['id' => 'color_pantone_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWmsColor()
    {
        return $this->hasOne(WmsColor::class, ['id' => 'wms_color_id']);
    }
    /**models_toquv_acs
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarPrints()
    {
        return $this->hasOne(ModelVarPrints::class, ['id' => 'model_var_prints_id']);
    }
    public static function getColorList($list=false,$q=null,$id=null)
    {
        $sql = "SELECT clp.id,
                clp.name as cname, clp.code as ccode, r, g, b, type.name as tname
                FROM color_pantone as clp
                LEFT JOIN color_panton_type as type ON clp.color_panton_type_id = type.id
                LEFT JOIN model_var_prints_colors mvpc on clp.id = mvpc.color_pantone_id
                LEFT JOIN model_var_prints mvp on mvpc.model_var_prints_id = mvp.id
                WHERE clp.color_panton_type_id = 3 ";
        if($q){
            $sql .= " AND (clp.name LIKE '%{$q}%' OR clp.code LIKE '%{$q}%')";
        }
        if($id){
            $sql .= " AND (mvp.id = {$id})";
        }
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        if($list){
            return $res;
        }
        $color_list = [];
        if (!empty($res)) {
            foreach ($res as $item) {
                $name = "<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>"
                    .$item['tname'] . "</span></span> ".$item['ccode'] . " - <b>"
                    . $item['cname'] . "</b>";
                $color_list[$item['id']] = [
                    'id' => $item['id'],
                    'name' => $name,
                ];
            }
        }
        return ArrayHelper::map($color_list,'id','name');
    }
}
