<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%model_var_stone}}".
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
 * @property string $image
 * @property string $desen_no
 * @property int $model_view_id
 * @property int $model_types_id
 * @property int $brend_id
 * @property int $musteri_id
 *
 * @property ModelOrderItemsStone[] $modelOrderItemsStones
 * @property Brend $brend
 * @property Musteri $musteri
 * @property ModelVarStoneRelAttach[] $modelVarStoneRelAttaches
 */
class ModelVarStone extends \app\modules\base\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_var_stone}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            ['code','required'],
//            ['code','unique'],
            [['width', 'height'], 'number'],
            [['add_info'], 'string'],
            [['status',  'base_details_list_id', 'models_list_id', 'created_by', 'created_at', 'updated_at', 'model_view_id', 'model_types_id', 'brend_id', 'musteri_id'], 'integer'],
            [['name', 'image'], 'string', 'max' => 255],
            [['code', 'desen_no'], 'string', 'max' => 30],
            [['brend_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brend::className(), 'targetAttribute' => ['brend_id' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => Musteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['models_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['models_list_id' => 'id']],
            [['base_details_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseDetailLists::className(), 'targetAttribute' => ['base_details_list_id' => 'id']],
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
            'image' => Yii::t('app', 'Image'),
            'desen_no' => Yii::t('app', 'Desen No'),
            'model_view_id' => Yii::t('app', 'Model View ID'),
            'model_types_id' => Yii::t('app', 'Model Types ID'),
            'brend_id' => Yii::t('app', 'Brend ID'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'base_details_list_id' => Yii::t('app', 'Base Details'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrderItemsStones()
    {
        return $this->hasMany(ModelOrderItemsStone::className(), ['model_var_stone_id' => 'id']);
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
    public function getModelVarStoneRelAttaches()
    {
        return $this->hasMany(ModelVarStoneRelAttach::className(), ['model_var_stone_id' => 'id']);
    }
    public function getImageOne()
    {
        $image = ModelVarStoneRelAttach::find()->where(['is_main'=>1,'model_var_stone_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        if(!$image){
            $image = ModelVarStoneRelAttach::find()->where(['model_var_stone_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        }
        if ($image){
            $attachment = $image->attachment['path'];
            if(!empty($attachment)){
                return $attachment;
            }
        }
        return false;
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
