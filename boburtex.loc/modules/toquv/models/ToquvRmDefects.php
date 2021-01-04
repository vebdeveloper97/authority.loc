<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_rm_defects".
 *
 * @property int $id
 * @property string $name
 *
 * @property ToquvKaliteDefects[] $toquvKaliteDefects
 */
class ToquvRmDefects extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_rm_defects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 150],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvKaliteDefects()
    {
        return $this->hasMany(ToquvKaliteDefects::className(), ['toquv_rm_defects_id' => 'id']);
    }
}
