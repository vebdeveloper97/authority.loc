<?php

namespace app\modules\mobile\models;

use app\modules\base\models\BaseDetailLists;
use app\modules\base\models\ModelOrdersItems;
use app\modules\bichuv\models\BichuvDetailTypes;
use Yii;

/**
 * This is the model class for table "mobile_process_production".
 *
 * @property int $id
 * @property int $mobile_tables_id
 * @property int $doc_id
 * @property int $doc_items_id
 * @property int $model_orders_items_id
 * @property string $nastel_no
 * @property string $started_date
 * @property string $ended_date
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property MobileTables $mobileTable
 * @property string $table_name [varchar(60)]
 * @property ModelOrdersItems $modelOrdersItems
 * @property int $parent_id [int]
 * @property int $bichuv_detail_type_id [int]
 * @property-read \yii\db\ActiveQuery $parent
 * @property int $base_detail_list_id [int]
 */
class MobileProcessProduction extends BaseModel
{
    const STATUS_WAITING = 1;
    const STATUS_IN_PROCESS = 2;
    const STATUS_STOPPED = 3;
    const STATUS_CANCELED = 4;
    const STATUS_ENDED = 5;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mobile_process_production';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_items_id', 'base_detail_list_id','mobile_tables_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'model_orders_items_id', 'doc_id'], 'integer'],
            [['started_date', 'ended_date'], 'datetime', 'format' => 'php: d.m.Y H:i:s'],
            ['table_name', 'string', 'max' => 60],
            [['nastel_no'], 'string', 'max' => 255],
            [['mobile_tables_id'], 'exist', 'skipOnError' => true, 'targetClass' => MobileTables::class, 'targetAttribute' => ['mobile_tables_id' => 'id']],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::class, 'targetAttribute' => ['model_orders_items_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => MobileProcessProduction::class, 'targetAttribute' => ['parent_id' => 'id']],
            [['bichuv_detail_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDetailTypes::class, 'targetAttribute' => ['bichuv_detail_type_id' => 'id']],
            [['base_detail_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseDetailLists::class, 'targetAttribute' => ['base_detail_list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'mobile_tables_id' => Yii::t('app', 'Table'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
            'started_date' => Yii::t('app', 'Start date'),
            'ended_date' => Yii::t('app', 'End date'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMobileTable()
    {
        return $this->hasOne(MobileTables::class, ['id' => 'mobile_tables_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(MobileProcessProduction::class, ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersItems()
    {
        return $this->hasOne(ModelOrdersItems::class, ['id' => 'model_orders_items_id']);
    }

    public function afterFind()
    {
        if (!empty($this->started_date)) {
            $this->started_date = date('d.m.Y H:i:s', strtotime($this->started_date));
        }

        if (!empty($this->ended_date)) {
            $this->ended_date = date('d.m.Y H:i:s', strtotime($this->ended_date));
        }
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!empty($this->started_date)) {
            $this->started_date = date('Y-m-d H:i:s', strtotime($this->started_date));
        }

        if (!empty($this->ended_date)) {
            $this->ended_date = date('Y-m-d H:i:s', strtotime($this->ended_date));

            /** agar JARAYON yakunlansa status => 3 qilamiz */
            $this->status = self::STATUS_ENDED;
        }

        return true;
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getStatusList($key = null){
        $result = [
            self::STATUS_WAITING   => Yii::t('app','Wainting'),
            self::STATUS_IN_PROCESS   => Yii::t('app','In process'),
            self::STATUS_STOPPED => Yii::t('app','Stopped'),
            self::STATUS_CANCELED => Yii::t('app','Canceled'),
            self::STATUS_ENDED => Yii::t('app','Ended'),
        ];
        if(!empty($key)){
            return $result[$key];
        }

        return $result;
    }

    /**
     * @param array $data
     * @param null $id
     * @return array|bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public static function saveMobileProcess($data, $id = null){
        $sql = "SHOW COLUMNS FROM %s ;";
        $sql = sprintf($sql, self::getTableSchema()->name);
        $fields = Yii::$app->db->createCommand($sql)->queryAll();
        $existsFields = [];
        foreach ($fields as $item){
            array_push($existsFields, $item['Field']);
        }
        $model = new self();
        if(!empty($id)){
            $model = self::findOne($id);
            if($model === null){
                Yii::error('Not found Mobile Process Production ID','save');
                return false;
            }
        }
        foreach ($data as $field => $value){
            if(in_array($field, $existsFields)){
                $model->{$field} = $value;
            }
        }
        if($model->save()){
            return $model;
        }
        return false;
    }

    /**
     * @param $nastel
     * @return MobileProcessProduction|array|bool|\yii\db\ActiveRecord|null\
     */
    public static function getCardItemOrder($nastel)
    {
        $cardItem = self::find()
            ->where(['nastel_no' => $nastel])
            ->andWhere(['not',['parent_id' => null]])
            ->andWhere(['not',['model_orders_items_id' => null]])
            ->asArray()
            ->one();
        if (!empty($cardItem))
            return $cardItem;
        return false;
    }

    public static function getNastelTableWithWorker()
    {

    }

}
