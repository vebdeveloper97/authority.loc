<?php

namespace app\modules\mobile\models;

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvTableRelWmsDoc;
use app\modules\bichuv\models\TikuvKonveyerBichuvGivenRolls;
use app\modules\hr\models\HrEmployeeUsers;
use app\modules\wms\models\WmsDocument;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mobile_tables".
 *
 * @property int $id
 * @property int $mobile_process_id
 * @property string $name
 * @property string $token
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property MobileProcess $mobileProcess
 * @property MobileTablesRelHrEmployee[] $mobileTablesRelHrEmployees
 * @property MobileTablesRelHrEmployee $activeResponsiblePerson
 */
class MobileTables extends \app\modules\mobile\models\BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mobile_tables';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mobile_process_id', 'name', 'token', ], 'required'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['token', 'unique'],
            [['mobile_process_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['mobile_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => MobileProcess::className(), 'targetAttribute' => ['mobile_process_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'mobile_process_id' => Yii::t('app', 'Process'),
            'name' => Yii::t('app', 'Name'),
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
    public function getMobileProcess()
    {
        return $this->hasOne(MobileProcess::className(), ['id' => 'mobile_process_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMobileTablesRelHrEmployees()
    {
        return $this->hasMany(MobileTablesRelHrEmployee::class, ['mobile_tables_id' => 'id']);
    }

    public function getActiveResponsiblePerson() {
        return $this->hasOne(MobileTablesRelHrEmployee::class, ['mobile_tables_id' => 'id'])
            ->andWhere(['is', 'end_date', new Expression('null')]);
    }

    /**
     * Department token va jarayon nomiga qarab stollarni (konveyerlarni) qaytaradi
     *
     * @param string $departmentToken
     * @param string $processToken
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTablesByDepartmentTokenAndProcessName(string $departmentToken, string $processToken)
    {
        $query = static::find()
            ->alias('mt')
            ->innerJoin(['mp' => 'mobile_process'], 'mt.mobile_process_id = mp.id')
            ->leftJoin(['hrd' => 'hr_departments'], 'mp.department_id = hrd.id')
            ->andWhere(['hrd.token' => $departmentToken])
            ->andWhere(['mp.token' => $processToken]);

        return $query->all();
    }

    public function getCurrentProcessViaUser(){
        $sql = "";
    }

    public static function getTableByToken(string $token) {
        $query = static::find()
            ->andWhere(['token' => $token]);

        return $query->one();
    }

    public static function getSliceKonveyerList($id)
    {
        $sql = "select 
                       bgr.id id,
                       bdt.name detail,
                       MIN(bgri.status) status
                from bichuv_given_roll_items bgri
                         left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                         left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id
                         left join tikuv_konveyer_bichuv_given_rolls tkbgr on bgr.id = tkbgr.bichuv_given_rolls_id
                WHERE bgri.bichuv_detail_type_id is not null AND tkbgr.bichuv_given_rolls_id is not null AND tkbgr.status < %d GROUP BY bgr.id,bgri.bichuv_detail_type_id
                LIMIT 2000;
            ";
        $finished = TikuvKonveyerBichuvGivenRolls::STATUS_FINISHED;
        $sql = sprintf($sql,$finished);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $list = [];
        if(!empty($res)) {
            foreach ($res as $n => $key) {
                $list[$key['id']]['status'] = (!empty($list[$key['id']]['status']))?$list[$key['id']]['status']:[];
                array_push($list[$key['id']]['status'], $key['status']);
                $fa = ($key['status']<3)?"<i class='fa fa-circle-o-notch fa-spin fa-fw'></i>":"<i class='fa fa-check'></i>";
                $list[$key['id']]['list'] .= "<div class='borderBottom'><span>{$fa}&nbsp;<b>{$key['detail']} </b></span></div>";

            }
        }
        $sql = "select bgr.nastel_party,
                       m.name musteri,
                       MAX(rm.name) mato,
                       MAX(ml.article) as model,
                       MAX(bgri.roll_count) as rulon_count,
                       max(bgri.required_count) as summa,
                       MAX(bgri.party_no) party_no,
                       bgr.id id,
                       tkbgr.status status,
                       tkbgr.indeks indeks,
                       MAX(mv.name) rang,
                        cp.code pantone
                from bichuv_given_roll_items bgri
                         left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                         left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id  
                         left join product p on bgri.model_id = p.id
                         left join bichuv_mato_info bmi ON bgri.entity_id = bmi.id
                         left join raw_material rm ON bmi.rm_id = rm.id
                         left join color c ON bmi.color_id = c.id
                         left join color_tone ct ON c.color_tone = ct.id
                         left join musteri m on bgr.musteri_id = m.id
                         left join tikuv_konveyer_bichuv_given_rolls tkbgr on bgr.id = tkbgr.bichuv_given_rolls_id
                         left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                         left join models_list ml on mrp.models_list_id = ml.id
                         left join models_variations mv on mrp.model_variation_id = mv.id
                         left join models_variation_colors mvc on mv.id = mvc.model_var_id
                         left join color_pantone cp on mvc.color_pantone_id = cp.id
                WHERE bgri.bichuv_detail_type_id is not null AND bgr.status = 3 AND tkbgr.mobile_tables_id = %d AND tkbgr.status < %d  GROUP BY bgr.id ORDER BY tkbgr.indeks ASC
                LIMIT 200;
            ";
        $sql = sprintf($sql,$id,$finished);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $items = [];
        foreach ($res as $n => $key) {
            $item_class = (!empty($list[$key['id']]['status'])&&min($list[$key['id']]['status'])==4)?"n_isready":"";
            $status = !empty($key['status'])?TikuvKonveyerBichuvGivenRolls::getClassList($key['status']):false;
            $class = ($status&&!is_array($status))?"bg-custom item_nastel {$item_class} {$status}":"bg-custom item_nastel {$item_class}";
            $content = "<div class='row' style='margin: 0 -5px;'>
                            <div class='col-md-8 noPaddingLeft n_nastel'>
                                <table class='table table-bordered nastel_table'>
                                    <thead>
                                        <tr>
                                            <th colspan='2' class='text-center'>
                                               <span class='badge pull-left number_list'>".++$n."</span>  <span class='nastel_no'>" .
                $key['nastel_party']
                . "</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class='n_model'>
                                            <td>" .
                Yii::t('app', 'Model nomi')
                . "</td>
                                            <td>" .
                $key['model']
                . "</td>
                                        </tr>
                                        <tr class='n_mato'>
                                            <td>" .
                Yii::t('app', 'Mato nomi')
                . "</td>
                                            <td>" .
                $key['mato']
                . "</td>
                                        </tr>
                                        <tr class='n_color'>
                                            <td>" .
                Yii::t('app', 'Rangi')
                . "</td>
                                            <td>" .
                $key['rang']." ".$key['pantone']
                . "</td>
                                        </tr>
                                    </tbody>
                                </table>
                        </div><div class='col-md-4 noPadding text-center flex-container nastel_detail {$item_class}'>".$list[$key['id']]['list']."</div></div>";
            $items[$key['id']] = [
                'content' => $content,
                'options' => [
                    'class' => $class ?? 'bg-red',
                    'data' => [
                        'id'=>$key['id'],
                        'parent' => $key['tkbd_id'],
                        'indeks' => $key['indeks'],
                    ],
                ],
            ];
        }
        return $items;
    }

    public static function getMobileTableByDepartment($id, $processToken = null){

        $tables = MobileTables::find()
            ->alias('mt')
            ->select(['mt.id','mt.name'])
            ->leftJoin(['mpr' => 'mobile_process'],'mt.mobile_process_id = mpr.id')
            ->where(['mpr.department_id' => $id]);

        if (!empty($processToken)){
            $tables->andWhere(['mpr.token' => $processToken]);
        }
        if (!empty($tables)){
            return $tables ->all();;
        }
        return false;
    }

    public static function getMobileTableByDepartmentMap($id, $processToken = null){
        $tables = self::getMobileTableByDepartment($id);
        if (!empty($processToken)){
            $tables = self::getMobileTableByDepartment($id,$processToken);
        }
        return ArrayHelper::map($tables, 'id', 'name');
    }

    public static function getMobileTableByHrEmployeeWithToken($token){

        $hrEmployees = self::find()
            ->alias('mt')
            ->select(['he.id','he.fish'])
            ->leftJoin(['mpr' => 'mobile_process'],'mt.mobile_process_id = mpr.id')
            ->leftJoin(['hd' => 'hr_departments'],'mpr.department_id = hd.id')
            ->leftJoin(['mtrhe' => 'mobile_tables_rel_hr_employee'],'mt.id = mtrhe.mobile_tables_id')
            ->leftJoin(['he' => 'hr_employee'],'mtrhe.hr_employee_id = he.id')
            ->where(['hd.token' => $token])
            ->andWhere(['mtrhe.status' => 1])
            ->asArray()
            ->all();
        if (!empty($hrEmployees)){
            return ArrayHelper::map($hrEmployees,'id','fish');
        }
        return [];
    }

    public static function getProcessIdById($mobile_tables_id)
    {
        return static::find()
            ->select('mobile_process_id')
            ->andWhere(['id' => $mobile_tables_id])
            ->scalar();
    }

    public static function getTableByUserIdAndProcessToken($userId, $processToken)
    {
        $processId = MobileProcess::getProcessIdByToken($processToken);
        $query = static::find()
            ->alias('mt')
            ->innerJoin(['mtrhe' => 'mobile_tables_rel_hr_employee'], 'mt.id = mtrhe.mobile_tables_id and mtrhe.status = 1')
            ->innerJoin(['he' => 'hr_employee'], 'mtrhe.hr_employee_id = he.id')
            ->innerJoin(['heu' => 'hr_employee_users'], 'heu.hr_employee_id = he.id')
            ->andWhere([
                'heu.users_id' => $userId,
                'mt.mobile_process_id' => $processId,
            ]);
        return $query->one();
    }



    public static function getNextTableInstanceByProcessId($processId)
    {
        return static::find()
            ->alias('mt')
            ->andWhere(['mt.mobile_process_id' => $processId])
            ->one();
    }
}
