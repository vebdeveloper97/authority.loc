<?php

namespace app\modules\toquv\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "toquv_ne".
 *
 * @property int $id
 * @property string $name
 * @property string $add_info
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property ToquvIp[] $toquvIps
 */
class ToquvNe extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_ne';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            ['name','unique'],
            [['add_info'], 'string'],
            [['status', 'created_at', 'updated_at', 'created_by'], 'integer'],
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
            'name' => Yii::t('app', 'Ne Name'),
            'add_info' => Yii::t('app', 'add_info'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvIps()
    {
        return $this->hasMany(ToquvIp::className(), ['ne_id' => 'id']);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getNameById($id)  {

        $model = self::find()->where(['id' => $id])->one();
        return $model->name;
    }
    public static function getFullNameAllTypes()
    {
        $types = self::find()->where(['status' => 1])->orderBy(['name'=>SORT_ASC])->all();

        return ArrayHelper::map($types,'id','name');

    }
}
