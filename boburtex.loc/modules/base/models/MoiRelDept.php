<?php

namespace app\modules\base\models;

use app\modules\toquv\models\ToquvDepartments;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "moi_rel_dept".
 *
 * @property int $id
 * @property string $name
 * @property int $model_orders_items_id
 * @property int $musteri_id
 * @property int $company_categories_id
 * @property int $toquv_departments_id
 * @property int $type
 * @property string $start_date
 * @property string $end_date
 * @property string $add_info
 * @property string $quantity
 * @property int $unit_id
 * @property int $model_orders_planning_id
 * @property int $is_own
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $model_orders_id
 *
 * @property ModelOrders $modelOrders
 * @property ModelOrdersItems $modelOrdersItems
 * @property ModelOrdersPlanning $modelOrdersPlanning
 * @property ToquvDepartments $toquvDepartments
 * @property int $count [int(11)]
 * @property ActiveQuery $size
 * @property int $size_id [int(11)]
 * @property string $thread_length [varchar(50)]
 * @property string $finish_en [varchar(50)]
 * @property string $finish_gramaj [varchar(50)]
 */
class MoiRelDept extends BaseModel
{
    const TYPE_MATO = 1;
    const TYPE_MATO_AKS = 2;
    const TYPE_BOYOQ = 3;
    const TYPE_BICHUV = 4;
    const TYPE_TIKUV = 5;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'moi_rel_dept';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_items_id','model_orders_planning_id','quantity','end_date'], 'required'],
            [['model_orders_items_id', 'musteri_id', 'company_categories_id', 'toquv_departments_id', 'type', 'unit_id', 'model_orders_planning_id', 'is_own', 'status', 'created_by', 'created_at', 'updated_at', 'model_orders_id', 'count', 'size_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['add_info'], 'string'],
            [['quantity'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['thread_length', 'finish_en', 'finish_gramaj'], 'string', 'max' => 50],
            [['model_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['model_orders_id' => 'id']],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
            [['model_orders_planning_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersPlanning::className(), 'targetAttribute' => ['model_orders_planning_id' => 'id']],
            [['toquv_departments_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['toquv_departments_id' => 'id']],
        ];
    }
    public static function getTypeList($key = null){
        $result = [
            self::TYPE_MATO   => Yii::t('app','Mato'),
            self::TYPE_MATO_AKS => Yii::t('app','Mato aksessuar'),
            self::TYPE_BOYOQ => Yii::t('app','Bo\'yoq'),
            self::TYPE_BICHUV => Yii::t('app','Bichuv'),
            self::TYPE_TIKUV => Yii::t('app','Tikuv')
        ];
        if(!empty($key)){
            return $result[$key];
        }

        return $result;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'model_orders_items_id' => Yii::t('app', 'Model Orders Items ID'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'company_categories_id' => Yii::t('app', 'Company Categories ID'),
            'toquv_departments_id' => Yii::t('app', 'Toquv Departments ID'),
            'type' => Yii::t('app', 'Type'),
            'start_date' => Yii::t('app', 'Boshlash sanasi'),
            'end_date' => Yii::t('app', 'Tayyorlanish sanasi'),
            'add_info' => Yii::t('app', 'Add Info'),
            'quantity' => Yii::t('app', 'Quantity'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'model_orders_planning_id' => Yii::t('app', 'Model Orders Planning ID'),
            'is_own' => Yii::t('app', 'Is Own'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'model_orders_id' => Yii::t('app', 'Model Orders ID'),
            'count' => Yii::t('app', 'Soni'),
            'thread_length' => Yii::t('app', 'Thread Length'),
            'finish_en' => Yii::t('app', 'Finish En'),
            'finish_gramaj' => Yii::t('app', 'Finish Gramaj'),
        ];
    }
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $date = date('Y-m-d');
            if(!empty($this->start_date)){
                $date =  date('Y-m-d', strtotime($this->start_date));
            }
            if(!empty($this->end_date)){
                $end_date =  date('Y-m-d', strtotime($this->end_date));
            }
            $currentTime = date('H:i:s');
            $this->start_date = date('Y-m-d H:i:s', strtotime($date.' '.$currentTime));
            $this->end_date = date('Y-m-d H:i:s', strtotime($end_date.' '.$currentTime));
            return true;
        }else{
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->start_date = date('d.m.Y', strtotime($this->start_date));
        $this->end_date = date('d.m.Y', strtotime($this->end_date));

    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrders()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'model_orders_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItems()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'model_orders_items_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrdersPlanning()
    {
        return $this->hasOne(ModelOrdersPlanning::className(), ['id' => 'model_orders_planning_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getToquvDepartments()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'toquv_departments_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }
}
