<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%model_var_rotatsion}}".
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
 * @property int $model_view_id
 * @property int $model_types_id
 * @property int $brend_id
 * @property int $musteri_id
 *
 * @property ModelOrderItemsRotatsion[] $modelOrderItemsRotatsions
 * @property Brend $brend
 * @property Musteri $musteri
 * @property ModelVarRotatsionColors[] $modelVarRotatsionColors
 * @property bool $imageOne
 * @property ModelVarRotatsionRelAttach[] $modelVarRotatsionRelAttaches
 */
class ModelVarRotatsion extends \app\modules\base\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_var_rotatsion}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['code','required'],
            ['code','unique'],
            [['width', 'height'], 'number'],
            [['add_info'], 'string'],
            [['status', 'created_by', 'created_at', 'updated_at', 'model_view_id', 'model_types_id', 'brend_id', 'musteri_id'], 'integer'],
            [['name', 'image'], 'string', 'max' => 255],
            [['code', 'desen_no'], 'string', 'max' => 30],
            [['brend_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brend::className(), 'targetAttribute' => ['brend_id' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => Musteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
        ];
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
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'code' => Yii::t('app', 'Code'),
            'desen_no' => Yii::t('app', 'Desen No'),
            'image' => Yii::t('app', 'Image'),
            'model_view_id' => Yii::t('app', 'Model View ID'),
            'model_types_id' => Yii::t('app', 'Model Types ID'),
            'brend_id' => Yii::t('app', 'Brend ID'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'attachments' => Yii::t('app', 'Attachments'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrderItemsRotatsions()
    {
        return $this->hasMany(ModelOrderItemsRotatsion::className(), ['model_var_rotatsion_id' => 'id']);
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
    public function getMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'musteri_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarRotatsionColors()
    {
        return $this->hasMany(ModelVarRotatsionColors::className(), ['model_var_rotatsion_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarRotatsionRelAttaches()
    {
        return $this->hasMany(ModelVarRotatsionRelAttach::className(), ['model_var_rotatsion_id' => 'id']);
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
    public function getImageOne()
    {
        $image = ModelVarRotatsionRelAttach::find()->where(['is_main'=>1,'model_var_rotatsion_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        if(!$image){
            $image = ModelVarRotatsionRelAttach::find()->where(['model_var_rotatsion_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        }
        if ($image){
            $attachment = $image->attachment['path'];
            if(!empty($attachment)){
                return $attachment;
            }
        }
        return false;
    }
}
