<?php

namespace app\modules\hr\models;

use Yii;
use app\modules\hr\models\BaseModel;
/**
 * This is the model class for table "hr_addition_task_items".
 *
 * @property int $id
 * @property int $hr_addition_task_id
 * @property string $task
 * @property int $rate
 * @property int $status
 * @property string $expire_date
 * @property string $remember_date
 * @property int $type
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property HrAdditionTaskToEmployees $hrAdditionTask
 */
class HrAdditionTaskItems extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_addition_task_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_addition_task_id', 'rate', 'status', 'type', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['task'], 'string'],
            [['task'],'required'],
            [['expire_date', 'remember_date'], 'safe'],
            [['hr_addition_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrAdditionTaskToEmployees::className(), 'targetAttribute' => ['hr_addition_task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hr_addition_task_id' => 'Hr Addition Task ID',
            'task' => 'Task',
            'rate' => 'Rate',
            'status' => 'Status',
            'expire_date' => 'Expire Date',
            'remember_date' => 'Remember Date',
            'type' => 'Type',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrAdditionTask()
    {
        return $this->hasOne(HrAdditionTaskToEmployees::className(), ['id' => 'hr_addition_task_id']);
    }
}
