<?php

namespace app\modules\hr\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%hr_position}}".
 *
 * @property int $id
 * @property string $name
 * @property int $functional_tasks_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class HrPosition extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hr_position}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['name', 'functional_tasks_id'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'functional_tasks_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['functional_tasks_id'], 'exist', 'skipOnError' => true, 'targetClass' => PositionFunctionalTasks::className(), 'targetAttribute' => ['functional_tasks_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app', 'Position name'),
            'functional_tasks_id' => Yii::t('app', 'Functional tasks'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getPositionFunctionalTasks() {
        return $this->hasOne(PositionFunctionalTasks::class, ['id' => 'functional_tasks_id']);
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getStatusList($key = null){
        $result = [
            self::STATUS_ACTIVE   => Yii::t('app','Active'),
            self::STATUS_INACTIVE => Yii::t('app','Inactive'),
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    public function getArray($id=null)
    {
        $model = '';
        if(!empty($id)){
            $model = self::findOne($id);
        }
        $model = ArrayHelper::map(self::find()->asArray()->all(), 'id', 'name');
        return $model;
    }
}
