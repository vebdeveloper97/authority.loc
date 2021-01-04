<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "base_error_category".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BaseErrorList[] $baseErrorLists
 */
class BaseErrorCategory extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_error_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'code'], 'string', 'max' => 255],
            [['code'],'unique']
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
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseErrorLists()
    {
        return $this->hasMany(BaseErrorList::class, ['error_category_id' => 'id']);
    }

    public static function getErrorCategoryList(){
        $errorCategory = self::find()->asArray()->all();
        if (!empty($errorCategory))
            return $errorCategory;
        return false;
    }

    public static  function getErrorCategoryListMap(){
        $errorCateogryList  = self::getErrorCategoryList();
        if ($errorCateogryList){
            return ArrayHelper::map($errorCateogryList, 'id','name');
        }
        return [];
    }
}
