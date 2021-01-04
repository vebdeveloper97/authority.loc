<?php

namespace app\modules\toquv\models;

use app\modules\base\models\ModelOrdersItems;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "toquv_instruction_rm".
 *
 * @property int $id
 * @property int $toquv_rm_order_id
 * @property int $toquv_pus_fine_id
 * @property string $thread_length
 * @property string $finish_en
 * @property string $finish_gramaj
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $toquv_instruction_id
 * @property int $moi_id
 * @property string $quantity
 * @property int $is_service [smallint(6)]
 * @property int $is_closed
 * @property int $type_weaving
 * @property string $planed_date
 * @property string $finished_date
 *
 * @property MatoInfo[] $matoInfos
 * @property RollInfo[] $rollInfos
 * @property ToquvInstructions $toquvInstruction
 * @property ToquvPusFine $toquvPusFine
 * @property ToquvRmOrder $toquvRmOrder
 * @property ToquvKalite[] $toquvKalites
 * @property ActiveQuery $modelOrdersItems
 * @property mixed $toquvInstructionItems
 * @property mixed $modelOrdersOne
 * @property ToquvMakineProcesses[] $toquvMakineProcesses
 */
class ToquvInstructionRm extends BaseModel
{

    public $done_date;

    public $toquv_rm_id;

    public $color_pantone_id;

    public $model_code;

    public $priority;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_instruction_rm';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_rm_order_id', 'toquv_pus_fine_id', 'status', 'created_by', 'created_at', 'updated_at', 'toquv_instruction_id', 'moi_id', 'is_service', 'is_closed', 'type_weaving'], 'integer'],
            ['quantity','number'],
            [['thread_length', 'finish_en', 'finish_gramaj'], 'string', 'max' => 30],
            [['done_date','planed_date', 'finished_date'], 'safe'],
            [['toquv_instruction_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvInstructions::className(), 'targetAttribute' => ['toquv_instruction_id' => 'id']],
            [['toquv_pus_fine_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvPusFine::className(), 'targetAttribute' => ['toquv_pus_fine_id' => 'id']],
            [['toquv_rm_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRmOrder::className(), 'targetAttribute' => ['toquv_rm_order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'toquv_rm_order_id' => Yii::t('app', 'Toquv Rm Order ID'),
            'toquv_pus_fine_id' => Yii::t('app', 'Toquv Pus Fine ID'),
            'thread_length' => Yii::t('app', 'Thread Length'),
            'finish_en' => Yii::t('app', 'Finish En'),
            'finish_gramaj' => Yii::t('app', 'Finish Gramaj'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'toquv_instruction_id' => Yii::t('app', 'Toquv Instruction ID'),
            'moi_id' => Yii::t('app', 'Moi ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'is_service' => Yii::t('app', 'Is Service'),
            'is_closed' => Yii::t('app', 'Is Closed'),
            'type_weaving' => Yii::t('app', 'Type Weaving'),
            'planed_date' => Yii::t('app', 'Planed Date'),
            'finished_date' => Yii::t('app', 'Finished Date'),
        ];
    }
    /**
     * @return ActiveQuery
     */
    public function getMatoInfos()
    {
        return $this->hasMany(MatoInfo::className(), ['toquv_instruction_rm_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRollInfos()
    {
        return $this->hasMany(RollInfo::className(), ['tir_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getToquvInstruction()
    {
        return $this->hasOne(ToquvInstructions::className(), ['id' => 'toquv_instruction_id']);
    }
    public function getToquvInstructionItems()
    {
        return $this->hasMany(ToquvInstructionItems::className(), ['toquv_instruction_rm_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getToquvPusFine()
    {
        return $this->hasOne(ToquvPusFine::className(), ['id' => 'toquv_pus_fine_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToquvRmOrder()
    {
        return $this->hasOne(ToquvRmOrder::className(), ['id' => 'toquv_rm_order_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToquvKalites()
    {
        return $this->hasMany(ToquvKalite::className(), ['toquv_instruction_rm_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItems()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'moi_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getToquvMakineProcesses()
    {
        return $this->hasMany(ToquvMakineProcesses::className(), ['toquv_instruction_rm_id' => 'id']);
    }
    public function getModelOrdersOne(){
        $sql = "SELECT 
                    trm.name,
                    CONCAT(ml.article, ' ',ml.name) model,
                    moi.id m_order_id,
                    mo.id,
                    tro.toquv_raw_materials_id trm_id,
                    tro.thread_length,
                    tro.finish_en,
                    tro.finish_gramaj,
                    summa
                FROM toquv_instruction_rm tir                      
                LEFT JOIN model_orders_items moi ON moi.id = tir.moi_id                         
                LEFT JOIN moi_rel_dept mrd ON mrd.model_orders_items_id = moi.id                         
                LEFT JOIN model_orders mo ON mo.id = moi.model_orders_id   
                LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                LEFT JOIN toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id       
                LEFT JOIN models_list ml on moi.models_list_id = ml.id
                LEFT JOIN (SELECT model_orders_items_id,SUM(count) summa FROM model_orders_items_size mois LEFT JOIN size s on mois.size_id = s.id GROUP BY mois.model_orders_items_id) mois on moi.id = mois.model_orders_items_id
                WHERE (mrd.toquv_departments_id=2) AND (tir.id= %d) LIMIT 1";
        $sql = sprintf($sql,$this->id);
        return Yii::$app->db->createCommand($sql)->queryAll()[0];
    }
    public function getIplar($br='<br>')
    {
        $iplar = $this->toquvInstructionItems;
        $ip = '';
        if($iplar){
            foreach ($iplar as $key => $item) {
                $ip .= $item['thread_name'];
                $ip .= ($key!=count($iplar)-1)?$br:'';
            }
        }
        return $ip;
    }
}
