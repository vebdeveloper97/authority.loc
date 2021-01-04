<?php

namespace app\modules\bichuv\models;

use app\modules\base\models\ModelsAcs;
use app\modules\toquv\models\Unit;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_acs".
 *
 * @property int $id
 * @property string $sku
 * @property string $name
 * @property int $property_id
 * @property int $unit_id
 * @property string $barcode
 * @property string $add_info
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property BichuvAcsProperty $property
 * @property Unit $unit
 * @property BichuvAcsAttachment[] $bichuvAcsAttachments
 * @property ModelsAcs[] $modelsAcs
 */
class BichuvAcs extends BaseModel
{
    public $barcode_quantity;

    public $counter = 1;
    public $image;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_acs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_id', 'unit_id', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            //[['sku', 'name', 'barcode', 'unit_id'], 'required'],
            [['sku', 'barcode'], 'unique'],
            [['add_info'], 'string'],
            [['stock_limit_min', 'stock_limit_max'], 'safe'],
            [['sku', 'barcode'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 200],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvAcsProperty::className(), 'targetAttribute' => ['property_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::className(), 'targetAttribute' => ['unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sku' => Yii::t('app', 'Sku'),
            'name' => Yii::t('app', 'Name'),
            'property_id' => Yii::t('app', 'Property ID'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'barcode' => Yii::t('app', 'Barcode'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'pr' => Yii::t('app', 'Created By'),
            'propertyName' => Yii::t('app', 'Property Name'),
            'unitName' => Yii::t('app', 'Unit Name'),
            'barcode_quantity' => Yii::t('app', 'Quantity'),
            'stock_limit_min' => Yii::t('app', 'stock_min_limit'),
            'stock_limit_max' => Yii::t('app', 'stock_max_limit'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(BichuvAcsProperty::className(), ['id' => 'property_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     * */
    public function getProperties()
    {
        return $this->hasMany(BichuvAcsProperties::className(), ['bichuv_acs_id' => 'id']);
    }

    public function getPropertyName()
    {
        return $this->property->name;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }

    public function getUnitName()
    {
        return $this->unit->name;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvAcsAttachments()
    {
        return $this->hasMany(BichuvAcsAttachment::className(), ['bichuv_acs_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsAcs()
    {
        return $this->hasMany(ModelsAcs::className(), ['bichuv_acs_id' => 'id']);
    }

    public static function getAllProperties()
    {
        $ne = BichuvAcsProperty::find()->all();
        return ArrayHelper::map($ne,'id','name');
    }

    public static function getAllUnits()
    {
        $ne = \app\modules\base\models\Unit::find()->all();
        return ArrayHelper::map($ne,'id','name');
    }
    public function upload($folder,$imageFile)
    {
        $session = explode('.', $imageFile->name);
        $session = $session[0];
        $directory = 'uploads' . DIRECTORY_SEPARATOR . 'acs' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR;
        if (!is_dir($directory)) {
            \yii\helpers\FileHelper::createDirectory($directory);
        }

        if ($imageFile) {
            $uid = date("Y-m-d-H-i-s")."-".$session;
            $fileName = $uid . '.' . $imageFile->extension;
            $filePath = $directory . $fileName;
            $ismain = BichuvAcsAttachment::find()->where(['bichuv_acs_id'=>$this->id,'isMain'=>1])->one();
            if ($imageFile->saveAs($filePath)) {
                $path = 'uploads/acs/'.$folder.'/'.$fileName;
                $type = explode("/",$imageFile->type);
                $image = new BichuvAcsAttachment();
                $image->setAttributes([
                    'bichuv_acs_id' => $this->id,
                    'name' => $imageFile->name,
                    'size' => $imageFile->size,
                    'extension' => $imageFile->extension,
                    'type' => $type[0],
                    'path' => $path,
                    'isMain' => (!$ismain)?1:0
                ]);
                $image->save();
            }
        }
        return false;
    }

    /**
     * @param $folder
     * @param $imageFile
     * @return bool|int
     * @throws \yii\base\Exception
     */
    public function uploadBase64($folder, $imageFile)
    {
        if ($imageFile) {
            $img = $imageFile;
            $img = explode(',', $img);
            $data = base64_decode($img[1]);
            $ini = substr($img[0], 11);
            $type = explode(';', $ini)[0];
            switch ($type){
                case 'jpeg':
                case 'gif':
                case 'jpg':
                case 'png':
                case 'bmp':
                case 'jfif':
                    break;
                default:
                    return false;
            }
            $directory = 'uploads/acs/' . $folder . '/' . $type;
            if (!is_dir($directory)) {
                \yii\helpers\FileHelper::createDirectory($directory);
            }
            $uid = uniqid(date('d.m.Y-H.i.s-'));
            $fileName = $uid . '.' . $type;
            $filePath = $directory . '/' . $fileName;
            if ($success = file_put_contents($filePath, $data)) {
                $ismain = BichuvAcsAttachment::find()->where(['bichuv_acs_id' => $this->id, 'isMain' => 1])->one();
                if ($success) {
                    $path = '/web/uploads/acs/' . $folder . '/' . $type . '/' . $fileName;
                    $image = new BichuvAcsAttachment();
                    $image->setAttributes([
                        'bichuv_acs_id' => $this->id,
                        'name' => $fileName,
                        'size' => $success,
                        'extension' => $type,
                        'type' => 'image',
                        'path' => $path,
                        'isMain' => (!$ismain) ? 1 : 0
                    ]);
                    $image->save();
                    return 1;
                }
            }
        }
        return false;
    }
    public function getImageOne()
    {
        $image = BichuvAcsAttachment::find()->where(['isMain'=>1,'bichuv_acs_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        if(!$image){
            $image = BichuvAcsAttachment::find()->where(['bichuv_acs_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        }
        if ($image){
            $attachment = $image['path'];
            if(!empty($attachment)){
                return $attachment;
            }
        }
        return false;
    }

    // bichuv properties list malumotlarni select2 ga chiqarish
    public function getAllData($name=null)
    {
        if($name === null) {
            $result = ArrayHelper::map(BichuvAcsPropertyList::find()
                ->all(), 'id', 'name');
            return $result;
        }
    }

    // bichuv acs save
    public function acsSave($data)
    {
        if(!$this->validate())
            return false;

        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        try{
            $this->status = BichuvAcs::STATUS_ACTIVE;
            if($this->save()){
                $saved = true;
                if(!empty($data)){
                    foreach ($data as $item){
                        $property = new BichuvAcsProperties();
                        $property->bichuv_acs_id = $this->id;
                        $property->bichuv_acs_property_list_id = $item['bichuv_acs_property_list_id'];
                        $property->value = $item['value'];
                        $property->status = BichuvAcsProperties::STATUS_ACTIVE;
                        if($property->save()){
                            $saved = true;
                            unset($property);
                        }
                        else{
                            $saved = false;
                            break;
                        }
                    }
                }
                else{
                    $saved = false;
                }
            }
            else{
                $saved = false;
            }

            if($saved){
                $transaction->commit();
                return true;
            }
            else{
                $transaction->rollBack();
                return true;
            }
        }
        catch(\Exception $e){
            Yii::info('error data '.$e->getMessage(),'save');
        }
    }

    public function acsSaveAjax($data)
    {
        if(!$this->validate())
            return false;

        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        try{
            $this->status = BichuvAcs::STATUS_ACTIVE;
            if($this->save()){
                $saved = true;
                if(!empty($data)){
                    foreach ($data as $item){
                        $property = new BichuvAcsProperties();
                        $property->bichuv_acs_id = $this->id;
                        $property->bichuv_acs_property_list_id = $item['bichuv_acs_property_list_id'];
                        $property->value = $item['value'];
                        $property->status = BichuvAcsProperties::STATUS_ACTIVE;
                        if($property->save()){
                            $saved = true;
                            unset($property);
                        }
                        else{
                            $saved = false;
                            break;
                        }
                    }
                }
                else{
                    $saved = false;
                }
            }
            else{
                $saved = false;
            }

            if($saved){
                $transaction->commit();
                return $this;
            }
            else{
                $transaction->rollBack();
                return false;
            }
        }
        catch(\Exception $e){
            Yii::info('error data '.$e->getMessage(),'save');
        }
    }

    // bichuv update
    public function acsUpdate($data)
    {
        if(!$this->validate())
            return false;

        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        try{
            $this->status = BichuvAcs::STATUS_ACTIVE;
            if($this->save()){
                $saved = true;

                $properties = BichuvAcsProperties::deleteAll([
                    'bichuv_acs_id' => $this->id
                ]);

                if(!empty($data)){
                    foreach ($data as $item){
                        $property = new BichuvAcsProperties();
                        $property->bichuv_acs_id = $this->id;
                        $property->bichuv_acs_property_list_id = $item['bichuv_acs_property_list_id'];
                        $property->value = $item['value'];
                        $property->status = BichuvAcsProperties::STATUS_ACTIVE;
                        if($property->save()){
                            $saved = true;
                            unset($property);
                        }
                        else{
                            $saved = false;
                            break;
                        }
                    }
                }
                else{
                    $saved = false;
                }
            }
            else{
                $saved = false;
            }

            if($saved){
                $transaction->commit();
                return true;
            }
            else{
                $transaction->rollBack();
                return true;
            }
        }
        catch(\Exception $e){
            Yii::info('error data '.$e->getMessage(),'save');
        }
    }

    public function showView($model,$id)
    {
        $result = BichuvAcsProperties::find()
            ->where(['bichuv_acs_id' => $id])
            ->asArray()
            ->all();
        $array = [];
        foreach($result as $key => $val){
            $res = BichuvAcsPropertyList::findOne($val['bichuv_acs_property_list_id']);
            if(isset($array[$res->name])){
                $array[$res->name][] = $val['value'];
            }
            else{
                $array[$res->name][] = $val['value'];
            }
        }

        $key_name = array_keys($array);
        $str = '';
        foreach ($key_name as $row) {
            if(is_array($array[$row])){
                foreach ($array[$row] as $item){
                    $str .= "<span style='color: #b37400'>".$item."</span>".' ';
                }
            }

        }
        $str = rtrim($str, ' ');
        return "<span class='text-warning'>".$model."</span> ".$str;
    }

    public function getUnitAll()
    {
        return ArrayHelper::map(Unit::find()->all(), 'id', 'name');
    }
}
