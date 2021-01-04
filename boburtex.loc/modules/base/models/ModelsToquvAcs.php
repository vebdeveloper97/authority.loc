<?php

namespace app\modules\base\models;

use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\wms\models\WmsMatoInfo;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "models_toquv_acs".
 *
 * @property int $id
 * @property int $models_list_id
 * @property int $models_var_id
 * @property int $toquv_acs_id
 * @property string $qty
 * @property string $add_info
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property ModelsList $modelsList
 * @property ModelsVariations $modelsVar
 * @property  $wmsMatoInfo
 */
class ModelsToquvAcs extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public $sizes;
    public $wms_color_id;
    public $wms_desen_id;
    public $pus_fine_id;
    public $en;
    public $gramaj;
    public $ne_id;

    public static function tableName()
    {
        return 'models_toquv_acs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['models_list_id', 'models_var_id', 'toquv_acs_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'wms_mato_info_id'], 'integer'],
            [['qty'], 'number'],
            [['add_info'], 'string'],
            [['models_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['models_list_id' => 'id']],
            [['toquv_acs_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRawMaterials::className(), 'targetAttribute' => ['toquv_acs_id' => 'id']],
            [['models_var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['models_var_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'models_list_id' => Yii::t('app', 'Models List ID'),
            'models_var_id' => Yii::t('app', 'Models Var ID'),
            'toquv_acs_id' => Yii::t('app', 'Toquv Acs ID'),
            'qty' => Yii::t('app', 'Qty'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
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
    public function getModelsList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'models_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsVar()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'models_var_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsToquvAcsSizes()
    {
        return $this->hasMany(ModelsToquvAcsSizes::className(), ['models_toquv_acs_id' => 'id']);
    }

    public function getSizeList($is_view=false){
        if($is_view){
            $sizes = ModelsToquvAcsSizes::find()->select('size_id,size.name')->joinWith('size')->where(['models_acs_id'=>$this->id])->asArray()->all();
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
        $sizes = ModelsToquvAcsSizes::find()->select('size_id')->where(['models_toquv_acs_id'=>$this->id])->asArray()->all();
        $list = [];
        if(!empty($sizes)){
            return ArrayHelper::getColumn($sizes,'size_id');
        }
        return $list;
    }

    public function getToquvRawMaterials()
    {
        return $this->hasOne(ToquvRawMaterials::class,['id' => 'toquv_acs_id']);
    }

    public function getWmsMatoInfo()
    {
        return $this->hasOne(WmsMatoInfo::class, ['id' => 'wms_mato_info_id'])->joinWith('toquvRawMaterials');
    }

    public static function getListWithType($type)
    {
        $list = ToquvRawMaterials::find()->select(['id','name','code'])->where(['toquv_raw_materials.raw_material_type_id'=>$type])->asArray()->all();
        $res = [];
        if(!empty($list)){
            foreach ($list as $item) {
                $res['list'][$item['id']] = $item['name'];
                $res['option'][$item['id']] = [
                    'data-code' => $item['code']
                ];
            }
        }
        return $res;
    }
    public function getData($data)
    {
        foreach($data as $k => $v){
            $result = WmsMatoInfo::findOne([
                'toquv_raw_materials_id' => $v['toquv_acs_id'],
                'type' => ToquvRawMaterials::ACS
            ]);
            if(!empty($result)){
                $this->wms_color_id = $result->wms_color_id;
                $this->wms_desen_id = $result->wms_desen_id;
                $this->pus_fine_id = $result->pus_fine_id;
                $this->en = $result->en;
                $this->gramaj = $result->gramaj;
            }
        }
    }
}
