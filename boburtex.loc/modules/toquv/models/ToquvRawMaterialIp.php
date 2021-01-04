<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_raw_material_ip".
 *
 * @property int $id
 * @property int $toquv_raw_material_id
 * @property int $created_by
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $percentage
 * @property int $ne_id
 * @property int $thread_id
 *
 * @property ToquvNe $ne
 * @property ToquvRawMaterials $toquvRawMaterial
 * @property ToquvThread $thread
 */
class ToquvRawMaterialIp extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_raw_material_ip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_raw_material_id', 'created_by', 'status', 'created_at', 'updated_at', 'percentage', 'ne_id', 'thread_id'], 'integer'],
            [['ne_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvNe::className(), 'targetAttribute' => ['ne_id' => 'id']],
            [['toquv_raw_material_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRawMaterials::className(), 'targetAttribute' => ['toquv_raw_material_id' => 'id']],
            [['thread_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvThread::className(), 'targetAttribute' => ['thread_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'toquv_raw_material_id' => Yii::t('app', 'Toquv Raw Material ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'percentage' => Yii::t('app', 'Percentage'),
            'ne_id' => Yii::t('app', 'Ne ID'),
            'thread_id' => Yii::t('app', 'Thread ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvNe()
    {
        return $this->hasOne(ToquvNe::className(), ['id' => 'ne_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvRawMaterial()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'toquv_raw_material_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvThread()
    {
        return $this->hasOne(ToquvThread::className(), ['id' => 'thread_id']);
    }
    public static function deleteRawMaterialIp($id)
    {
        foreach (self::find()->where(['toquv_raw_material_id' => $id])->all() as $child) {
            $child->delete();
        }
    }
    public function getFullName($comma='',$br=false){
        $new_row = (!$br)?"<br>":"";
        $name = $this->toquvNe->name . " - " . $this->toquvThread->name . " - ". $this->percentage . " %{$comma} {$new_row}";
        return $name;
    }
    public function getThreadNeName(){
        $name = $this->toquvNe->name . " " . $this->toquvThread->name;
        return $name;
    }
    public function getPrice(){
        $price = ToquvPriceIpItem::findOne(['toquv_ne_id'=>$this->ne_id,'toquv_thread_id'=>$this->thread_id]);
        return ($price)?$price['price']:0;
    }
}
