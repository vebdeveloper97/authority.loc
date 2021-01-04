<?php

namespace app\modules\settings\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "company_categories".
 *
 * @property int $id
 * @property string $name
 * @property int $order
 * @property int $type
 * @property string $token
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 */
class CompanyCategories extends \app\modules\settings\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order', 'type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['add_info'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['token'], 'string', 'max' => 30],
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
            'order' => Yii::t('app', 'Order'),
            'type' => Yii::t('app', 'Type'),
            'token' => Yii::t('app', 'Token'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    public static function getList($key = null){
        $list = static::find()->select(['id','name'])->where(['status'=>static::STATUS_ACTIVE])->asArray()->all();
        $result = ArrayHelper::map($list,'id','name');
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }
}
