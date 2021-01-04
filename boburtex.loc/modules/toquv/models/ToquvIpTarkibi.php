<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_ip_tarkibi".
 *
 * @property int $id
 * @property int $fabric_type_id
 * @property int $quantity
 * @property int $ip_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 *@property FabricTypes $fabricType
 * @property ToquvIp $ip
 */
class ToquvIpTarkibi extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_ip_tarkibi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fabric_type_id', 'quantity'], 'required'],
            [['fabric_type_id', 'quantity', 'ip_id',
                'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['ip_id'], 'exist', 'skipOnError' => true,
                'targetClass' => ToquvIp::className(),
                'targetAttribute' => ['ip_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fabric_type_id' => Yii::t('app', 'Fabric Type ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'ip_id' => Yii::t('app', 'Ip ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFabricType()
    {
        return $this->hasOne(FabricTypes::className(), ['id' => 'fabric_type_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIp()
    {
        return $this->hasOne(ToquvIp::className(), ['id' => 'ip_id']);
    }

    public static function deleteTarkib($id)
    {
        foreach (self::find()->where(['ip_id' => $id])->all() as $child) {
            $child->delete();
        }
    }
    public function getFullName(){
        $name = $this->fabricType->name_uz . " - " . $this->quantity . " %<br>";
        return $name;
    }
}
