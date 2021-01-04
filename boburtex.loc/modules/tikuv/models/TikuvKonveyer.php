<?php

namespace app\modules\tikuv\models;

use app\models\Users;
use app\modules\bichuv\models\TikuvKonveyerBichuvGivenRolls;
use app\modules\toquv\models\ToquvDepartments;
use Yii;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tikuv_konveyer".
 *
 * @property int $id
 * @property int $number
 * @property string $code
 * @property string $name
 * @property int $users_id
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $dept_id
 *
 * @property Users $users
 * @property ToquvDepartments $dept
 */
class TikuvKonveyer extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_konveyer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'users_id', 'status', 'created_by', 'created_at', 'updated_at', 'dept_id'], 'integer'],
            [['add_info'], 'string'],
            [['code'], 'string', 'max' => 30],
            [['name'], 'string', 'max' => 50],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['users_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'number' => Yii::t('app', 'Number'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'users_id' => Yii::t('app', 'Users ID'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'dept_id' => Yii::t('app', 'Department'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }/**
     * @return \yii\db\ActiveQuery
     */
    public function getDept()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'dept_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvKonveyerBichuvGivenRolls()
    {
        return $this->hasMany(TikuvKonveyerBichuvGivenRolls::className(), ['tikuv_konveyer_id' => 'id']);
    }
    public static function getSliceList()
    {
        $sql = "select 
                       bgr.id id,
                       bdt.name detail,
                       MIN(bgri.status) status
                from bichuv_given_roll_items bgri
                         left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                         left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id
                         left join tikuv_konveyer_bichuv_given_rolls tkbgr on bgr.id = tkbgr.bichuv_given_rolls_id
                WHERE bgri.bichuv_detail_type_id is not null AND tkbgr.bichuv_given_rolls_id is null AND bgr.status = 3 GROUP BY bgr.id,bgri.bichuv_detail_type_id ORDER BY bgr.id ASC
                LIMIT 1000;
            ";
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
                       MAX(mv.name) rang
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
                WHERE bgri.bichuv_detail_type_id = 1 AND tkbgr.bichuv_given_rolls_id is null AND bgr.status = 3 GROUP BY bgr.id ORDER BY bgr.id ASC
                LIMIT 40;
            ";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $items = [];
        foreach ($res as $key) {
            $class = (!empty($list[$key['id']]['status'])&&min($list[$key['id']]['status'])==4)?"bg-green":"";
            $content = "<div class='row {$class}' style='margin: 0 -5px;'><div class='col-md-8 noPaddingLeft'> <table class='table table-bordered nastel_table'> <thead> <tr> <th colspan='2' class='text-center'><span class='badge pull-left number_list'></span><span class='nastel_no'>" . $key['nastel_party'] . "</span></th> </tr> </thead> <tbody> <tr> <td>" . Yii::t('app', 'Buyurtmachi') . "</td> <td>" . $key['musteri'] . "</td> </tr> <tr> <td>" . Yii::t('app', 'Model nomi') . "</td> <td>" . $key['model'] . "</td> </tr> <tr> <td>" . Yii::t('app', 'Mato nomi') . "</td> <td>" . $key['mato'] . "</td> </tr> <tr> <td>" . Yii::t('app', 'Rangi') . "</td> <td>" . $key['rang'] . "</td> </tr> </tbody> </table> </div><div class='col-md-4 noPadding text-center flex-container nastel_detail'>".$list[$key['id']]['list']."</div></div>";
            $items[$key['id']] = [
                'content' => $content,
                'options' => [
                    'data' => [
                        'id'=>$key['id']
                    ],
                ],
            ];
        }
        return $items;
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
                        tdi.status tikuv_status,
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
                         LEFT JOIN (SELECT tdi.nastel_party_no,td.status FROM tikuv_doc_items tdi 
                                    LEFT JOIN tikuv_doc td ON td.id = tdi.tikuv_doc_id
                                    WHERE td.document_type = 7 GROUP BY tdi.nastel_party_no
                         ) tdi ON tdi.nastel_party_no = bgr.nastel_party
                WHERE bgri.bichuv_detail_type_id is not null AND bgr.status = 3 AND tkbgr.tikuv_konveyer_id = %d AND tkbgr.status < %d  GROUP BY bgr.id ORDER BY tdi.status DESC,tkbgr.indeks ASC
                LIMIT 200;
            ";
        $sql = sprintf($sql,$id,$finished);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $items = [];
        foreach ($res as $n => $key) {
            $item_class = (!empty($list[$key['id']]['status'])&&min($list[$key['id']]['status'])==4)?"n_isready":"";
            $status = !empty($key['status'])?TikuvKonveyerBichuvGivenRolls::getClassList($key['status']):false;
            $class = ($status&&!is_array($status))?"bg-custom item_nastel {$item_class} {$status}":"bg-custom item_nastel {$item_class}";
            $check_tikuv = ($key['tikuv_status']==3)?'&#10004;':'';
            $content = "<div class='row' style='margin: 0 -5px;'>
                            <div class='col-md-8 noPaddingLeft n_nastel'>
                                <table class='table table-bordered nastel_table'>
                                    <thead>
                                        <tr>
                                            <th colspan='2' class='text-center'>
                                               <span class='badge pull-left number_list'>".++$n."</span> ".$check_tikuv."  <span class='nastel_no'>" .
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
    public static function getSliceSearch($query='',$arr=null)
    {
        $q = '';
        if(!empty($query)){
            $q = " AND (bgr.nastel_party LIKE '%{$query}%' OR m.name LIKE '%{$query}%' OR p.name LIKE '%{$query}%')";
        }
        $list_search = ($arr)?" AND bgr.id NOT IN ({$arr})":"";
        $sql = "select 
                       bgr.id id,
                       bdt.name detail,
                       MIN(bgri.status) status
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
                WHERE bgri.bichuv_detail_type_id is not null AND tkbgr.bichuv_given_rolls_id is null AND bgr.status = 3 %s %s GROUP BY bgr.id,bgri.bichuv_detail_type_id
                LIMIT 1000;
            ";
        $sql = sprintf($sql,$q,$list_search);
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
                       MAX(mv.name) rang
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
                WHERE tkbgr.bichuv_given_rolls_id is null AND bgr.status = 3 %s %s GROUP BY bgr.id
                LIMIT 40;
            ";
        $sql = sprintf($sql,$q,$list_search);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $content = "";
        foreach ($res as $n => $key) {
            $class = (min($list[$key['id']]['status'])==4)?"bg-green":"";
            $content .= "<li class='bg-nastel item_nastel' data-id='".$key['id']."' data-parent='1' draggable='true' role='option' aria-grabbed='false'> <div class='row' style='margin: 0 -5px;'> <div class='col-md-8 noPaddingLeft'> <table class='table table-bordered nastel_table'> <thead> <tr> <th colspan='2' class='text-center'> <span class='badge pull-left number_list {$class}'>" . ++$n . "</span>  <span class='nastel_no'>" . $key['nastel_party'] . "</span></th> </tr> </thead> <tbody> <tr> <td>" . Yii::t('app', 'Buyurtmachi') . "</td> <td>" . $key['musteri'] . "</td> </tr> <tr> <td>" . Yii::t('app', 'Model nomi') . "</td> <td>" . $key['model'] . "</td> </tr> <tr> <td>" . Yii::t('app', 'Mato nomi') . "</td> <td>" . $key['mato'] . "</td> </tr> <tr> <td>" . Yii::t('app', 'Rangi') . "</td> <td>" . $key['rang'] . "</td> </tr> </tbody> </table> </div><div class='col-md-4 noPadding text-center flex-container nastel_detail'>".$list[$key['id']]['list']."</div></div></li>";
        }
        return $content;
    }
    public static function getDetailList($id)
    {
        $sql = "select 
                       bgr.id id,
                       bdt.name detail,
                       MIN(bgri.status) status
                from bichuv_given_roll_items bgri
                         left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                         left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id
                WHERE bgr.id = {$id} AND bgri.bichuv_detail_type_id is not null GROUP BY bgri.bichuv_detail_type_id
                LIMIT 40;
            ";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $list = [];
        $content = "";
        if(!empty($res)) {
            $min = 0;
            $status = 0;
            foreach ($res as $key) {
                if($min>$key['status']||$status == 0){
                    $status = $key['status'];
                }else{
                    $status = $key['status'];
                }
                $min = $status;
                $fa = ($key['status']<3)?"<i class='fa fa-circle-o-notch fa-spin fa-fw'></i>":"<i class='fa fa-check'></i>";
                $content .= "<div class='borderBottom'><span>{$fa}&nbsp;<b>{$key['detail']} </b></span></div>";
                
            }
        }
        $list['content'] = $content;
//        return $min;
        return $content;
    }
    public static function getList($id)
    {
        $sql = "select 
                       bgr.id id,
                       bdt.name detail,
                       MIN(bgri.status) status
                from bichuv_given_roll_items bgri
                         left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                         left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id
                         left join tikuv_konveyer_bichuv_given_rolls tkbgr on bgr.id = tkbgr.bichuv_given_rolls_id
                WHERE bgri.bichuv_detail_type_id is not null AND tkbgr.bichuv_given_rolls_id is not null GROUP BY bgr.id,bgri.bichuv_detail_type_id
                LIMIT 2000;
            ";
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
                       bgri.entity_id,
                       m.name musteri,
                       MAX(rm.name) mato,
                       MAX(p.name) as model,
                       MAX(bgri.roll_count) as rulon_count,
                       max(bgri.required_count) as summa,
                       MAX(bgri.party_no) party_no,
                       bgr.id id,
                       MAX(ct.name) rang
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
                WHERE bgri.bichuv_detail_type_id is not null AND bgr.status = 3 AND tkbgr.tikuv_konveyer_id = %d  GROUP BY bgr.id ORDER BY tkbgr.indeks ASC
                LIMIT 200;
            ";
        $sql = sprintf($sql,$id);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $items = [];
        $items['list'] = $res;
        $items['child'] = $list;
        /*foreach ($res as $n => $key) {
            $class = (min($list[$key['id']]['status'])==4)?"bg-green":"";
            $content = "<div class='row {$class}' style='margin: 0 -5px;'>
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
                                        <tr class='n_musteri'>
                                            <td>" .
                Yii::t('app', 'Buyurtmachi')
                . "</td>
                                            <td>" .
                $key['musteri']
                . "</td>
                                        </tr>
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
                $key['rang']
                . "</td>
                                        </tr>
                                    </tbody>
                                </table>
                        </div><div class='col-md-4 noPadding text-center flex-container nastel_detail'>".$list[$key['id']]['list']."</div></div>";
            $items[$key['id']] = [
                'content' => $content,
                'options' => [
                    'data' => [
                        'id'=>$key['id'],
                        'parent' => $key['tkbd_id'],
                        'indeks' => $key['indeks'],
                    ],
                ],
            ];
        }*/
        return $items;
    }
}
