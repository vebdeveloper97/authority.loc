<?php

namespace app\modules\toquv\models;

use app\models\Users;
use app\modules\base\models\ModelOrdersPlanning;
use app\modules\base\models\ModelsRawMaterials;
use app\modules\base\models\ModelsToquvAcs;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "toquv_raw_materials".
 *
 * @property int $id
 * @property string $name
 * @property string $name_ru
 * @property int $raw_material_type_id
 * @property int $created_by
 * @property string $code
 * @property int $status
 * @property int $color
 * @property int $created_at
 * @property int $updated_at
 * @property int $type
 * @property string $density
 *
 * @property ModelOrdersPlanning[] $modelOrdersPlannings
 * @property ModelsRawMaterials[] $models   RawMaterials
 * @property ToquvPricing[] $toquvPricings *
 * @property ToquvRawMaterialConsist[] $toquvRawMaterialConsists
 * @property ToquvRawMaterialIp[] $toquvRawMaterialIps
 * @property ToquvRawMaterialAttachments[] $toquvRawMaterialAttachments
 * @property ToquvRawMaterialType $rawMaterialType
 * @property ToquvRawMaterialColor $rawMaterialColor
 * @property ToquvRmOrder[] $toquvRmOrders
 * @property mixed $createdUser
 * @property mixed $allFabricTypes
 * @property mixed $rawMaterialType2Name
 * @property mixed $allToquvIpTypes
 * @property ActiveQuery $modelsRawMaterials
 * @property mixed $allToquvNeTypes
 * @property mixed $rawMaterialName
 * @property mixed $userName
 * @property bool $imageOne
 * @property mixed $allToquvThreadTypes
 * @property ToquvServicePricing[] $toquvServicePricings
 * @property int $color_id [int(11)]
 */
class ToquvRawMaterials extends BaseModel
{
    const MATO = 1;
    const ACS  = 2;

    /** Malumotlarni modellarni olish */
    public $qty;
    public $add_info;
    public $sizes;
    public $bichuv_acs_id;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_raw_materials';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','raw_material_type_id'], 'required'],
            ['code','unique'],
            ['code', 'default', 'value' => function ($model, $attribute) {
                return substr($model->name, 0, 1)
                    . '-'
                    . date('d.m.Y');
            }],
            [['raw_material_type_id', 'type', 'color_id', 'created_by', 'status', 'created_at', 'updated_at', 'type'], 'integer'],
            [['density'], 'number'],
            [['name', 'name_ru'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 50],
            [['raw_material_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRawMaterialType::className(), 'targetAttribute' => ['raw_material_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app','ID'),
            'name' => Yii::t('app','Name'),
            'name_ru' => Yii::t('app','Name Ru'),
            'raw_material_type_id' => Yii::t('app','Raw Material Type ID'),
            'created_by' => Yii::t('app','Created By'),
            'code' => Yii::t('app','Code'),
            'status' => Yii::t('app','Status'),
            'color_id' => Yii::t('app','Color ID'),
            'created_at' => Yii::t('app','Created At'),
            'updated_at' => Yii::t('app','Updated At'),
            'toquvRawMaterialConsists' => Yii::t('app','Raw Material Consists'),
            'toquvRawMaterialIps' => Yii::t('app','Raw Material Ips'),
            'rawMaterialName' => Yii::t('app','Raw Material Type ID'),
            'rawMaterialConsist' => Yii::t('app','Raw Material Consists'),
            'rawMaterialIp' => Yii::t('app','Raw Material Ips'),
            'type' => Yii::t('app','Type'),
            'density' => Yii::t('app', '1 kg necha dona?'),
        ];
    }
    public static function getTypeList($key = null){
        $result = [
            self::MATO   => Yii::t('app','Mato'),
            self::ACS => Yii::t('app','Aksessuar')
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrdersPlannings()
    {
        return $this->hasMany(ModelOrdersPlanning::className(), ['toquv_raw_materials_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelsRawMaterials()
    {
        return $this->hasMany(ModelsRawMaterials::className(), ['rm_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToquvRawMaterialAttachments()
    {
        return $this->hasMany(ToquvRawMaterialAttachments::className(), ['toquv_raw_materials_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    /*public function getToquvPricings()
    {
        return $this->hasMany(ToquvPricing::className(), ['mato_id' => 'id']);
    }*/
    /**
     * @return ActiveQuery
     */
    public function getToquvRawMaterialConsists()
    {
        return $this->hasMany(ToquvRawMaterialConsist::className(), ['raw_material_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToquvRawMaterialIps()
    {
        return $this->hasMany(ToquvRawMaterialIp::className(), ['toquv_raw_material_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRawMaterialType()
    {
        return $this->hasOne(ToquvRawMaterialType::className(), ['id' => 'raw_material_type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(ToquvRawMaterialColor::className(), ['id' => 'color_id']);
    }


    public function getRawMaterialType2Name()
    {
        return $this->rawMaterialType->name;
    }

    public function getMaterialType($type=null)
    {
        $list = ToquvRawMaterialType::find();
        if($type){
            $list = $list->where(['type'=>$type]);
        }
        $list = $list->all();
        return ArrayHelper::map($list,'id','name');
    }
    public static function getMaterialTypeSearch($type=null)
    {
        $list = ToquvRawMaterialType::find();
        if($type){
            $list = $list->where(['type'=>$type]);
        }
        $list = $list->all();
        return ArrayHelper::map($list,'name','name');
    }
    //Mato tarkibi
    public function getRawMaterialConsist($lang = 'ru')
    {
        $consist = $this->toquvRawMaterialConsists;
        $name = '';
        foreach ($consist as $key){
            $name .= $key->getFullName($lang);
        }
        return substr($name, 0,-2);
    }
    //Ip
    public function getRawMaterialIp($comma=',',$br=false)
    {
        $consist = $this->toquvRawMaterialIps;
        $name = '';
        $count = count($consist)-1;
        foreach ($consist as $num => $key){
            $name .= ($num != $count)?$key->getFullName($comma,$br):$key->getFullName('',$br);
        }
        return $name;
    }

    //Ipliklar
    public function getRawMaterialThread($comma=',',$br=false)
    {
        $consist = $this->toquvRawMaterialIps;
        $name = '';
        $count = count($consist)-1;
        foreach ($consist as $num => $key){
            $name .= ($num != $count)?$key->getThreadNeName().", ":$key->getThreadNeName();
        }
        return $name;
    }

    public function getCreatedUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by']);
    }

    public function getUserName()
    {
        return $this->createdUser->username;
    }
    public function getAllFabricTypes() {
        return FabricTypes::getAllTypes();
    }

    public function getAllToquvIpTypes()
    {
        return ToquvIp::getFullNameAllTypes();
    }
    public function getAllToquvThreadTypes()
    {
        return ToquvThread::getFullNameAllTypes();
    }
    public function getRawMaterialName()
    {
        return $this->rawMaterialType->name;
    }
    public function getAllToquvNeTypes()
    {
        return ToquvNe::getFullNameAllTypes();
    }
    public static function getNarx($id,$kg=0)
    {
        $consist = ToquvRawMaterialConsist::find()->where(['fabric_type_id' => 3,'raw_material_id' => $id])->one();
        $service = ($consist)?0.35:0.25;
        $model = ToquvRawMaterialIp::find()->where(['toquv_raw_material_id'=>$id])->all();
        $price = 0;
        foreach($model as $key) {
            $price += (!empty($kg) && $kg > 0) ? $kg * $key->percentage / 100 * $key->price : $key->price;
        }
        $price = ($kg>0)?$kg*$service+$price:$service+$price;
        return $price;
    }
    public static function getAllColors()
    {
        $list = ToquvRawMaterialColor::find()->asArray()->all();
        return ArrayHelper::map($list,'id', 'name');
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
                    (SELECT a.path FROM attachments a 
                        LEFT JOIN toquv_raw_material_attachments trma on a.id = trma.attachment_id
                        LEFT JOIN toquv_raw_materials trm on trma.toquv_raw_materials_id = trm.id
                        WHERE trma.is_main = 1 AND trm.id = raw.id) image,
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
            $image = (!empty($item['image']))?"<img src='/web/{$item['image']}' style='height: 20px;width:auto;padding:0'>":"";
            $ip[$item['id']]['ip'] .= " (".$item['ne']."-".$item['thread'] . ")";
            $res['list'][$item['tname']][$item['id']] = $image. " {$color} <b>". $item['rname'] . " - " . $item['tname'] ."</b>" . $ip[$item['id']]['ip'];
            $res['options'][$item['id']] = [
                'data-image' => $item['image']
            ];
        }
        return $res;
    }

    public static function getMaterials($entity_type=null, $raw_id = null, $asArray = true)
    {
        $query = static::find()
            ->alias('raw')
            ->select([
                'raw.id',
                'raw.code',
                'raw.name as rname',
                'type.name as tname',
                'ne_id',
                'tn.name ne',
                'tt.name thread'
            ])
            ->leftJoin(['type' => 'raw_material_type'], 'raw.raw_material_type_id = type.id')
            ->leftJoin(['trmi' => 'toquv_raw_material_ip'], 'raw.id = trmi.toquv_raw_material_id')
            ->leftJoin(['tn' => 'toquv_ne'], 'trmi.ne_id = tn.id')
            ->leftJoin(['tt' => 'toquv_thread'], 'trmi.thread_id = tt.id')
            ->andFilterWhere(['raw.type' => $entity_type])
            ->andFilterWhere(['raw.id' => $raw_id])
            ->asArray($asArray);

        return $query->all();
    }

    public static function getMaterialById($rawId, $entityType = null, $asArray = true) {
        $materials = self::getMaterials($entityType, $rawId, $asArray);
        return isset($materials[0]) ? $materials[0] : null;
    }

    public static function getListMap($entity_type=null, $raw_id = null) {
        $materials = self::getMaterials($entity_type, $raw_id);
        return ArrayHelper::map($materials, 'id', function ($item) {
            return /*$item['code']
                . " - "
                . */$item['rname']
                . " - "
                . $item['tname']
                . $item['ne']
                . "-"
                .$item['thread'];
        });
    }

    public function getImageOne()
    {
        $image = ToquvRawMaterialAttachments::find()->where(['is_main'=>1,'toquv_raw_materials_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        if(!$image){
            $image = ToquvRawMaterialAttachments::find()->where(['toquv_raw_materials_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        }
        if ($image){
            $attachment = $image->attachment['path'];
            if(!empty($attachment)){
                return $attachment;
            }
        }
        return false;
    }

    public function getModelsToquvAcs()
    {
        return $this->hasMany(ModelsToquvAcs::class,['toquv_acs_id' => 'id']);
    }
}
