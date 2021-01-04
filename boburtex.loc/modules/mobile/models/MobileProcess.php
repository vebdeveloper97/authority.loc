<?php

namespace app\modules\mobile\models;

use app\models\Users;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployeeUsers;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "mobile_process".
 *
 * @property int $id
 * @property string $name
 * @property int $process_order
 * @property int $department_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property string $token
 *
 * @property HrDepartments $department
 * @property MobileTables[] $mobileTables
 * @property MobileProcessProduction[] $mobileProcessProductions
 */
class MobileProcess extends \app\modules\mobile\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mobile_process';
    }

    public static function getNextProcessInstanceByProcessId(int $processId)
    {
        $currentProcessOrder = static::find()
            ->select('process_order')
            ->andWhere(['id' => $processId])
            ->scalar();

        return static::find()
            ->andWhere(['process_order' => $currentProcessOrder])
            ->limit(1)
            ->all();
    }

    public static function getNextProcessInstanceByTableId(int $tableId)
    {
        $currentProcessOrder = static::find()
            ->alias('mp')
            ->select('mp.process_order')
            ->leftJoin(['mt' => 'mobile_tables'], 'mp.id = mt.mobile_process_id')
            ->andWhere(['mt.id' => $tableId])
            ->scalar();

        return static::find()
            ->andWhere(['process_order' => $currentProcessOrder+1])
            ->limit(1)
            ->one();
    }

    public static function getProcessesByDepartmentIdAndUserId($department_id, $userId)
    {
        return  static::find()
            ->alias('mp')
            ->innerJoin(['mt' => 'mobile_tables'], 'mp.id = mt.mobile_process_id')
            ->innerJoin(['mtrhe' => 'mobile_tables_rel_hr_employee'], 'mt.id = mtrhe.mobile_tables_id and mtrhe.status = 1')
            ->innerJoin(['he' => 'hr_employee'], 'mtrhe.hr_employee_id = he.id')
            ->innerJoin(['heu' => 'hr_employee_users'], 'heu.hr_employee_id = he.id')
            ->andWhere([
                'heu.users_id' => $userId,
                'mp.department_id' => $department_id,
            ])
            ->all();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'process_order'], 'required'],
            ['name', 'trim'],
            [['process_order','type'],'integer'],
//            [['process_order'],'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['department_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'token'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getMobileTables() {
        return $this->hasMany(MobileTables::class, ['mobile_process_id' => 'id']);
    }

    public static function getListMap()
    {
        $tables = static::find()
            ->alias('mp')
            ->select(['mp.name', 'mp.id', 'department_id'])
            ->joinWith(['department hrd' => function($query) {
                $query->select(['hrd.id', 'hrd.name']);
            }])
            ->asArray()
            ->all();

        $result = ArrayHelper::map($tables, 'id', function ($item) {
            return $item['name'] . ' - ' . $item['department']['name'];
        });

        return $result;
    }

    public static function reorderProcessesFromBody(string $getRawBody)
    {
        $decodedBody = Json::decode($getRawBody);
        $flag = false;
        if (isset($decodedBody['items'])) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach($decodedBody['items'] as $item) {
                    $process = static::findOne(['id' => $item['id']]);
                    if ($process === null) {
                        $flag = false;
                        break;
                    }

                    $process->process_order = $item['index'];
                    $flag = $process->save();
                    if (!$flag) {
                        Yii::info($process->getErrors(), 'save');
                        $flag = false;
                        break;
                    }
                }

                if ($flag) {
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
            } catch (\Throwable $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage(), 'save');
                $flag = false;
            }
        }

        return $flag;
    }

    public static function getProcessIdByToken(string $token)
    {
        return static::find()
            ->select('id')
            ->andWhere(['token' => $token])
            ->scalar();
    }

    public static function getProcessesByDepartmentId($department_id)
    {
        return static::find()
            ->andWhere(['department_id' => $department_id])
            ->all();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'process_order' => Yii::t('app', 'Process order'),
            'department_id' => Yii::t('app', 'Department ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'type' => Yii::t('app','Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMobileProcessProductions()
    {
        return $this->hasMany(MobileProcessProduction::className(), ['mobile_process_id' => 'id']);
    }

    /**
     * @param int $toNumber
     * @return array
     */
    public static function generateNumberForOrder($toNumber = 100){
        $result = [];
        for ($i = 1; $i <= $toNumber; $i++) {
            $result[$i] = $i;
        }
        return $result;
    }

    public static function getSortableItems() {
        $items = [];
        foreach(self::getInstanceAsArray() as $id => $process) {
            $items[$id]['content'] = self::getOneContentForSortable($process);
            $items[$id]['options'] = [
                'data' => [
                    'id' => $id,
                ]
            ];
        }

        return $items;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getInstanceAsArray() {
        return static::find()
            ->with('department')
            ->orderBy('process_order')
            ->indexBy('id')
            ->asArray()
            ->all();
    }

    public static function getOneContentForSortable($process) {
        return "<div><strong>".Yii::t('app', 'Sort order')."</strong>: <code class='process-order'>" . $process['process_order'] . "</code></div>"
              ."<div><strong>".Yii::t('app', 'Department')."</strong>: " . $process['department']['name'] . "</div>"
              ."<div><strong>".Yii::t('app', 'Process')."</strong>: " . $process['name'] . "</div>";
    }

    public static function getProcessIdByUserId($currentUserId)
    {
        return HrEmployeeUsers::find()
            ->alias('heu')
            ->select('mp.id')
            ->innerJoin(['he' => 'hr_employee'], 'heu.hr_employee_id = he.id')
            ->innerJoin(['mtrhe' => 'mobile_tables_rel_hr_employee'], 'he.id = mtrhe.hr_employee_id and mtrhe.status = 1')
            ->innerJoin(['mt' => 'mobile_tables'], 'mtrhe.mobile_tables_id = mt.id')
            ->innerJoin(['mp' => 'mobile_process'], 'mt.mobile_process_id = mp.id')
            ->andWhere(['heu.users_id' => $currentUserId])
            ->scalar();
    }

    public static function getDepartmentIdByProcessId(int $processId)
    {
        return static::find()
            ->select('department_id')
            ->andWhere(['id' => $processId])
            ->scalar();
    }
}
