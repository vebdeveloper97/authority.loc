<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_price_ip".
 *
 * @property int $id
 * @property string $data_number
 * @property string $reg_date
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvPriceIpItem[] $toquvPriceIpItems
 */
class ToquvPriceIp extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_price_ip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['doc_number','unique'],
            [['reg_date','doc_number'],'required'],
            [['reg_date'], 'safe'],
            [['add_info'], 'string'],
            [['status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['doc_number'], 'string', 'max' => 50],
        ];
    }
     /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->reg_date = date('Y-m-d', strtotime($this->reg_date));
            return true;
        }else{
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->reg_date = date('d.m.Y', strtotime($this->reg_date));

    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'doc_number' => Yii::t('app', 'Doc Number'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvPriceIpItems()
    {
        return $this->hasMany(ToquvPriceIpItem::className(), ['toquv_price_ip_id' => 'id']);
    }
    public function getEntityAll($type){
        $sql = ($type != 'raw_material_type')?sprintf("SELECT id, name from toquv_{$type}
                WHERE status = 1"):sprintf("SELECT id, name from {$type}");
        $table = Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($table)){
            $result = [];
            foreach ($table as $key){
                $result[$key['id']] = $key['name'];
            }
            return $result;
        }
        return null;
    }
    public function savePricing($data){
        foreach ($data as $key) {
            if(!empty($key['price']) && $key['price']>0){
                $item = ToquvPriceIpItem::findOne(['toquv_price_ip_id'=>$this->id,'toquv_ne_id'=>$key['toquv_ne_id'],'toquv_thread_id'=>$key['toquv_thread_id']]);
                $pricing = ($item) ? $item : new ToquvPriceIpItem();
                $pricing->setAttributes([
                    'toquv_price_ip_id' => $this->id,
                    'toquv_ne_id' => $key['toquv_ne_id'],
                    'toquv_thread_id' => $key['toquv_thread_id'],
                    'price' => $key['price'],
                    'pb_id' => 2,
                ]);
                $pricing->save();
            }
        }
    }
    public function removePricing($remove){
        foreach ($remove as $key) {
            $item = ToquvPriceIpItem::findOne($key);
            if ($item) {
                $item->delete();
            }
        }
    }
}
