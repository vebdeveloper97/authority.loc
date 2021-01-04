<?php

namespace app\modules\bichuv\models;

use app\modules\mobile\models\MobileTables;
use Yii;
use app\modules\wms\models\WmsDocument;
use app\modules\bichuv\models\BichuvTables;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_table_rel_wms_doc".
 *
 * @property int $id
 * @property int $bichuv_table_id
 * @property int $wms_doc_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BichuvTables $bichuvTable
 * @property WmsDocument $wmsDoc
 */
class BichuvTableRelWmsDoc extends BaseModel
{

    const STATUS_ACTIVE = 1;
    const STATUS_MOVED = 2;
    const STATUS_ACCEPTED = 3;
    const STATUS_STARTED = 4;
    const STATUS_PAUSE = 5;
    const STATUS_FINISHED = 6;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_table_rel_wms_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_table_id', 'mobile_table_id','wms_doc_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['mobile_table_id'], 'exist', 'skipOnError' => true, 'targetClass' => MobileTables::className(), 'targetAttribute' => ['mobile_table_id' => 'id']],
            [['bichuv_table_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvTables::className(), 'targetAttribute' => ['bichuv_table_id' => 'id']],
            [['wms_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDocument::className(), 'targetAttribute' => ['wms_doc_id' => 'id']],
            [['indeks'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bichuv_table_id' => Yii::t('app', 'Bichuv Table ID'),
            'mobile_table_id' => Yii::t('app', 'Mobile Table'),
            'wms_doc_id' => Yii::t('app', 'Wms Doc ID'),
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
    public function getBichuvTable()
    {
        return $this->hasOne(BichuvTables::className(), ['id' => 'bichuv_table_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWmsDoc()
    {
        return $this->hasOne(WmsDocument::className(), ['id' => 'wms_doc_id']);
    }

    /**
     * @param bool $key
     * @return array|mixed
     */
    public static function getClassList($key=false)
    {
        $result = [
            0 => '',
            self::STATUS_ACTIVE   => 'n_active',
            self::STATUS_MOVED   => 'n_moved',
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
    public static function getStatusProcess($key = null){
        $result = [
            self::STATUS_ACTIVE   => "<code class='bg-info'>".Yii::t('app','Kutish jarayonida')."</code>",
            self::STATUS_MOVED   => "<code class='bg-info'>".Yii::t('app','Mato ko\'childi')."</code>",
            self::STATUS_ACCEPTED   =>  "<code class='bg-warning'>".Yii::t('app','Mato qabul qilingan')."</code>",
            self::STATUS_STARTED => "<code class='bg-success'>".Yii::t('app',"Ishlab chiqarishga berildi")."</code>",
            self::STATUS_PAUSE => "<code class='bg-danger'>".Yii::t('app','To\'xtatildi')."</code>",
            self::STATUS_FINISHED => "<code class='bg-aqua-gradient'>".Yii::t('app','Kesim tayyor')."</code>"
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }
    /**
     * @param $id
     * @return false|int|string
     * Kelgan matolarni ishlab chiqarishga ruxsat
     */
    static function getPermissionByICH($id){

        $plans = self::find()
            ->select(['id'])
            ->where(['status' => self::STATUS_SAVED])
            ->orderBy(['indeks' => SORT_ASC])
            ->asArray()
            ->one();
        if (empty($plans)){
            $plans = [];
        }
        array_unshift($plans, -1);
        return array_search($id, $plans);

    }
    /**
     * @param $nastel_list_id
     * @param null $status
     * @return bool
     * Berilgan nastel id va status qarab karta holatini o'zgartirish
     */
    public static function getBichuvTableRelWmsDocByNastelId($nastel_list_id, $status = null){

        if(!empty($status)){
            $query = BichuvTableRelWmsDoc::find()
                ->alias('btrwd')
                ->leftJoin(['wd' => 'wms_document'],'btrwd.wms_doc_id = wd.id')
                ->where(['wd.bichuv_nastel_list_id' => $nastel_list_id])
                ->one();

            if(!empty($query)){
                $query->status = $status;
                return $query->save();
            }
        }

        return false;
    }

    public static function getMatoTableList($id)
    {

        $finished = BichuvTableRelWmsDoc::STATUS_FINISHED;
        $doubleSql = BichuvTableRelWmsDoc::find()
            ->select(['status','wms_doc_id as id'])
            ->where(['<','status' , $finished])
            ->asArray()
            ->all();
        $list = [];
        if(!empty($doubleSql)) {
            foreach ($doubleSql as $n => $key) {
                $list[$key['id']]['status'] = (!empty($list[$key['id']]['status'])) ? $list[$key['id']]['status'] : [];
                array_push($list[$key['id']]['status'], $key['status']);
                $fa = ($key['status'] < 3)?"<i class='fa fa-circle-o-notch fa-spin fa-fw'></i>":"<i class='fa fa-check'></i>";
                $list[$key['id']]['list'] .= "<div class='borderBottom'><span>{$fa}&nbsp;<b>{$key['detail']} </b></span></div>";
            }
        }
        $query = BichuvTableRelWmsDoc::find()
            ->alias('btrwd')
            ->select([
                "wd.id",
                "bnl.name as nastel_no",
                "wd.id",
                "ml.name as model",
                "ml.article",
                "mv.name as model_var",
                "mv.code as code",
                "btrwd.status as btrwd_status",
                "wd.status",
                "m.name as musteri",
                "mo.reg_date",
                "moi.load_date",
                'btrwd.indeks'
            ])
            ->leftJoin(['wd' => 'wms_document'],'btrwd.wms_doc_id = wd.id')
            ->leftJoin(['bnl' => 'bichuv_nastel_lists'],'wd.bichuv_nastel_list_id = bnl.id')
            ->leftJoin(['wdi' => 'wms_document_items'],'wd.id = wdi.wms_document_id')
            ->leftJoin(['moi' => 'model_orders_items'],'wdi.model_orders_items_id = moi.id')
            ->leftJoin(['mo' => 'model_orders'],'moi.model_orders_id = mo.id')
            ->leftJoin(['m' => 'musteri'],'mo.musteri_id = m.id')
            ->leftJoin(['ml' => 'models_list'],'moi.models_list_id = ml.id')
            ->leftJoin(['mv' => 'models_variations'],'moi.model_var_id = mv.id')
            ->where(['<','btrwd.status',$finished])
            ->andWhere(['btrwd.mobile_table_id' => $id])
            ->groupBy(['wd.id'])
            ->orderBy(['btrwd.indeks' => SORT_ASC])
            ->asArray()
            ->all();
        $items = [];
        foreach ($query as $n => $key) {
            $item_class = (!empty($list[$key['id']]['status']) && min($list[$key['id']]['status']) == 4) ? "n_isready" : "";
            $status = !empty($key['btrwd_status']) ? BichuvTableRelWmsDoc::getClassList($key['btrwd_status']) : false;
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
                                             <tr>
                                                <td class='n_musteri'>" . Yii::t('app', 'Buyurtmachi nomi') . "</td>
                                                <td>" . $key['musteri'] . "</td>
                                            </tr>
                                            <tr>
                                                <td class='n_model'>" . Yii::t('app', 'Model nomi') . "</td>
                                                <td>" . $key['model'] . "</td>
                                            </tr>
                                            <tr>
                                                <td class='n_article'>" . Yii::t('app', 'Model article') . "</td>
                                                <td>" . $key['article'] . "</td>
                                            </tr>
                                            <tr>
                                                <td class='n_color'>" . Yii::t('app', 'Rangi') . "</td>
                                                <td>" . $key['model_var']." ".$key['code'] . "</td>
                                            </tr>
                                             <tr> 
                                                <td class='n_reg_date'>" . Yii::t('app', 'Ro\'yxatga olingan sana') . "</td> 
                                                <td>" . $key['reg_date']."</td> 
                                            </tr>  
                                            <tr> 
                                                <td class='n_load_date'>" . Yii::t('app', 'Yuklama sanasi') . "</td> 
                                                <td>" . $key['load_date']."</td> 
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
                        'indeks' => $key['indeks'],
                    ],
                ],
            ];
        }
        return $items;
    }

    public static function getBichuvPlanList(){

        $plan = BichuvTableRelWmsDoc::find()->asArray()->all();
        $planIds = ArrayHelper::getColumn($plan,'wms_doc_id');
        $query = WmsDocument::find()
            ->alias('wd')
            ->select([
                "wd.id",
                "bnl.name as nastel_no",
                "wd.id",
                "ml.name as model",
                "ml.article",
                "mv.name as model_var",
                "mv.code as code",
                "wd.status",
                'IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) as color_code',
                'IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) as color_name',
                "m.name as musteri",
                "mo.reg_date",
                "moi.load_date"
                ])
            ->leftJoin(['bnl' => 'bichuv_nastel_lists'],'wd.bichuv_nastel_list_id = bnl.id')
            ->leftJoin(['wdi' => 'wms_document_items'],'wd.id = wdi.wms_document_id')
            ->leftJoin(['moi' => 'model_orders_items'],'wdi.model_orders_items_id = moi.id')
            ->leftJoin(['mo' => 'model_orders'],'moi.model_orders_id = mo.id')
            ->leftJoin(['m' => 'musteri'],'mo.musteri_id = m.id')
            ->leftJoin(['ml' => 'models_list'],'moi.models_list_id = ml.id')
            ->leftJoin(['mv' => 'models_variations'],'moi.model_var_id = mv.id')
            ->leftJoin(['wc' => 'wms_color'],'mv.wms_color_id = wc.id')
            ->leftJoin(['cp' => 'color_pantone'],'wc.color_pantone_id = cp.id')
            ->where(['wd.document_type' => WmsDocument::DOCUMENT_REQUEST_CARD, 'wd.status' => BichuvDoc::STATUS_ACTIVE])
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
                                 <tr class='n_article'>
                                    <td>" . Yii::t('app', 'Model article') . "</td>
                                    <td>" . $key['article'] . "</td>
                                </tr>
                                <tr> 
                                    <td>" . Yii::t('app', 'Rangi') . "</td> 
                                    <td>" . $key['color_name'] ." (".$key['color_code'].")"."</td> 
                                </tr>
                                <tr> 
                                    <td>" . Yii::t('app', 'Ro\'yxatga olingan sana') . "</td> 
                                    <td>" . date('d.m.Y', strtotime($key['reg_date']))."</td>
                                </tr>  
                                <tr> 
                                    <td>" . Yii::t('app', 'Yuklama sanasi') . "</td> 
                                    <td>" . date('d.m.Y', strtotime($key['load_date']))."</td> 
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

    public static function getBichuvPlanListDone($preview = false){

        $finished = BichuvTableRelWmsDoc::STATUS_FINISHED;
        $doubleSql = BichuvTableRelWmsDoc::find()
            ->select(['status','wms_doc_id as id'])
            ->where(['<','status' , $finished])
            ->asArray()
            ->all();
        $list = [];
        if(!empty($doubleSql)) {
            foreach ($doubleSql as $n => $key) {
                $list[$key['id']]['status'] = (!empty($list[$key['id']]['status'])) ? $list[$key['id']]['status'] : [];
                array_push($list[$key['id']]['status'], $key['status']);
                $fa = ($key['status'] < self::STATUS_SAVED) ? "<i class='fa fa-circle-o-notch fa-spin fa-fw'></i>":"<i class='fa fa-check'></i>";
                $list[$key['id']]['list'] .= "<div class='borderBottom'><span>{$fa}&nbsp;<b>{$key['detail']} </b></span></div>";
            }
        }
        $query = BichuvTableRelWmsDoc::find()
            ->alias('btrwd')
            ->select([
                "wd.id",
                "bnl.name as nastel_no",
                "wd.id",
                "ml.name as model",
                "ml.article",
                "mv.name as model_var",
                "mv.code as code",
                "btrwd.status as btrwd_status",
                "wd.status",
                "m.name as musteri",
                "mo.reg_date",
                "moi.load_date",
                'btrwd.indeks',
                'IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) as color_code',
                'IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) as color_name',
            ])
            ->leftJoin(['wd' => 'wms_document'],'btrwd.wms_doc_id = wd.id')
            ->leftJoin(['bnl' => 'bichuv_nastel_lists'],'wd.bichuv_nastel_list_id = bnl.id')
            ->leftJoin(['wdi' => 'wms_document_items'],'wd.id = wdi.wms_document_id')
            ->leftJoin(['moi' => 'model_orders_items'],'wdi.model_orders_items_id = moi.id')
            ->leftJoin(['mo' => 'model_orders'],'moi.model_orders_id = mo.id')
            ->leftJoin(['m' => 'musteri'],'mo.musteri_id = m.id')
            ->leftJoin(['ml' => 'models_list'],'moi.models_list_id = ml.id')
            ->leftJoin(['mv' => 'models_variations'],'moi.model_var_id = mv.id')
            ->leftJoin(['wc' => 'wms_color'],'mv.wms_color_id = wc.id')
            ->leftJoin(['cp' => 'color_pantone'],'wc.color_pantone_id = cp.id')
            ->where(['<','btrwd.status' , BichuvTableRelWmsDoc::STATUS_FINISHED])
            ->groupBy(['bnl.name'])
            ->orderBy(['btrwd.indeks' => SORT_ASC]);
    
            if($preview){
                $query->andWhere(['btrwd.mobile_table_id' => null]);
            }
        $query =  $query->asArray()->all();
        $items = [];
        foreach ($query as $n => $key) {
            $item_class = (!empty($list[$key['id']]['status']) && min($list[$key['id']]['status']) == 6) ? "n_active" : "";
            $status = !empty($key['btrwd_status']) ? self::getClassList($key['btrwd_status']) : false;
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
                                            <tr>
                                                <td>" . Yii::t('app', 'Buyurtmachi nomi') . "</td>
                                                <td>" . $key['musteri'] . "</td>
                                            </tr>
                                            <tr>
                                                <td>" . Yii::t('app', 'Model nomi') . "</td>
                                                <td>" . $key['model'] . "</td>
                                            </tr>
                                            <tr>
                                                <td>" . Yii::t('app', 'Model article') . "</td>
                                                <td>" . $key['article'] . "</td>
                                            </tr>
                                            <tr>
                                                <td>" . Yii::t('app', 'Rangi') . "</td>
                                                <td>" . $key['color_name']." (".$key['color_code'] . ")</td>
                                            </tr>
                                             <tr> 
                                                <td>" . Yii::t('app', 'Ro\'yxatga olingan sana') . "</td> 
                                                <td>" . date('d.m.Y', strtotime($key['reg_date']))."</td> 
                                            </tr>  
                                            <tr> 
                                                <td>" . Yii::t('app', 'Yuklama sanasi') . "</td> 
                                                <td>" . date('d.m.Y', strtotime($key['load_date']))."</td> 
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
            ->select([
                "wd.id",
                "bnl.name as nastel_no",
                "wd.id",
                "ml.name as model",
                "ml.article",
                "mv.name as model_var",
                "mv.code as code",
                "wd.status",
                "m.name as musteri",
                "mo.reg_date",
                "moi.load_date"
                ])
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
                                    <td>" . Yii::t('app', 'Model article') . "</td>
                                    <td>" . $key['article'] . "</td>
                                </tr>
                                <tr> 
                                    <td>" . Yii::t('app', 'Rangi') . "</td> 
                                    <td>" . $key['model_var'] ." (".$key['code'].")"."</td> 
                                </tr>
                                <tr> 
                                    <td>" . Yii::t('app', 'Ro\'yxatga olingan sana') . "</td> 
                                    <td>" . $key['reg_date']."</td> 
                                </tr>  
                                <tr> 
                                    <td>" . Yii::t('app', 'Yuklama sanasi') . "</td> 
                                    <td>" . $key['load_date']."</td> 
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

    public static function setStatusFinished($nastel_no){
        $getCardFinishedId = self::find()
            ->alias('btrwd')
            ->select(['btrwd.id as id','bnl.name'])
            ->innerJoin(['wd' => 'wms_document'],'btrwd.wms_doc_id = wd.id')
            ->innerJoin(['bnl' => 'bichuv_nastel_lists'],'wd.bichuv_nastel_list_id = bnl.id')
            ->where(['bnl.name' => $nastel_no])
            ->groupBy(['btrwd.wms_doc_id'])
            ->asArray()
            ->one();

        if (!empty($getCardFinishedId)){
            $model =  self::findOne(['id' => $getCardFinishedId['id']]);
           $model->status = self::STATUS_FINISHED;
           return $model->save();
        }
        return false;
    }

}
