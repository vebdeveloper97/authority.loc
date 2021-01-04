<?php

namespace app\modules\bichuv\models;

use app\components\OurCustomBehavior;
use app\models\Constants;
use app\modules\mobile\models\MobileTables;
use app\modules\tikuv\models\TikuvKonveyer;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tikuv_konveyer_bichuv_given_rolls".
 *
 * @property int $tikuv_konveyer_id
 * @property int $mobile_tables_id
 * @property int $bichuv_given_rolls_id
 * @property double $indeks
 * @property int $created_by
 * @property int $status
 * @property int $updated_at
 * @property int $created_at
 *
 * @property BichuvGivenRolls $bichuvGivenRolls
 * @property TikuvKonveyer $tikuvKonveyer
 * @property MobileTables $mobileTable
 * @property int $updated_by [int(11)]
 */
class TikuvKonveyerBichuvGivenRolls extends BaseModel
{
    const STATUS_ACTIVE = 1;
    const STATUS_ACCEPTED = 2;
    const STATUS_STARTED = 3;
    const STATUS_PAUSE = 4;
    const STATUS_FINISHED = 5;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_konveyer_bichuv_given_rolls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mobile_tables_id', 'bichuv_given_rolls_id'], 'required'],
            [['tikuv_konveyer_id', 'mobile_tables_id', 'bichuv_given_rolls_id', 'created_by', 'updated_by', 'status', 'updated_at', 'created_at'], 'integer'],
            [['indeks'], 'number'],
            [['tikuv_konveyer_id', 'bichuv_given_rolls_id'], 'unique', 'targetAttribute' => ['tikuv_konveyer_id', 'bichuv_given_rolls_id']],
            [['bichuv_given_rolls_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvGivenRolls::className(), 'targetAttribute' => ['bichuv_given_rolls_id' => 'id']],
//            [['tikuv_konveyer_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvKonveyer::className(), 'targetAttribute' => ['tikuv_konveyer_id' => 'id']],
            [['mobile_tables_id'], 'exist', 'skipOnError' => true, 'targetClass' => MobileTables::className(), 'targetAttribute' => ['mobile_tables_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tikuv_konveyer_id' => Yii::t('app', 'Tikuv Konveyer'),
            'mobile_tables_id' => Yii::t('app', 'Tables'),
            'bichuv_given_rolls_id' => Yii::t('app', 'Bichuv Given Rolls'),
            'indeks' => Yii::t('app', 'Indeks'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }
    public static function getClassList($key=false)
    {
        $result = [
            self::STATUS_ACTIVE   => 'n_active',
            self::STATUS_ACCEPTED => 'n_accepted',
            self::STATUS_STARTED => 'n_started',
            self::STATUS_PAUSE => 'n_pause',
            self::STATUS_FINISHED => 'n_finished'
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }
    public static function getColorList($key=false)
    {
        $result = [
            self::STATUS_ACTIVE   => 'n_active',
            self::STATUS_ACCEPTED => 'n_accepted',
            self::STATUS_STARTED => 'n_started',
            self::STATUS_PAUSE => 'n_pause',
            self::STATUS_FINISHED => 'n_finished'
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvGivenRolls()
    {
        return $this->hasOne(BichuvGivenRolls::className(), ['id' => 'bichuv_given_rolls_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvKonveyer()
    {
        return $this->hasOne(TikuvKonveyer::className(), ['id' => 'tikuv_konveyer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMobileTable()
    {
        return $this->hasOne(MobileTables::className(), ['id' => 'mobile_tables_id']);
    }

    public static function getStartTableId() {
        $tayyorlovQabulKesimTable = MobileTables::getTableByToken(Constants::TOKEN_TAYYORLOV_ACCEPT_SLICE);
        $tableId =  isset($tayyorlovQabulKesimTable['id']) ? $tayyorlovQabulKesimTable['id'] : null;
        if ($tableId === null) {
            Yii::error(Constants::TOKEN_TAYYORLOV_ACCEPT_SLICE . ' tokenli table mavjud emas', 'error');
        }
        return $tableId;
    }


    public static function getSliceList()
    {
        $tableId = self::getStartTableId();

        $sql = "select 
                       bgr.id id,
                       bdt.name detail,
                       MIN(bgri.status) status
                from bichuv_given_roll_items bgri
                         left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                         left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id
                         left join tikuv_konveyer_bichuv_given_rolls tkbgr on bgr.id = tkbgr.bichuv_given_rolls_id
                        inner join mobile_process_production mpp on bgr.nastel_party = mpp.nastel_no
                WHERE bgri.bichuv_detail_type_id is not null 
                  AND tkbgr.bichuv_given_rolls_id is null 
                  AND bgr.status = 3
                  AND mpp.mobile_tables_id = :tableId
                GROUP BY bgr.id,bgri.bichuv_detail_type_id 
                ORDER BY bgr.id ASC
                LIMIT 1000;
            ";
        $res = Yii::$app->db->createCommand($sql)->bindParam(':tableId', $tableId)->queryAll();
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
                         inner join mobile_process_production mpp on bgr.nastel_party = mpp.nastel_no
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
                WHERE bgri.bichuv_detail_type_id = 1 
                  AND tkbgr.bichuv_given_rolls_id is null 
                  AND bgr.status = 3 
                  AND mpp.mobile_tables_id = :tableId
                GROUP BY bgr.id 
                ORDER BY bgr.id ASC
                LIMIT 40;
            ";
        $res = Yii::$app->db->createCommand($sql)->bindParam(':tableId', $tableId)->queryAll();
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


    public static function getSliceSearch($query='',$arr=null)
    {
        $tableId = self::getStartTableId();

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
                         inner join mobile_process_production mpp on bgr.nastel_party = mpp.nastel_no
                         left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id
                         left join product p on bgri.model_id = p.id
                         left join bichuv_mato_info bmi ON bgri.entity_id = bmi.id
                         left join raw_material rm ON bmi.rm_id = rm.id
                         left join color c ON bmi.color_id = c.id
                         left join color_tone ct ON c.color_tone = ct.id
                         left join musteri m on bgr.musteri_id = m.id
                         left join tikuv_konveyer_bichuv_given_rolls tkbgr on bgr.id = tkbgr.bichuv_given_rolls_id
                WHERE bgri.bichuv_detail_type_id is not null 
                  AND tkbgr.bichuv_given_rolls_id is null 
                  AND bgr.status = 3 %s %s 
                   AND mpp.mobile_tables_id = :tableId
                GROUP BY bgr.id,bgri.bichuv_detail_type_id
                LIMIT 1000;
            ";
        $sql = sprintf($sql,$q,$list_search);
        $res = Yii::$app->db->createCommand($sql)->bindParam(':tableId', $tableId)->queryAll();
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
                         inner join mobile_process_production mpp on bgr.nastel_party = mpp.nastel_no
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
                WHERE tkbgr.bichuv_given_rolls_id is null 
                  AND bgr.status = 3 %s %s 
                   AND mpp.mobile_tables_id = :tableId

                GROUP BY bgr.id
                LIMIT 40;
            ";
        $sql = sprintf($sql,$q,$list_search);
        $res = Yii::$app->db->createCommand($sql)->bindParam(':tableId', $tableId)->queryAll();
        $content = "";
        foreach ($res as $n => $key) {
            $class = (min($list[$key['id']]['status'])==4)?"bg-green":"";
            $content .= "<li class='bg-nastel item_nastel' data-id='".$key['id']."' data-parent='1' draggable='true' role='option' aria-grabbed='false'> <div class='row' style='margin: 0 -5px;'> <div class='col-md-8 noPaddingLeft'> <table class='table table-bordered nastel_table'> <thead> <tr> <th colspan='2' class='text-center'> <span class='badge pull-left number_list {$class}'>" . ++$n . "</span>  <span class='nastel_no'>" . $key['nastel_party'] . "</span></th> </tr> </thead> <tbody> <tr> <td>" . Yii::t('app', 'Buyurtmachi') . "</td> <td>" . $key['musteri'] . "</td> </tr> <tr> <td>" . Yii::t('app', 'Model nomi') . "</td> <td>" . $key['model'] . "</td> </tr> <tr> <td>" . Yii::t('app', 'Mato nomi') . "</td> <td>" . $key['mato'] . "</td> </tr> <tr> <td>" . Yii::t('app', 'Rangi') . "</td> <td>" . $key['rang'] . "</td> </tr> </tbody> </table> </div><div class='col-md-4 noPadding text-center flex-container nastel_detail'>".$list[$key['id']]['list']."</div></div></li>";
        }
        return $content;
    }

    /**
     * @param string $nastelNo
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getInstanceByNastelNo(string $nastelNo) {
        $query = static::find()
            ->alias('tkbgr')
            ->leftJoin(['bgr' => 'bichuv_given_rolls'], 'tkbgr.bichuv_given_rolls_id = bgr.id')
            ->andWhere(['bgr.nastel_party' => $nastelNo]);

        return $query->one();
    }

    public function changeStatus(int $status)
    {
        $this->status = $status;
        return $this->save();
    }
}
