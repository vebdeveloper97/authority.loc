<?php

namespace app\modules\bichuv\models;

use app\modules\admin\models\ToquvUserDepartment;
use Yii;
use yii\behaviors\TimestampBehavior;
use app\components\OurCustomBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class BaseModel extends ActiveRecord
{
    const STATUS_ACTIVE     = 1;
    const STATUS_INACTIVE   = 2;
    const STATUS_SAVED      = 3;
    const MAX_NASTEL_STOL   = 25;

    const TOKEN_BEKA = 'BEKA';
    const TOKEN_MAIN = 'MAIN';
    const TOKEN_ACCESSORY = 'ACCESSORY';
    public $cp = [];

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }

    public function afterValidate()
    {
        if($this->hasErrors()){
            $res = [
                'status' => 'error',
                'module' => 'Bichuv',
                'table' => self::tableName() ?? '',
                'url' => \yii\helpers\Url::current([], true),
                'message' => $this->getErrors(),
                'data' => $this->toArray(),
            ];
            Yii::error($res, 'save');
        }
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getStatusList($key = null){
        $result = [
            self::STATUS_ACTIVE   => Yii::t('app','Active'),
            self::STATUS_INACTIVE => Yii::t('app','Deleted'),
            self::STATUS_SAVED => Yii::t('app','Saved')
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    /**
     * @param $id
     * @param bool $isMultiple
     * @param array $ids
     * @return string|null
     * @throws \yii\db\Exception
     */
    public function getMatoName($id, $isMultiple = false, $ids = []){

        if($isMultiple){
            $ids = join(',', $ids);
            $sql = "select wmi.id as mato_id,
                       rmt.name     as mato,
                       tn.name as ne,
                       tt.name    as thread,
                       tpf.name     as pus_fine,
                       c.color_id,
                       c.pantone,
                       ct.name     as ctone
                from  wms_mato_info wmi 
                             left join toquv_raw_materials trm on wmi.toquv_raw_materials_id = trm.id
                             left join raw_material_type rmt on trm.raw_material_type_id = rmt.id
                             left join toquv_raw_material_ip trmi on trm.id = trmi.toquv_raw_material_id
                             left join toquv_ne tn on trmi.ne_id = tn.id
                             left join toquv_thread tt on trmi.thread_id = tt.id
                             left join toquv_pus_fine tpf on wmi.pus_fine_id = tpf.id
                             left join color c on wmi.wms_color_id = c.id
                             left join color_tone ct on c.color_tone = ct.id
                where wmi.id IN (%s);";
            $sql = sprintf($sql, $ids);
            $results = Yii::$app->db->createCommand($sql)->queryAll();
            return ArrayHelper::map($results,'mato_id', function($m){
               return "{$m['mato']} {$m['ne']} {$m['thread']} {$m['pus_fine']} {$m['ctone']} {$m['color_id']} {$m['pantone']}";
            });
        }
        $sql = "select wmi.id as mato_id,
                       rmt.name     as mato,
                       tn.name as ne,
                       tt.name    as thread,
                       tpf.name     as pus_fine,
                       c.color_id,
                       c.pantone,
                       ct.name     as ctone
                from  wms_mato_info wmi 
                             left join toquv_raw_materials trm on wmi.toquv_raw_materials_id = trm.id
                             left join raw_material_type rmt on trm.raw_material_type_id = rmt.id
                             left join toquv_raw_material_ip trmi on trm.id = trmi.toquv_raw_material_id
                             left join toquv_ne tn on trmi.ne_id = tn.id
                             left join toquv_thread tt on trmi.thread_id = tt.id
                             left join toquv_pus_fine tpf on wmi.pus_fine_id = tpf.id
                             left join color c on wmi.wms_color_id = c.id
                             left join color_tone ct on c.color_tone = ct.id
                where wmi.id = :entityId;";

        $data = Yii::$app->db->createCommand($sql)->bindValues([
            'entityId' => $id,
        ])->queryOne();
        if(!empty($data)){
            return "{$data['mato']} {$data['ne']} {$data['thread']} {$data['pus_fine']} {$data['ctone']} {$data['color_id']} {$data['pantone']}";
        }
        return null;
    }

    public function getTableList($withTypes=false){
        if($withTypes){
            $res = BichuvTables::find()->joinWith('bichuvProcesses')->asArray()->all();
            return ArrayHelper::map($res, 'id', function($model){
                return "<b>{$model['name']}</b> ({$model['bichuvProcesses']['name']})";
            });
        }
        $res = BichuvTables::find()->asArray()->all();
        return ArrayHelper::map($res, 'id', 'name');
    }
    public function getUserTableList($id,$list=false,$process=null){
        $res = BichuvTables::find()->where(['bichuv_tables.status'=>1]);
        if($process){
            $res = $res->andWhere(['bichuv_processes_id'=>$process]);
        }else{
            $processes = BichuvProcessesUsers::find()->select('bichuv_processes_id')->where(['users_id'=>$id]);
            $res = $res->andWhere(['bichuv_processes_id'=>$processes]);
        }
        $res = $res->asArray()->all();
        if($list){
            return $res;
        }
        return ArrayHelper::map($res, 'id', 'name');
    }
    public function getProcessesList($withTypes=false){
        if($withTypes){
            $res = BichuvProcesses::find()->asArray()->all();
            return ArrayHelper::map($res, 'id', function($model){
                return "<b>{$model['name']}</b>";
            });
        }
        $res = BichuvProcesses::find()->asArray()->all();
        return ArrayHelper::map($res, 'id', 'name');
    }
    public function getUserProcessList($id,$list=false){
        $res = BichuvProcesses::find()->joinWith(['bichuvProcessesUsers'])->where(['users_id'=>$id]);
        $res = $res->asArray()->all();
        if($list){
            return $res;
        }
        return ArrayHelper::map($res, 'id', 'name');
    }
    /**
     * @param null $key
     * @param bool $withAttr
     * @param array $token
     * @return array
     */
    public function getDetailTypeList($key = null, $withAttr = false, $token = []){
        if(!empty($token)){
            $res = BichuvDetailTypes::find()->where(['in', 'token', $token])->asArray()->orderBy(['type_order' => SORT_ASC])->all();
        }else{
            $res = BichuvDetailTypes::find()->asArray()->orderBy(['type_order' => SORT_ASC])->all();
        }
        $result = [];
        if($withAttr && empty($key)){
            if(!empty($res)){
                foreach ($res as $item) {
                    $result['data'][$item['id']] = $item['name'];
                    $result['dataAttr'][$item['id']] = ['data-token' => $item['token']];
                }
            }
            return $result;
        }
        if($key){
            return $res[$key];
        }
        return ArrayHelper::map($res,'id','name');
    }

    /**
     * @param int $limit
     * @param bool $withAttr
     * @return array
     * @throws \yii\db\Exception
     */
    public function getRmListForPlan($limit = 60, $withAttr = false){

        $sql = "select bgr.id,
                       bgri.id as item_id, 
                       bgr.nastel_party,
                       bgri.entity_id,
                       rm.name as mato,
                       rm.name     as mato,
                       nename.name as ne,
                       thr.name    as thread,
                       pf.name     as pus_fine,
                       c.color_id,
                       c.pantone,
                       ct.name     as ctone
                from bichuv_given_rolls bgr
                left join bichuv_given_roll_items bgri on bgr.id = bgri.bichuv_given_roll_id
                left join bichuv_mato_info bmi on bgri.entity_id = bmi.id
                left join raw_material rm on bmi.rm_id = rm.id
                left join ne nename on nename.id = bmi.ne_id
                left join pus_fine pf on pf.id = bmi.pus_fine_id
                left join thread thr on thr.id = bmi.thread_id
                left join color c on bmi.color_id = c.id
                left join color_tone ct on c.color_tone = ct.id
                where bgr.status = 3 ORDER BY bgri.id DESC LIMIT %d;";

        $sql = sprintf($sql, $limit);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $out = [];

        if($withAttr){
            foreach ($results as $result) {
                $name = "({$result['nastel_party']}) {$result['mato']}-{$result['ne']}-{$result['thread']}|{$result['pus_fine']}";
                $out['data'][$result['item_id']] = $name;
                $out['dataAttr'][$result['item_id']] = [
                    'data-nastel-no' => $result['nastel_party'],
                    'data-entity-id' => $result['entity_id']
                ];
            }
            return $out;
        }
        return $results;
    }

    /**
     * @param $user_id
     * @param null $type
     * @param bool $isMultiple
     * @return array|null
     */
    public function getUserDepartmentByUserId($user_id, $type = null, $isMultiple = false)
    {
        if ( is_null($type) ) {
            $type = ToquvUserDepartment::OWN_DEPARTMENT_TYPE;
        }
        if ($user_id) {
            $result = ToquvUserDepartment::find()
                ->select(['td.id', 'td.name'])
                ->from('toquv_user_department tud')
                ->innerJoin('toquv_departments td', '`td`.`id` = `tud`.`department_id`')
                ->where(['tud.user_id' => $user_id, 'tud.type' => $type])
                ->asArray()
                ->all();

            if (!empty($result)) {
                return ArrayHelper::map($result, 'id', 'name');
            }
        }
        return [];
    }
}