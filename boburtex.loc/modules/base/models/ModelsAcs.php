<?php

namespace app\modules\base\models;

use app\modules\bichuv\models\BichuvAcs;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "models_acs".
 *
 * @property int $id
 * @property int $model_list_id
 * @property int $bichuv_acs_id
 * @property string $qty
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $for_all_sizes
 *
 * @property BichuvAcs $bichuvAcs
 * @property ModelsList $modelList
 * @property ModelsAcsSizes[] $modelsAcsSizes
 */
class ModelsAcs extends BaseModel
{
    public $sizes;
    /** Type Bichuv Va Toquv Acs*/
    const bichuv_type = 1;
    const toquv_type  = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'models_acs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_list_id', 'model_orders_id', 'bichuv_acs_id', 'status', 'created_by', 'created_at', 'updated_at', 'for_all_sizes'], 'integer'],
            [['qty', 'type'], 'number'],
            [['add_info'], 'string'],
            [['bichuv_acs_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvAcs::className(), 'targetAttribute' => ['bichuv_acs_id' => 'id']],
            [['model_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['model_orders_id' => 'id']],
            [['model_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_list_id' => Yii::t('app', 'Model List ID'),
            'bichuv_acs_id' => Yii::t('app', 'Bichuv Acs ID'),
            'qty' => Yii::t('app', 'Qty'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'for_all_sizes' => Yii::t('app', 'For All Sizes'),
        ];
    }
    public function afterFind()
    {
        parent::afterFind();
        $this->sizes = $this->sizeList;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvAcs()
    {
        return $this->hasOne(BichuvAcs::className(), ['id' => 'bichuv_acs_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsAcsSizes()
    {
        return $this->hasMany(ModelsAcsSizes::className(), ['models_acs_id' => 'id']);
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function getAcsList()
    {
        $sql = "SELECT acs.id,
                acs.name as acsname, acs.sku as sku, acs.barcode as bar, p.name as pname, u.name as unit, u.id unit_id
                FROM bichuv_acs as acs
                LEFT JOIN bichuv_acs_property as p ON acs.property_id = p.id
                LEFT JOIN unit u ON acs.unit_id = u.id
        ";
        $acs = Yii::$app->db->createCommand($sql)->queryAll();
        $res = [];
        foreach ($acs as $item){
            $res['id'][$item['pname']][$item['id']] = $item['bar']." - ".$item['acsname']." - ".$item['pname']. "  ({$item['unit']})"
//                'group'=>$item['pname']
            ;
            $res['options'][$item['id']] = [
                'unit' => $item['unit']
            ];
        }
        $acs = ArrayHelper::map($res,'id','name','group');
        return $res;
    }

    public function getSizeList($is_view=false){
        if($is_view){
            $sizes = ModelsAcsSizes::find()->select('size_id,size.name')->joinWith('size')->where(['models_acs_id'=>$this->id])->asArray()->all();
            $result = '<code>';
            if(!empty($sizes)){
                foreach ($sizes as $key => $size) {
                    $result .= ($key!=0)?", ".$size['name']:$size['name'];
                }
            }else{
                $result .= Yii::t('app', "Barcha o'lchamlar");
            }
            return $result."</code>";
        }
        $sizes = ModelsAcsSizes::find()->select('size_id')->where(['models_acs_id'=>$this->id])->asArray()->all();
        $list = [];
        if(!empty($sizes)){
            return ArrayHelper::getColumn($sizes,'size_id');
        }
        return $list;
    }
}
