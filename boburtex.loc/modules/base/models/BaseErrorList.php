<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "base_error_list".
 *
 * @property int $id
 * @property int $error_category_id
 * @property string $name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BaseErrorCategory $errorCategory
 * @property BaseNormStandartItems[] $baseNormStandartItems
 * @property BaseQcDocument[] $baseQcDocuments
 */
class BaseErrorList extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_error_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['error_category_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['error_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseErrorCategory::className(), 'targetAttribute' => ['error_category_id' => 'id']],
            [['error_category_id','name'],'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'error_category_id' => Yii::t('app', 'Base Error Category'),
            'name' => Yii::t('app', 'Name'),
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
    public function getErrorCategory()
    {
        return $this->hasOne(BaseErrorCategory::className(), ['id' => 'error_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseNormStandartItems()
    {
        return $this->hasMany(BaseNormStandartItems::className(), ['error_list_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseQcDocuments()
    {
        return $this->hasMany(BaseQcDocument::className(), ['error_list_id' => 'id']);
    }

    public static function getErrorList(){
        $errors = self::find()->all();
        if (!empty($errors)){
            return $errors;
        }else{
            return false;
        }
    }

    public static function getErrorListMap(){
        $errors = self::getErrorList();
        if (!empty($errors)){
            return ArrayHelper::map($errors,'id',function($model){
                return $model->name." - ".$model->errorCategory->name;
            });
        }else{
            return [];
        }
    }
}
