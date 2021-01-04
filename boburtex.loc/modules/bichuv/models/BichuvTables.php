<?php

namespace app\modules\bichuv\models;

use app\models\Users;
use app\modules\wms\models\WmsDocument;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_tables".
 *
 * @property int $id
 * @property string $name
 * @property int $bichuv_processes_id
 * @property int $type
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property string $slug
 *
 * @property BichuvProcesses $bichuvProcesses
 */
class BichuvTables extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_tables';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_processes_id', 'type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['add_info'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['slug'], 'string', 'max' => 255],
            [ 'slug', 'unique'],
            [['bichuv_processes_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvProcesses::className(), 'targetAttribute' => ['bichuv_processes_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'bichuv_processes_id' => Yii::t('app', 'Bichuv Processes ID'),
            'type' => Yii::t('app', 'Type'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'slug' => Yii::t('app', 'Slug'),
        ];
    }
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => SluggableBehavior::className(),
                    'attribute' => 'name',
                    'ensureUnique' => true
                ]
            ]
        );
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvProcesses()
    {
        return $this->hasOne(BichuvProcesses::className(), ['id' => 'bichuv_processes_id']);
    }

    public function getProcessList()
    {
        $list = BichuvProcesses::find()->asArray()->all();
        return ArrayHelper::map($list, 'id', 'name');
    }

    public function getBichuvTablesUsers()
    {
        return $this->hasMany(BichuvTablesUsers::className(), ['bichuv_tables_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['id' => 'users_id'])->viaTable('bichuv_tables_users', ['bichuv_tables_id' => 'id']);
    }


    public static function getBichuvPlanList(){

        $docType = WmsDocument::DOCUMENT_REQUEST_CARD;
        $statusActive = BichuvDoc::STATUS_ACTIVE;
        $statusMoved = BichuvDoc::STATUS_SAVED;
        $plan = BichuvTableRelWmsDoc::find()->asArray()->all();
        $planIds = ArrayHelper::getColumn($plan,'wms_doc_id');

        $query = WmsDocument::find()
            ->alias('wd')
            ->select(["wd.id", "bnl.name as nastel_no", "wd.id", "ml.name as model", "mv.name as model_var", "mv.code as code", "wd.status","m.name as musteri"])
            ->leftJoin(['bnl' => 'bichuv_nastel_lists'],'wd.bichuv_nastel_list_id = bnl.id')
            ->leftJoin(['wdi' => 'wms_document_items'],'wd.id = wdi.wms_document_id')
            ->leftJoin(['moi' => 'model_orders_items'],'wdi.model_orders_items_id = moi.id')
            ->leftJoin(['mo' => 'model_orders'],'moi.model_orders_id = mo.id')
            ->leftJoin(['m' => 'musteri'],'mo.musteri_id = m.id')
            ->leftJoin(['ml' => 'models_list'],'moi.models_list_id = ml.id')
            ->leftJoin(['mv' => 'models_variations'],'moi.model_var_id = mv.id')
            ->where(['wd.document_type' => $docType, 'wd.status' => $statusActive])
            ->groupBy(['bnl.name']);

        if(!empty($planIds)){
            $query = $query->andWhere(["NOT IN",'wd.id', $planIds]);
        }
        $list = $query->asArray()->all();

        $items = [];
        foreach ($list as $key) {
            $class = (!empty($key['status']) && $key['status'] == 3) ? "bg-green" : "";
            $content = "
            <div class='row {$class}' style='margin: 0 -5px;'>
                <div class='col-md-8 noPaddingLeft'> 
                    <table class='table table-bordered nastel_table'>
                         <thead>
                              <tr>
                                    <th colspan='2' class='text-center'>
                                       <span class='badge pull-left number_list'></span>
                                       <span class='nastel_no'>" . $key['nastel_no'] . "</span>
                                   </th> 
                               </tr> 
                         </thead>
                         <tbody> 
                                <tr> 
                                    <td>" . Yii::t('app', 'Buyurtmachi') . "</td> 
                                    <td>" . $key['musteri'] . "</td> 
                                </tr> 
                                <tr> 
                                    <td>" . Yii::t('app', 'Model nomi') . "</td> 
                                    <td>" . $key['model'] . "</td> 
                                </tr>
                                <tr> 
                                    <td>" . Yii::t('app', 'Rangi') . "</td> 
                                    <td>" . $key['model_var'] ." (".$key['code'].")"."</td> 
                                 </tr> 
                           </tbody> 
                       </table>
                    </div>
                    <div class='col-md-4 noPadding text-center flex-container nastel_detail'>".$list[$key['id']]['list']."</div>
                </div>";
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


    public static function getMatoTableList($id)
    {

        $finished = BichuvTableRelWmsDoc::STATUS_FINISHED;
        $doubleSql = BichuvTableRelWmsDoc::find()->select(['status','wms_doc_id as id'])->where(['<','status' , $finished])->asArray()->all();

        $list = [];

        if(!empty($doubleSql)) {
            foreach ($doubleSql as $n => $key) {
                $list[$key['id']]['status'] = (!empty($list[$key['id']]['status'])) ? $list[$key['id']]['status'] : [];
                array_push($list[$key['id']]['status'], $key['status']);
                $fa = ($key['status']<3)?"<i class='fa fa-circle-o-notch fa-spin fa-fw'></i>":"<i class='fa fa-check'></i>";
                $list[$key['id']]['list'] .= "<div class='borderBottom'><span>{$fa}&nbsp;<b>{$key['detail']} </b></span></div>";
            }
        }
        $sql = " SELECT 
                 bnl.name as nastel_no,
                 ml.name as model,
                 CONCAT(mv.name,' (',mv.code,')') as rang,
                 wd.id as id,
                 btrwd.indeks as indeks,
                 btrwd.status as btrwd_status,
                 wd.status
             FROM bichuv_table_rel_wms_doc btrwd
                LEFT JOIN wms_document wd ON btrwd.wms_doc_id = wd.id
                LEFT JOIN bichuv_nastel_lists bnl ON wd.bichuv_nastel_list_id = bnl.id
                LEFT JOIN wms_document_items wdi ON wd.id = wdi.wms_document_id
                LEFT JOIN model_orders_items moi ON wdi.model_orders_items_id = moi.id
                LEFT JOIN models_list ml ON moi.models_list_id = ml.id
                LEFT JOIN models_variations mv ON model_var_id = mv.id
                WHERE btrwd.status < {$finished}
                     AND btrwd.bichuv_table_id = %d  
                GROUP BY wd.id
                ORDER BY btrwd.indeks ASC
             ";
        $sql = sprintf($sql,$id);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $items = [];
        foreach ($res as $n => $key) {
            $item_class = (!empty($list[$key['id']]['status']) && min($list[$key['id']]['status']) == 4) ? "n_isready" : "";
            $status = !empty($key['btrwd_status']) ? TikuvKonveyerBichuvGivenRolls::getClassList($key['btrwd_status']) : false;
            $class = ( $status && !is_array($status)) ? "bg-custom item_nastel {$item_class} {$status}" : "bg-custom item_nastel {$item_class}";
            $content = "<div class='row' style='margin: 0 -5px;'>
                            <div class='col-md-8 noPaddingLeft n_nastel'>
                                <table class='table table-bordered nastel_table'>
                                    <thead>
                                        <tr>
                                            <th colspan='2' class='text-center'>
                                               <span class='badge pull-left number_list'>".++$n."</span> 
                                               <span class='nastel_no'>" . $key['nastel_no'] . "</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class='n_model'>
                                            <td>" . Yii::t('app', 'Model nomi') . "</td>
                                            <td>" . $key['model'] . "</td>
                                        </tr>
                                        <tr class='n_mato'>
                                            <td>" . Yii::t('app', 'Mato nomi') . "</td>
                                            <td>" . $key['mato'] . "</td>
                                        </tr>
                                        <tr class='n_color'>
                                            <td>" . Yii::t('app', 'Rangi') . "</td>
                                            <td>" . $key['rang']." ".$key['pantone'] . "</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class='col-md-4 noPadding text-center flex-container nastel_detail {$item_class}'>".$list[$key['id']]['list']."</div>
                        </div>";
            $items[$key['id']] = [
                'content' => $content,
                'options' => [
                    'class' => $class ?? 'bg-red',
                    'data' => [
                        'id'=>$key['id'],
//                        'parent' => $key['tkbd_id'],
                        'indeks' => $key['indeks'],
                    ],
                ],
            ];
        }
        return $items;
    }

    public static function getRmSearch($queryParams='',$arr=null)
    {
        $docType = WmsDocument::DOCUMENT_REQUEST_CARD;
        $statusActive = BichuvDoc::STATUS_ACTIVE;
        $statusMoved = BichuvDoc::STATUS_SAVED;
        $plan = BichuvTableRelWmsDoc::find()->asArray()->all();
        $planIds = ArrayHelper::getColumn($plan,'wms_doc_id');

        $query = WmsDocument::find()
            ->alias('wd')
            ->select(["wd.id", "bnl.name as nastel_no", "wd.id", "ml.name as model", "mv.name as model_var", "mv.code as code", "wd.status"])
            ->leftJoin(['bnl' => 'bichuv_nastel_lists'],'wd.bichuv_nastel_list_id = bnl.id')
            ->leftJoin(['wdi' => 'wms_document_items'],'wd.id = wdi.wms_document_id')
            ->leftJoin(['moi' => 'model_orders_items'],'wdi.model_orders_items_id = moi.id')
            ->leftJoin(['mo' => 'model_orders'],'moi.model_orders_id = mo.id')
            ->leftJoin(['ml' => 'models_list'],'moi.models_list_id = ml.id')
            ->leftJoin(['mv' => 'models_variations'],'moi.model_var_id = mv.id')
            ->where(['wd.document_type' => $docType, 'wd.status' => $statusActive])
            ->groupBy(['bnl.name']);

        if(!empty($planIds)){
            $query = $query->andWhere(["NOT IN",'wd.id', $planIds]);
        }
        if(!empty($queryParams)){
           $query = $query->andFilterWhere(['OR',['LIKE', 'ml.name', $queryParams],['LIKE', 'bnl.name',$queryParams]]);
       }
        if(!empty($arr)){
            $query = $query->andWhere(["NOT IN",'wd.id', $arr]);
        }

        $list = $query->asArray()->all();
        $items = [];
        $content = "";
        foreach ($list as $key) {
            $class = (!empty($key['status']) && $key['status'] == 3) ? "bg-green" : "";
            $content .=
                "<li class='bg-nastel item_nastel' data-id='".$key['id']."' data-parent='1' draggable='true' role='option' aria-grabbed='false'> 
                    <div class='row' style='margin: 0 -5px;'> <div class='col-md-8 noPaddingLeft'> 
                        <table class='table table-bordered nastel_table'> 
                            <thead> 
                                <tr> 
                                    <th colspan='2' class='text-center'> 
                                        <span class='badge pull-left number_list {$class}'>" . ++$n . "</span> 
                                        <span class='nastel_no'>" . $key['nastel_party'] . "</span>
                                     </th> 
                                </tr> 
                            </thead> 
                            <tbody> 
                                 <tr> 
                                    <td>" . Yii::t('app', 'Buyurtmachi') . "</td> 
                                    <td>" . $key['musteri'] . "</td> 
                                </tr> 
                                <tr> 
                                    <td>" . Yii::t('app', 'Model nomi') . "</td> 
                                    <td>" . $key['model'] . "</td> 
                                </tr>
                                <tr> 
                                    <td>" . Yii::t('app', 'Rangi') . "</td> 
                                    <td>" . $key['model_var'] ." (".$key['code'].")"."</td> 
                                 </tr> 
                            </tbody> 
                        </table>
                    </div>
                    <div class='col-md-4 noPadding text-center flex-container nastel_detail'>".$list[$key['id']]['list']."</div>
                 </div>
               </li>";
        }
        return $content;
    }








}
