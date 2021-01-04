<?php

namespace app\modules\toquv\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "toquv_thread".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property ToquvIp[] $toquvIps
 */
class ToquvThread extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_thread';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Thread Name'),
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
        return $this->hasMany(ToquvIp::className(), ['thread_id' => 'id']);
    }

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
