<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "categories_uz".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class CategoriesUz extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories_'.Yii::$app->language;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 50],
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
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * {@inheritdoc}
     * beforesave Saqlashdan oldin ishlashi uchun
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if(empty($this->status)){
                $this->status = 1;
            }
            return true;
        }
        else{
            return false;
        }
    }

}
