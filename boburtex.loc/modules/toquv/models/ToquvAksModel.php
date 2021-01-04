<?php

namespace app\modules\toquv\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%toquv_aks_model}}".
 *
 * @property int $id
 * @property int $trm_id
 * @property string $name
 * @property string $code
 * @property string $image
 * @property double $width
 * @property double $height
 * @property int $qavat
 * @property int $palasa
 * @property double $price
 * @property int $pb_id
 * @property int $musteri_id
 * @property int $color_pantone_id
 * @property int $color_boyoq_id
 * @property int $raw_material_type
 * @property int $color_type
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property string $add_info
 *
 * @property ToquvRawMaterials $trm
 * @property ToquvAksModelItem[] $toquvAksModelItems
 */
class ToquvAksModel extends \app\modules\toquv\models\BaseModel
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%toquv_aks_model}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trm_id', 'qavat', 'palasa', 'pb_id', 'musteri_id', 'color_pantone_id', 'color_boyoq_id', 'raw_material_type', 'color_type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['width', 'height', 'price'], 'number'],
            [['name', 'image', 'add_info'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 40],
            [['trm_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRawMaterials::className(), 'targetAttribute' => ['trm_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'trm_id' => Yii::t('app', 'Aksessuar turi'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'image' => Yii::t('app', 'Image'),
            'width' => Yii::t('app', 'Uzunligi'),
            'height' => Yii::t('app', "Eni"),
            'qavat' => Yii::t('app', 'Qavat'),
            'palasa' => Yii::t('app', 'Palasa'),
            'price' => Yii::t('app', 'Price'),
            'pb_id' => Yii::t('app', 'Pb ID'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'color_pantone_id' => Yii::t('app', 'Color Pantone ID'),
            'color_boyoq_id' => Yii::t('app', 'Color Boyoq ID'),
            'raw_material_type' => Yii::t('app', 'Raw Material Type'),
            'color_type' => Yii::t('app', 'Color Type'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'rawMaterialConsist' => Yii::t('app','Raw Material Consists'),
            'rawMaterialIp' => Yii::t('app','Raw Material Ips'),
            'add_info' => Yii::t('app', 'Add Info'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrm()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'trm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvAksModelItems()
    {
        return $this->hasMany(ToquvAksModelItem::className(), ['toquv_aks_model_id' => 'id'])->orderBy(['indeks'=>SORT_ASC]);
    }

    public static function getAksessuar($options=null)
    {
        $list = ToquvRawMaterials::find()->alias('trm')->with(['toquvRawMaterialIps'=>function($query){
            $query->select(['toquv_raw_material_ip.id','toquv_raw_material_ip.percentage','toquv_raw_material_ip.thread_id','toquv_raw_material_ip.ne_id','toquv_raw_material_ip.toquv_raw_material_id','toquv_raw_material_ip.ne_id'])->with(['toquvNe'=>function($query){
                $query->select(['toquv_ne.id','toquv_ne.name']);
            }])->with(['toquvThread'=>function($query){
                $query->select(['toquv_thread.id','toquv_thread.name']);
            }]);
        }
        ])->where(['trm.type'=>ToquvRawMaterials::ACS])->asArray()->limit(20)->all();
        $res = [];
        if(!empty($list)){
            foreach ($list as $key => $item) {
                $res['list'][$item['id']] = $item['name'];
                $res['ip'][$item['id']]['ip'] = $item['toquvRawMaterialIps'];
                $res['ip'][$item['id']]['type'] = $item['raw_material_type_id'];
            }
        }
        return $res;
    }
    public function uploadBase64($imageFile)
    {
        if ($imageFile) {
            $img = $imageFile;
            $img = explode(',', $img);
            $data = base64_decode($img[1]);
            $ini = substr($img[0], 11);
            $type = explode(';', $ini)[0];
            switch ($type){
                case 'jpeg':
                    break;
                case 'gif':
                    break;
                case 'jpg':
                    break;
                case 'png':
                    break;
                case 'bmp':
                    break;
                case 'jfif':
                    break;
                default:
                    return false;
            }
            $directory = 'uploads/toquvacs/' . $type;
            if (!is_dir($directory)) {
                \yii\helpers\FileHelper::createDirectory($directory);
            }
            $uid = uniqid('a',time());
            $fileName = $uid . '.' . $type;
            $filePath = $directory . '/' . $fileName;
            if ($success = file_put_contents($filePath, $data)) {
                if ($success) {
                    return $filePath;
                }
            }
        }
        return false;
    }
    //Mato tarkibi
    public function getRawMaterialConsist()
    {
        $consist = $this->trm->toquvRawMaterialConsists;
        $name = '';
        foreach ($consist as $key){
            $name .= $key->fullName;
        }
        return substr($name, 0,-2);
    }
    //Ip
    public function getRawMaterialIp($comma=',',$br=false)
    {
        $consist = $this->trm->toquvRawMaterialIps;
        $name = '';
        $count = count($consist)-1;
        foreach ($consist as $num => $key){
            $name .= ($num != $count)?$key->getFullName($comma,$br):$key->getFullName('',$br);
        }
        return $name;
    }
    public static function getColorList($list=false,$q=null,$id=null)
    {
        $sql = "SELECT clp.id,
                clp.name as cname, clp.code as ccode, r, g, b, type.name as tname
                FROM color_pantone as clp
                LEFT JOIN color_panton_type as type ON clp.color_panton_type_id = type.id
                LEFT JOIN toquv_aks_model_item tami ON tami.color_pantone_id = clp.id
                LEFT JOIN toquv_aks_model tam on tami.toquv_aks_model_id = tam.id
                WHERE clp.color_panton_type_id = 3 ";
        if($q){
            $sql .= " AND (clp.name LIKE '%{$q}%' OR clp.code LIKE '%{$q}%')";
        }
        if($id){
            $sql .= " AND (tam.id = {$id})";
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
    public static function getModelList()
    {
        $sql = "SELECT
                    tam.id,
                    tam.code,
                    tam.name as name,
                    tam.image,
                    tam.height,
                    tam.width,
                    tam.qavat,
                    trm.name type
                FROM
                    toquv_aks_model as tam
                LEFT JOIN toquv_raw_materials trm on tam.trm_id = trm.id
        ";
        $acs = Yii::$app->db->createCommand($sql)->queryAll();
        $res = [];
        foreach ($acs as $item) {
            $image = (!empty($item['image'])) ? "<img src='/web/{$item['image']}' style='width:40px;max-height:30px'>":"";
            $res['list'][$item['id']] = $item['code'] ." - <b>". $item['name'] . "  </b>" . $image . " ". $item['type'];
            $res['options'][$item['id']] = [
                'height' => $item['height'],
                'width' => $item['width'],
                'qavat' => $item['qavat'],
            ];
        }
        return $res;
    }
}
