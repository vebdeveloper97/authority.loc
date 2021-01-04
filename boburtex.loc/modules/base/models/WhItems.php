<?php

namespace app\modules\base\models;

use app\components\OurCustomBehavior;
use Yii;
use app\modules\settings\models\Unit;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wh_items".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $category_id
 * @property int $type_id
 * @property int $unit_id
 * @property string $barcode
 * @property int $country_id
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property WhItemCategory $category
 * @property WhItemCountry $country
 * @property WhItemTypes $type
 * @property Unit $unit
 */
class WhItems extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wh_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'type_id', 'unit_id', 'country_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['type_id', 'unit_id', 'name'], 'required'],
            [['add_info'], 'string'],
            [['code', 'barcode'], 'unique'],
            [['code', 'barcode'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => WhItemCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => WhItemCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => WhItemTypes::className(), 'targetAttribute' => ['type_id' => 'id']],
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
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'category_id' => Yii::t('app', 'Category ID'),
            'type_id' => Yii::t('app', 'Type ID'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'barcode' => Yii::t('app', 'Barcode'),
            'country_id' => Yii::t('app', 'Country ID'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(WhItemCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(WhItemCountry::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(WhItemTypes::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }

    /**
     * @param null $cat_id
     * @param bool $array
     * @return array
     */
    public static function getList($cat_id=null,$array=false)
    {
        $list = self::find();
        if($cat_id){
            $list = $list->where(['category_id'=>$cat_id]);
        }
        $list = $list->asArray()->all();
        if($array){
            $res = [];
            if(!empty($list)){
                foreach ($list as $item) {
                    $res[] = [
                        'id' => $item['id'],
                        'name' => $item['name']
                    ];
                }
            }
            return $res;
        }
        return ArrayHelper::map($list,'id','name');
    }
}
