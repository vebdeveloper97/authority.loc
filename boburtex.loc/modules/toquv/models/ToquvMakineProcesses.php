<?php

namespace app\modules\toquv\models;

use app\models\Constants;
use app\models\Users;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "toquv_makine_processes".
 *
 * @property int $id
 * @property int $ti_id
 * @property int $toquv_instruction_rm_id
 * @property int $toquv_order_id
 * @property int $toquv_order_item_id
 * @property int $machine_id
 * @property int $user_id
 * @property string $started_at
 * @property string $ended_at
 * @property int $created_by
 * @property int $ended_by
 * @property int $status
 *
 * @property Users $endedBy
 * @property ToquvInstructions $ti
 * @property ToquvInstructionRm $toquvInstructionRm
 * @property ToquvOrders $toquvOrder
 * @property mixed $user
 * @property mixed $userBy
 * @property ActiveQuery $toquvMakine
 * @property ToquvRmOrder $toquvOrderItem
 */
class ToquvMakineProcesses extends BaseModel
{
    /*public $status = 1;*/
    public $created_at;
    public $updated_at;
    public $machines;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_makine_processes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_order_id', 'toquv_order_item_id', 'machines'], 'required'],
            [['ti_id', 'toquv_instruction_rm_id', 'toquv_order_id', 'toquv_order_item_id', 'machine_id', 'user_id', 'created_by', 'ended_by', 'status'], 'integer'],
            [['started_at', 'ended_at'], 'safe'],
            [['ended_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['ended_by' => 'id']],
            [['ti_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvInstructions::className(), 'targetAttribute' => ['ti_id' => 'id']],
            [['toquv_instruction_rm_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvInstructionRm::className(), 'targetAttribute' => ['toquv_instruction_rm_id' => 'id']],
            [['toquv_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvOrders::className(), 'targetAttribute' => ['toquv_order_id' => 'id']],
            [['toquv_order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRmOrder::className(), 'targetAttribute' => ['toquv_order_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ti_id' => Yii::t('app', 'Ti ID'),
            'toquv_instruction_rm_id' => Yii::t('app', 'Toquv Instruction Rm ID'),
            'toquv_order_id' => Yii::t('app', 'Toquv Orders'),
            'toquv_kg' => Yii::t('app', 'Buyurtma miqdori'),
            'instruction_kg' => Yii::t('app', 'Ko\'rsatma miqdori'),
            'toquv_order_item_id' => Yii::t('app', 'Mato'),
            'machine_id' => Yii::t('app', 'Mashina'),
            'user_id' => 'User Name',
            'started_at' => Yii::t('app', 'Boshlangan'),
            'ended_at' => Yii::t('app', 'Tugallangan'),
            'done_date' => Yii::t('app', 'Reja'),
            'created_by' => Yii::t('app', 'Created By'),
            'machines'  => Yii::t('app','Machines'),
            'ended_by' => Yii::t('app', 'Ended By'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $date = date('Y-m-d');
            if (!empty($this->reg_date)) {
                $date = date('Y-m-d', strtotime($this->started_at));
            }
            $currentTime = date('H:i:s');
            $this->started_at = date('Y-m-d H:i:s', strtotime($date . ' ' . $currentTime));
            return true;
        } else {
            return false;
        }
    }
    public function afterFind()
    {
        $this->started_at = $this->started_at ? date('H:i d.m.Y', strtotime($this->started_at)) : null;
        $this->ended_at = $this->ended_at ? date('H:i d.m.Y', strtotime($this->ended_at)) : null;
        parent::afterFind();
    }
    /**
     * @return ActiveQuery
     */
    public function getEndedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'ended_by']);
    }
    /**
     * @return ActiveQuery
     */
    public function getTi()
    {
        return $this->hasOne(ToquvInstructions::className(), ['id' => 'ti_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToquvInstructionRm()
    {
        return $this->hasOne(ToquvInstructionRm::className(), ['id' => 'toquv_instruction_rm_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getToquvOrder()
    {
        return $this->hasOne(ToquvOrders::className(), ['id' => 'toquv_order_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToquvOrderItem()
    {
        return $this->hasOne(ToquvRmOrder::className(), ['id' => 'toquv_order_item_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getToquvMakine()
    {
        return $this->hasOne(ToquvMakine::className(), ['id' => 'machine_id']);
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    public function getUserBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by']);
    }

    public function getIplar($br='<br>')
    {
        $iplar = $this->toquvInstructionRm->toquvInstructionItems;
        $ip = '';
        if($iplar){
            foreach ($iplar as $key => $item) {
                $ip .= $item['thread_name'];
                $ip .= ($key!=count($iplar)-1)?$br:'';
            }
        }
        return $ip;
    }
    public static function getMachineList($pus)
    {
        $sql = "SELECT tm.id,
       tpf.name pus_fine,
       toqrm.name           AS mato,
       tir.quantity         AS quantity,
       tir.thread_length     AS length,
       tir.finish_en         AS en,
       tir.finish_gramaj     AS gramaj,
       toqro.quantity       AS order_quantity,
       SUM(tk.quantity)       AS tk_quantity,
       tm.name              AS mname,
       tm.type,
       mus.name             AS musname,
       m.name             AS model_mushteri,
       toqo.document_number AS dnum,
       toqo.done_date       AS doned
       from toquv_makine tm
         LEFT JOIN toquv_makine_processes toq ON toq.machine_id = tm.id
         LEFT JOIN toquv_orders toqo ON toqo.id = toq.toquv_order_id
         LEFT JOIN toquv_instruction_rm tir ON toq.toquv_instruction_rm_id = tir.id
         LEFT JOIN toquv_rm_order toqro ON toqro.toquv_orders_id = toq.toquv_order_id
         LEFT JOIN toquv_raw_materials toqrm ON toqro.toquv_raw_materials_id = toqrm.id
         LEFT JOIN musteri mus ON mus.id = toqo.musteri_id
         LEFT JOIN musteri m ON m.id = toqo.model_musteri_id
         LEFT JOIN toquv_pus_fine tpf ON tm.pus_fine_id = tpf.id
         LEFT JOIN toquv_kalite tk ON toqro.id = tk.toquv_rm_order_id
        where tpf.name like '%$pus%'
         and (toq.id IN (select max(id)
                 FROM toquv_makine_processes tmp
                 group by tmp.machine_id) or toq.id is null)
        GROUP BY tm.id";
        $machineLists = Yii::$app->db->createCommand($sql)->queryAll();
        if(empty($machineLists)){

        }
        return $machineLists;
    }
    public function getPartiyaList($type=1)
    {
        $makine = ToquvInstructions::find()->alias('ti')->select(['ti.id id','ti.reg_date','tor.id tor_id', 'musteri.name musteri', 'm2.name model_musteri', 'tor.document_number', 'tor.priority', 'tor.done_date'])
            ->leftJoin('toquv_orders tor' ,'tor.id = ti.toquv_order_id')
            ->leftJoin('musteri', 'musteri.id = tor.musteri_id')
            ->leftJoin('model_orders', 'tor.model_orders_id = model_orders.id')
            ->leftJoin('musteri m2', 'tor.model_musteri_id = m2.id')
            ->where(['ti.type'=>$type])
            ->andFilterWhere(['ti.status' => 3])
            ->andFilterWhere(['ti.is_closed' => 1])
            ->orderBy(['priority' => SORT_DESC, 'done_date' => SORT_ASC])
            ->asArray()->all();
        $return = [];
        foreach ($makine as $item) {
            $model_musteri = ($item['model_musteri'])?" (<small><b>{$item['model_musteri']}</b></small>)":"";
            $return[$item['id']] = " <span class='btn btn-default'> <span style='color: maroon;'> <b>{$item['musteri']}</b></span> {$model_musteri}". '</span>  |  <b>' . $item['document_number'] . '</b> | Muddati -> <b>' . $item['done_date'] . '</b> | Muhimligi -><b>' . Constants::getPriorityList($item['priority']).
            '</b> | Ko\'rsatma sanasi-> <b>'. date('d.m.Y H:i',strtotime($item['reg_date'])).'</b>';
        }
        return $return;
    }
}
