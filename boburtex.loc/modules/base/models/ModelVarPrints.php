<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%model_var_prints}}".
 *
 * @property int $id
 * @property string $name
 * @property double $width
 * @property double $height
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property string $code
 * @property string $desen_no
 * @property string $image
 *
 * @property ModelVarPrintRelAttach[] $modelVarPrintRelAttaches
 * @property ModelVarPrintsColors[] $modelVarPrintsColors
 * @property ModelVarPrintsRel[] $modelVarPrintsRels
 * @property Brend $brend
 * @property  Musteri $musteri
 * @property int $brend_id [int(11)]
 * @property int $musteri_id [bigint(20)]
 */
class ModelVarPrints extends BaseModel
{
    const MODELSLIST_CODE = 'code';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_var_prints}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::MODELSLIST_CODE] = ['code'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brend_id','musteri_id', 'base_details_list_id', 'models_list_id'],'integer'],
            ['code','unique'],
//            ['code','required'],
            ['code', 'integer', 'on' => self::MODELSLIST_CODE],
            [['width', 'height'], 'number'],
            [['add_info'], 'string'],
            [['status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'image'], 'string', 'max' => 255],
            [['code', 'desen_no'], 'string', 'max' => 30],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => Musteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['base_details_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseDetailLists::className(), 'targetAttribute' => ['base_details_list_id' => 'id']],
            [['brend_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brend::className(), 'targetAttribute' => ['brend_id' => 'id']],
            [['models_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['models_list_id' => 'id']]
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->image = $this->imageOne;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'width' => Yii::t('app', 'Width'),
            'height' => Yii::t('app', 'Height'),
            'add_info' => Yii::t('app', 'Add info'),
            'brend_id' => Yii::t('app', 'Brend'),
            'musteri_id' => Yii::t('app', 'Buyurtmachi'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'code' => Yii::t('app', 'Code'),
            'desen_no' => Yii::t('app', 'Desen No'),
            'image' => Yii::t('app', 'Image'),
            'base_details_list_id' => Yii::t('app', 'Base Details'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMusteri()
    {
        return $this->hasOne(Musteri::className(),['id' => 'musteri_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrend()
    {
        return $this->hasOne(Brend::className(),['id' => 'brend_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarPrintRelAttaches()
    {
        return $this->hasMany(ModelVarPrintRelAttach::className(), ['model_var_print_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseDetailsList()
    {
        return $this->hasOne(BaseDetailLists::class, ['id' => 'base_details_list_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarPrintsColors()
    {
        return $this->hasMany(ModelVarPrintsColors::className(), ['model_var_prints_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarPrintsRels()
    {
        return $this->hasMany(ModelVarPrintsRel::className(), ['model_var_prints_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrderItemsPrints()
    {
        return $this->hasMany(ModelOrderItemsPrints::className(), ['model_var_prints_id' => 'id']);
    }
    /**
     * @return bool|mixed
     */
    public function getImageOne()
    {
        $image = ModelVarPrintRelAttach::find()->where(['is_main'=>1,'model_var_print_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        if(!$image){
            $image = ModelVarPrintRelAttach::find()->where(['model_var_print_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        }
        if (!empty($image)){
            $attachment = $image->attachment['path'];
            if(!empty($attachment)){
                return $attachment;
            }
        }
        return false;
    }

    public function getBrandList($key = null)
    {
        if($key){
            $br = Brend::findOne(['id' => $key]);
            if($br !== null){
                return $br->name;
            }
            return null;
        }
        $brands = Brend::find()->asArray()->all();
        return ArrayHelper::map($brands,'id','name');
    }

    public function getMusteriList($key = null)
    {
        if($key){
            $br = Musteri::findOne(['id' => $key]);
            if($br !== null){
                return $br->name;
            }
            return null;
        }
        $brands = Musteri::find()->asArray()->all();
        return ArrayHelper::map($brands,'id','name');
    }
    public static function getListMap() {
        $prints = static::find()
            ->select(['id', 'code'])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->andWhere(['not', ['code' => null]])
            ->andWhere(['not', ['code' => '']])
            ->asArray()
            ->all();

        return ArrayHelper::map($prints, 'id', 'code');
    }

    /**
     * @params ArrayHelpers
     * */
    public static function getMapList()
    {
        return ArrayHelper::map(self::find()->all(), 'id', function($m){
            $width = '';
            $height = '';
            $name = '';
            if($m['width']!=null && !empty($m['width'])){
                $width = $m['width'].'mm';
            }
            if($m['height']!=null && !empty($m['height'])){
                $height = $m['height'].'mm';
            }
            if($m['name']!=null && !empty($m['name'])){
                $name = $m['name'];
            }
            $all = $name.' '.$width.' '.$height;
            return $all;
        });
    }
}
