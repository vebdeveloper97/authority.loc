<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "barcode_customers".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property mixed $barcodeCustomerList
 * @property GoodsBarcode[] $goodsBarcodes
 */
class BarcodeCustomers extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barcode_customers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'code'], 'string', 'max' => 255],
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
            'code' => Yii::t('app', 'Code'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsBarcodes()
    {
        return $this->hasMany(GoodsBarcode::className(), ['bc_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $name = explode(' ', $this->name);
            if(!empty($name)){
                $this->code = strtoupper(join('_', $name));
            }
            return true;
        }
        return false;
    }

    public static function getBarcodeCustomerList()
    {
        $items = self::find()->asArray()->all();
        $items = ArrayHelper::map($items,'id','name');
        return $items;
    }
}
