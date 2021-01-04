<?php

namespace app\modules\base\models;

use app\models\Users;
use app\modules\hr\models\HrEmployee;
use Mpdf\Tag\Hr;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\User;

/**
 * This is the model class for table "base_patterns".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $brend_id
 * @property int $musteri_id
 * @property int $model_type_id
 * @property int $pattern_type
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BasePatternItems[] $basePatternItems
 * @property BasePatternRelAttachment[] $basePatternRelAttachments
 * @property BasePatternRelAttachment[] $basePatternRelFiles
 * @property BasePatternMiniPostal[] $basePatternMiniPostal
 * @property Brend $brend
 * @property ModelTypes $modelType
 * @property Musteri $musteri
 * @property Users $customer
 * @property int $customer_id [bigint(20)]
 * @property int $counter [int(11)]
 * @property int $constructor_id [bigint(20)]
 * @property int $designer_id [bigint(20)]
 */
class BasePatterns extends BaseModel
{
    public $path;
    public $files;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_patterns';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brend_id','customer_id','constructor_id','designer_id','counter','musteri_id', 'model_type_id', 'pattern_type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255],
            ['path','safe'],
            [['name'],'required'],
            [['name'],'unique'],
            [['brend_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brend::className(), 'targetAttribute' => ['brend_id' => 'id']],
            [['model_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelTypes::className(), 'targetAttribute' => ['model_type_id' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => Musteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['constructor_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['constructor_id' => 'id']],
            [['designer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['designer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'brend_id' => Yii::t('app', 'Brend ID'),
            'customer_id' => Yii::t('app', 'Buyurtmachi'),
            'designer_id' => Yii::t('app', 'Dizayner'),
            'constructor_id' => Yii::t('app', 'Konstruktor'),
            'counter' => Yii::t('app', 'Tartib raqami'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'model_type_id' => Yii::t('app', 'Model Type ID'),
            'pattern_type' => Yii::t('app', 'Pattern Type'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }


    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->code = (string)($this->code);
            return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasePatternItems()
    {
        return $this->hasMany(BasePatternItems::className(), ['base_pattern_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasePatternMiniPostal()
    {
        return $this->hasMany(BasePatternMiniPostal::className(), ['base_patterns_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasePatternRelAttachments()
    {
        return $this->hasMany(BasePatternRelAttachment::class, [
            'base_pattern_id' => 'id'
        ]);
    }
    public function getBasePatternRelFiles()
    {
        return $this->hasMany(BasePatternRelAttachment::className(), ['base_pattern_id' => 'id'])->where(['type'=>2]);
    }
    public function getFileList(){
        $data = $this->basePatternRelFiles;

        if(!empty($data)){
            $images = [];
            foreach ($data as $key){
                $images[] = "/web/".$key->attachment['path'];
            }
            return $images;
        }
        return [];
    }
    public function getFileConfigList(){
        $data = $this->basePatternRelFiles;
        if(!empty($data)){
            $images = [];
            $i = 0;
            foreach ($data as $key){
                $images[] = [
                    'caption' => "{$key->attachment['name']}",
                    'key' => $key['id'],
                    'extra' => ['id' => $key->attachment['id']],
                    'type' => $key->attachment['extension'],
//                    'filetype' => $key->$this->attachment['type'].'/'.$key->$this->attachment['extension'],
                    'downloadUrl' => "/web/".$key->attachment['path'],
                    'size' => $key->attachment['size']
                ];
                $i++;
            }
            return $images;
        }
        return [];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConstructor()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'constructor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesigner()
    {
        return $this->hasOne(Users::className(), ['id' => 'designer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrend()
    {
        return $this->hasOne(Brend::className(), ['id' => 'brend_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Users::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConstruct()
    {
        return $this->hasOne(HrEmployee::class, ['id' => 'constructor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelType()
    {
        return $this->hasOne(ModelTypes::className(), ['id' => 'model_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'musteri_id']);
    }

    /**
     * @param string $model
     * @param bool $key
     * @return array|mixed|null
     */
    public function getEntityList($model = 'Brend', $key = null){

        $brands = $model::find();
        if(!empty($key)){
            $res = $brands->where(['id' => $key])->asArray()->one();
            if(!empty($res)){
                return $res['name'];
            }
            return null;
        }
        $res = $brands->asArray()->orderBy(['name' => SORT_ASC])->all();

        return ArrayHelper::map($res,'id','name');
    }

    /**
     * @param null $key
     * @param array $token
     * @return array|mixed|null
     */
    public function getCustomerList($key = null, $token = []){
        $users = Users::find();
        if(!empty($key)){
            $out = $users->where(['id' => $key])->asArray()->one();
            if(!empty($out)){
                if(!empty($m['lavozimi'])){
                    return $m['user_fio']." - ".$m['lavozimi'];
                }
                return $out['user_fio'];
            }
            return null;
        }
        $out = $users->asArray()->all();
        return ArrayHelper::map($out,'id',function($m){
            $data = $m['user_fio'];
            if(!empty($m['lavozimi'])){
                $data .= " - ".$m['lavozimi'];
            }
            if(!empty($m['add_info'])){
                $data .= " - ".$m['add_info'];
            }
            return $data;
        });
    }

    /**
     * Buyurtmalarni faqat tasdiqlangan variantlarini korish
     * */
    public function getSuccessOrders()
    {
        $result = ArrayHelper::map(ModelOrdersVariations::find()
            ->where(['status' => ModelOrders::STATUS_SAVED])
            ->andWhere(['base_patterns_id' => NULL])
            ->asArray()
            ->all(),'model_orders_id', function ($m){
            $name = ModelOrders::findOne($m['model_orders_id']);
            return $name->doc_number.' - documentni '.$m['variant_no'].' - varianti';
        });
        return $result;
    }
    
    /**
     * qoliplarni nomini qaytaradi
     * */
    public function getPatterns($id)
    {
        $model = BasePatterns::find()
            ->where(['id' => $id])
            ->asArray()
            ->all();
        return ArrayHelper::map($model, 'id', function($m){
            $brend = Brend::findOne($m['id']);
            return $brend->name.' '.$m['name'];
        });
    }

    public function getImages($images)
    {
        if(!empty($images)){
            $attachments_id = [];
            foreach($images as $k => $v){
                $attachments_id[] = $v['attachment_id'];
            }
            $img = Attachments::find()
                ->where(['in', 'id', $attachments_id])
                ->asArray()
                ->all();
            return $img;
        }
    }

    /** O'lchamlar bilan ishlash */
    public function getSizes()
    {
        $size = ArrayHelper::map(Size::find()->asArray()->all(),'id', 'name');
        return $size;
    }
}
