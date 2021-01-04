<?php

namespace app\modules\bichuv\models;

use app\components\OurCustomBehavior;
use app\modules\base\models\ModelOrdersItems;
use app\modules\base\models\ModelOrdersPlanning;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvRawMaterials;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%bichuv_mato_order_items}}".
 *
 * @property int $id
 * @property int $bichuv_mato_orders_id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $name
 * @property string $quantity
 * @property int $roll_count
 * @property int $count
 * @property int $moi_id model_orders_items_id
 * @property int $mop_id model_orders_planning_id
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BichuvDocResponsible[] $bichuvDocResponsibles
 * @property BichuvMatoOrders $bichuvMatoOrders
 * @property ModelOrdersItems $moi
 * @property ToquvRawMaterials $mato
 * @property string $trmName
 * @property mixed $given
 * @property ModelOrdersPlanning $mop
 */
class BichuvMatoOrderItems extends BaseModel
{
    public $given_qty;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bichuv_mato_order_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_mato_orders_id', 'entity_id', 'entity_type', 'roll_count', 'count', 'moi_id', 'mop_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['quantity'], 'number'],
            [['bichuv_mato_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvMatoOrders::className(), 'targetAttribute' => ['bichuv_mato_orders_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bichuv_mato_orders_id' => Yii::t('app', 'Bichuv Mato Orders ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'name' => Yii::t('app', 'Name'),
            'quantity' => Yii::t('app', 'Quantity'),
            'roll_count' => Yii::t('app', 'Roll Count'),
            'count' => Yii::t('app', 'Count'),
            'moi_id' => Yii::t('app', 'Moi ID'),
            'mop_id' => Yii::t('app', 'Mop ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }
    /**
     * @return ActiveQuery
     */
    public function getBichuvDocResponsibles()
    {
        return $this->hasMany(BichuvDocResponsible::className(), ['bichuv_mato_order_items_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getBichuvMatoOrders()
    {
        return $this->hasOne(BichuvMatoOrders::className(), ['id' => 'bichuv_mato_orders_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getMoi()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'moi_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getMato()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'entity_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getMop()
    {
        return $this->hasOne(ModelOrdersPlanning::className(), ['id' => 'mop_id']);
    }
    public function getTrmName()
    {
        $mato = $this->mato;
        $mop = $this->mop;
        $color = ($mop->colorPantone)?"- ({$mop->colorPantone->code}) -":'';
        if($mato->type==ToquvRawMaterials::MATO){
            $name = "{$mato->name} {$color} ({$mop->finish_en} sm | {$mop->finish_gramaj} gr/m2 - ({$mato->getRawMaterialIp(',',true)})";
        }else{
            $name = "{$mato->name} {$color} - ({$mop->finish_en} x {$mop->finish_gramaj})";
        }
        return $name;
    }
    public function getGiven()
    {
        $mato_ombor = ToquvDepartments::findOne(['token'=>'BICHUV_MATO_OMBOR']);
        $bichuv = ToquvDepartments::findOne(['token'=>'BICHUV_DEP']);
        $given = BichuvDocItems::find()->joinWith('bichuvDoc bd')->where(['bichuv_mato_order_items_id'=>$this->id,'bd.document_type'=>BichuvDoc::DOC_TYPE_MOVING,'bd.from_department'=>$mato_ombor['id'],'bd.to_department'=>$bichuv['id']])->andFilterWhere(['>','bd.status',BichuvDoc::STATUS_INACTIVE])->sum('quantity');
        return $given;
    }
}
