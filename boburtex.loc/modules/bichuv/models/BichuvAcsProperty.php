<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "bichuv_acs_property".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property string $add_info
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property BichuvAcs[] $bichuvAcs
 */
class BichuvAcsProperty extends \app\modules\toquv\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_acs_property';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['add_info'], 'string'],
            [['name'], 'string', 'max' => 200],
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
            'type' => Yii::t('app', 'Type'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvAcs()
    {
        return $this->hasMany(BichuvAcs::className(), ['property_id' => 'id']);
    }
}
