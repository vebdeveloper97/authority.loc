<?php

namespace app\api\modules\v1\controllers;

use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;
use app\modules\bichuv\models\BichuvNastelLists;
use app\modules\bichuv\models\BichuvSliceItemBalance;
use app\modules\hr\models\HrDepartments;
use app\modules\mobile\models\MobileProcess;
use app\modules\mobile\models\MobileProcessProduction;
use app\modules\mobile\models\MobileTables;
use app\modules\tikuv\models\TikuvSliceItemBalance;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\wms\models\Musteri;
use app\modules\wms\models\WmsChangeOrderHistory;
use app\modules\wms\models\WmsColor;
use app\modules\wms\models\WmsDepartmentArea;
use app\modules\wms\models\WmsDesen;
use app\modules\wms\models\WmsDocument;
use app\modules\wms\models\WmsDocumentItems;
use app\modules\wms\models\WmsItemBalance;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use app\models\Constants;
use yii\httpclient\Client;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use app\modules\base\models\Goods;
use yii\filters\ContentNegotiator;
use app\modules\tikuv\models\TikuvGoodsDocAccepted;
use app\api\modules\v1\components\CorsCustom;
use app\modules\tikuv\models\TikuvGoodsDocPack;

/**
 * Country Controller API
 *
 * @author Omadbek Onorov <omadbek.onorov@gmail.com>
 */
class WmsController extends ActiveController
{
    public $modelClass = 'app\modules\wms\models\WmsDocument';

    public $_userId;

    public $enableCsrfValidation = false;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['index']);
        unset($actions['view']);
        return $actions;
    }

    public function beforeAction($action)
    {
        $userId = Yii::$app->user->isGuest;
        if ($userId) {
            return false;
        }
        $this->_userId = Yii::$app->user->id;
        if (!parent::beforeAction($action)) {
            return false;
        }
        return true;
    }

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => CorsCustom::className()
            ],
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @param $getData
     * @return array
     */
    public function conditions($getData)
    {
        $conditions = [
            'page' => 1,
            'limit' => 20,
            'lang' => 'uz',
            'sort' => 'DESC',
            'model' => '',
            'modelVar' => '',
            'party' => '',
            'customer' => '',
            'rm' => '',
            'nastel' => '',
            'type' => '',
        ];
        if (!empty($getData)) {
            foreach ($conditions as $field => $value) {
                if (!empty($getData[$field])) {
                    $conditions[$field] = $getData[$field];
                }
            }
        }

        return $conditions;
    }

    public function actionRmRemainList()
    {
        $getData = $this->conditions(Yii::$app->request->get());
        $offset = ($getData['page'] - 1) * $getData['limit'];
        $conditions = '';
        if (!empty($getData['model'])) {
            $conditions .= " AND ml.article LIKE '%{$getData['model']}%' ";
        }
        if (!empty($getData['modelVar'])) {
            $conditions .= " AND moi.model_var_id = {$getData['modelVar']} ";
        }
        if (!empty($getData['customer'])) {
            $conditions .= " AND wib.to_musteri = {$getData['customer']} ";
        }
        if (!empty($getData['party'])) {
            $conditions .= " AND wib.musteri_party_no = {$getData['party']} ";
        }
        if (!empty($getData['rm'])) {
            $conditions .= " AND wmi.toquv_raw_materials_id = {$getData['rm']} ";
        }
        $sql = "SELECT wmi.id,
                       wib.entity_id                                           as entityId,
                       wib.entity_type,     
                       trm.code,
                       trm.name                                                AS rname,
                       rtype.name                                               AS tname,
                       tn.name                                                 AS ne,
                       tt.name                                                 AS thread,
                       tpf.name                                                AS pus_fine,
                       IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) AS color_code,
                       IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) AS color_name,
                       wmi.en,
                       wmi.gramaj,
                       wib.inventory,
                       wib.lot                                                 as party_no,
                       wib.musteri_party_no,
                       mo.doc_number,
                       mo.reg_date,
                       ml.article,
                       cp.code,
                       mv.name                                                 as mvname,
                       mk.name                                                 as mk,
                       mz.name                                                 as mz,
                       wib.incoming_pb_id,
                       wib.incoming_price,
                       wib.musteri_id,
                       wib.to_musteri,
                       wib.model_orders_items_id,
                       wib.dep_area
                FROM wms_item_balance wib
                         LEFT JOIN model_orders_items moi ON wib.model_orders_items_id = moi.id
                         LEFT JOIN musteri mk ON wib.musteri_id = mk.id
                         LEFT JOIN musteri mz ON wib.to_musteri = mz.id
                         LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                         LEFT JOIN models_list ml ON ml.id = moi.models_list_id
                         LEFT JOIN models_variations mv ON moi.model_var_id = mv.id
                         LEFT JOIN wms_mato_info wmi ON wmi.id = wib.entity_id
                         LEFT JOIN toquv_raw_materials trm ON wmi.toquv_raw_materials_id = trm.id
                         LEFT JOIN raw_material_type rtype ON wmi.raw_material_type_id = rtype.id
                         LEFT JOIN toquv_ne tn ON wmi.ne_id = tn.id
                         LEFT JOIN toquv_thread tt ON wmi.thread_id = tt.id
                         LEFT JOIN toquv_pus_fine tpf ON wmi.pus_fine_id = tpf.id
                         LEFT JOIN wms_color wc ON wmi.wms_color_id = wc.id
                         LEFT JOIN color_pantone cp ON wc.color_pantone_id = cp.id
                         WHERE wib.inventory > 0 AND wib.id IN (SELECT MAX(wib2.id) FROM wms_item_balance wib2
                         WHERE wib2.entity_type = (select id from wms_item_category where token = 'MATERIAL')
                         GROUP BY wib2.entity_id, wib2.entity_type, wib2.lot, wib2.musteri_party_no, wib2.department_id, wib2.dep_area) 
                         AND 1=1 %s LIMIT %d OFFSET %d;";
        $sql = sprintf($sql, $conditions, $getData['limit'], $offset);
        try {
            $results = Yii::$app->db->createCommand($sql)->queryAll();
            $response = [
                'status' => true,
                'message' => Yii::t('app', 'Muvaffaqiyatli bajarildi'),
                'data' => $results
            ];
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    public function actionProcessList()
    {
        $processLists = MobileProcess::find()
            ->select([
                'mobile_process.id',
                'mobile_process.name',
                'hr_departments.name as dept'])
            ->where(['mobile_process.type' => 2])
            ->joinWith('department')
            ->orderBy(['mobile_process.process_order' => SORT_ASC])
            ->asArray()
            ->all();
        if (!empty($processLists)) {
            return ['status' => true, 'list' => $processLists];
        }
        return ['status' => false, 'message' => Yii::t('app', "Ma'lumot mavjud emas!")];
    }

    public function actionDeptList()
    {
        $currentUserId = Yii::$app->request->get('id', null);
        $response = [];
        $response['status'] = false;
        $response['message'] = Yii::t('app', "Ma'lumot mavjud emas!");
        if ($currentUserId) {
            $matoOmborToken = Constants::$TOKEN_MATO_OMBOR;
            $bichuvToken = Constants::$TOKEN_BICHUV;
            $sqlMato = "select IF(hd.token = '%s',hd.id, NULL) as depId,
                            IF(hd.token = '%s',hd.name, NULL) as depName,
                            IF(hd.token = '%s',he.id, NULL) as empId
                    from hr_departments hd
                    left join hr_department_responsible_person hdrp ON hd.id = hdrp.hr_department_id
                    left join hr_employee he ON hdrp.hr_employee_id = he.id
                    where hd.token = '%s';";
            $sqlBichuv = "select IF(hd.token = '%s',hd.id, NULL) as depId,
                                 IF(hd.token = '%s',hd.name, NULL) as depName,
                                 IF(hd.token = '%s',he.id, NULL) as empId
                    from hr_departments hd
                    left join hr_department_responsible_person hdrp ON hd.id = hdrp.hr_department_id
                    left join hr_employee he ON hdrp.hr_employee_id = he.id
                    where hd.token = '%s';";
            $sqlMato = sprintf($sqlMato, $matoOmborToken, $matoOmborToken, $matoOmborToken, $matoOmborToken);
            $sqlBichuv = sprintf($sqlBichuv, $bichuvToken, $bichuvToken, $bichuvToken, $bichuvToken);
            try {
                $resultMato = Yii::$app->db->createCommand($sqlMato)->queryOne();
                $resultBichuv = Yii::$app->db->createCommand($sqlBichuv)->queryOne();
                $response['data']['mato'] = $resultMato;
                $response['data']['bichuv'] = $resultBichuv;
                $response['status'] = true;
                $response['message'] = Yii::t('app', 'Muvaffaqiyatli bajarildi');
            } catch (Exception $e) {
                $response['status'] = false;
                $response['message'] = $e->getMessage();
            }
        }
        return $response;
    }

    public function actionSaveRmRequest()
    {
        $data = Yii::$app->request->post();
        $response = [];
        $response['status'] = false;
        $response['message'] = Yii::t('app', 'Xatolik yuz berdi!');

        if (!empty($data['doc']) && !empty($data['items'])) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $saved = false;
                $lastId = WmsDocument::getLastId();
                $document = new WmsDocument();
                $currYear = date("Y");

                $document->doc_number = "WD{$lastId}/{$currYear}";
                $document->document_type = WmsDocument::DOCUMENT_REQUEST_CARD;
                $document->reg_date = date('d.m.Y');

                $document->from_department = $data['doc']['mato']['depId'];
                $document->to_department = $data['doc']['bichuv']['depId'];

                $document->from_employee = $data['doc']['mato']['empId'];
                $document->to_employee = $data['doc']['bichuv']['empId'];

                $document->bichuv_nastel_list_id = BichuvNastelLists::newId();
                $document->scenario = WmsDocument::SCENARIO_PLAN_RM_REQUEST;
                $customer = '';
                $article = '';
                $modelName = '';
                $color = '';
                $loadDate = null;
                $modelOrderItemId = null;
                if ($document->save()) {
                    $documentId = $document->id;
                    foreach ($data['items'] as $item) {
                        $documentItems = new WmsDocumentItems();
                        $documentItems->wms_document_id = $documentId;
                        $documentItems->entity_id = $item['entityId'];
                        $documentItems->entity_type = $item['entity_type'];
                        $documentItems->dep_area = $item['dep_area'];
                        $documentItems->party_no = $item['party_no'];
                        $documentItems->quantity = $item['inventory'];
                        $documentItems->musteri_id = $item['musteri_id'];
                        $documentItems->to_musteri = $item['to_musteri'];
                        $documentItems->model_orders_items_id = $item['model_orders_items_id'];
                        $documentItems->incoming_price = $item['incoming_price'];
                        $documentItems->incoming_pb_id = $item['incoming_pb_id'];
                        $documentItems->musteri_party_no = $item['musteri_party_no'];
                        $documentItems->roll_count = 1;
                        $documentItems->status = WmsDocumentItems::STATUS_ACTIVE;
                        $loadDate = $documentItems->modelOrdersItems->load_date;
                        if ($documentItems->save()) {
                            $saved = true;
                            if (!empty($loadDate)) {
                                $loadDate = date('d.m.Y', strtotime($documentItems->modelOrdersItems->load_date));
                                $modelOrderItemId = $item['model_orders_items_id'];
                                $customer = $documentItems->toMusteri->name;
                                $color = $documentItems->modelOrdersItems->modelVar->id;
                                $article = $documentItems->modelOrdersItems->modelsList->article;
                                $modelName = $documentItems->modelOrdersItems->modelsList->name;
                            }
                            unset($documentItems);
                        } else {
                            $saved = false;
                            break;
                        }
                    }
                    $mTable = MobileTables::find()->where(['token' => Constants::TOKEN_PLAN_RM_CARD])->asArray()->one();
                    if (empty($mTable)) {
                        return ['status' => false, 'message' => Yii::t('app', 'Plan uchun PLAN_RM_CARD tokenli table mavjud emas')];
                    }
                    $params = [
                        'nastel_no' => $document->bichuvNastelList->name,
                        'started_date' => date('d.m.Y H:i:s'),
                        'ended_date' => date('d.m.Y H:i:s'),
                        'table_name' => WmsDocument::getTableSchema()->name,
                        'model_orders_items_id' => $modelOrderItemId,
                        'doc_id' => $document->id,
                        'mobile_tables_id' => $mTable['id']
                    ];
                    $saved = MobileProcessProduction::saveMobileProcess($params) && $saved;
                }
                if ($saved) {
                    $colorNameSql = "select IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) AS color_code,
                                         IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) AS color_name 
                                  from wms_color wc
                                  left join models_variations mv on wc.id = mv.wms_color_id
                                  left join color_pantone cp on wc.color_pantone_id = cp.id
                                  where mv.id = '%s'";
                    $colorNameSql = sprintf($colorNameSql, $color);
                    $colorNameResult = Yii::$app->db->createCommand($colorNameSql)->queryOne();
                    $colorName = null;
                    if (!empty($colorNameResult)) {
                        $colorName = "{$colorNameResult['color_code']} {$colorNameResult['color_name']}";
                    }
                    $transaction->commit();
                    $response['status'] = true;
                    $response['message'] = Yii::t('app', 'Muvaffaqiyatli bajarildi');
                    $response['data'] = [
                        'nastel_no' => $document->bichuvNastelList->name,
                        'color' => $colorName,
                        'article' => $article,
                        'model_name' => $modelName,
                        'customer' => $customer,
                        'docDate' => date('d.m.Y', strtotime($document->reg_date)),
                        'load_date' => $loadDate,
                    ];
                }
                $transaction->rollBack();
            } catch (Exception $e) {
                $response['status'] = false;
                $response['message'] = $e->getMessage();
                $transaction->rollBack();
            } catch (InvalidConfigException $e) {
                $response['status'] = false;
                $response['message'] = $e->getMessage();
                $transaction->rollBack();
            }
        }
        return $response;
    }

    public function actionNastelList()
    {

        $getData = $this->conditions(Yii::$app->request->get());

        $offset = ($getData['page'] - 1) * $getData['limit'];
        $conditions = '';

        if (!empty($getData['model'])) {
            $conditions .= " AND ml.article LIKE '%{$getData['model']}%' ";
        }
        if (!empty($getData['modelVar'])) {
            $conditions .= " AND (wc.color_code LIKE '%{$getData['modelVar']}%' OR wc.color_name LIKE '%{$getData['modelVar']}%' OR cp.code LIKE '%{$getData['modelVar']}%') ";
        }
        if (!empty($getData['customer'])) {
            $conditions .= " AND mo.musteri_id = {$getData['customer']} ";
        }
        if (!empty($getData['nastel'])) {
            $conditions .= " AND mpp.nastel_no = '{$getData['nastel']}' ";
        }
        $response = [];
        $response['status'] = false;
        $sql = "select mpp.id,
                       mpp.nastel_no,
                       ml.name,
                       ml.article,
                       IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) AS color_code,
                       IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) AS color_name,
                       moi.id as model_order_item_id,
                       ml.id model_list_id
                from mobile_process_production mpp
                inner join mobile_tables mt on mpp.mobile_tables_id = mt.id
                left join model_orders_items moi on mpp.model_orders_items_id = moi.id
                left join model_orders mo on moi.model_orders_id = mo.id    
                left join models_list ml on moi.models_list_id = ml.id
                left join models_variations mv on moi.model_var_id = mv.id
                left join wms_color wc on mv.wms_color_id = wc.id
                left join color_pantone cp on wc.color_pantone_id = cp.id
                where mt.token = '%s' AND mpp.parent_id IS NULL %s ORDER BY mpp.id DESC LIMIT %d OFFSET %d;";
        $sql = sprintf($sql, Constants::TOKEN_BICHUV_PRODUCTION_MATO, $conditions, $getData['limit'], $offset);
        try {
            $results = Yii::$app->db->createCommand($sql)->queryAll();
            $response['status'] = true;
            $response['items'] = $results;
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    public function actionNastelChildren()
    {

        $getData = $this->conditions(Yii::$app->request->get());
        $offset = ($getData['page'] - 1) * $getData['limit'];

        $data = Yii::$app->request->post();
        $response = [];
        $response['status'] = false;
        $sql = "select  bsib.id, 
                        bsib.party_no as nastel_no,
                        bdl.name as detail,
                        bdt.name as detail_group
                    from bichuv_slice_item_balance bsib
                        left join mobile_process_production mpp on bsib.party_no = mpp.nastel_no
                        left join base_detail_lists bdl on mpp.base_detail_list_id = bdl.id
                        left join bichuv_detail_types bdt on mpp.bichuv_detail_type_id = bdt.id
                        inner join mobile_tables mt on mpp.mobile_tables_id = mt.id
                        left join model_orders_items moi on mpp.model_orders_items_id = moi.id
                        left join models_list ml on moi.models_list_id = ml.id
                        left join models_variations mv on moi.model_var_id = mv.id
                        left join wms_color wc on mv.wms_color_id = wc.id
                        left join color_pantone cp on wc.color_pantone_id = cp.id
                        where mt.token = '%s' AND moi.id = '%d' AND bsib.inventory > 0 AND mpp.parent_id IS NOT NULL AND bsib.id IN (
                            select MAX(bsib2.id) from bichuv_slice_item_balance bsib2 where bsib2.party_no = mpp.nastel_no GROUP BY bsib2.party_no
                            ) GROUP BY bsib.party_no ORDER BY bsib.party_no LIMIT %d OFFSET %d;";
        $sql = sprintf($sql, Constants::TOKEN_BICHUV_QABUL_KESIM, $data['model_order_item_id'], $getData['limit'], $offset);
        try {
            $results = Yii::$app->db->createCommand($sql)->queryAll();
            $response['status'] = true;
            $response['items'] = $results;
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    public function actionChildrenCards()
    {

        $items = Yii::$app->request->post();
        $response = [];
        try {
            if (!empty($items)) {
                $nastelNo = "";
                $lastIndex = array_key_last($items);
                foreach ($items as $key => $item) {
                    $nastelNo .= "'{$item['nastel_no']}'";
                    if ($lastIndex !== $key) {
                        $nastelNo .= ", ";
                    }
                }
                $sql = "select bsib.party_no as nastel_no,
                           bsib.size_id,
                           s.name, 
                           bsib.inventory,
                           bdt.name as detail_group,
                           bdl.name as detail,
                           bsib.inventory as fact
                        from bichuv_slice_item_balance bsib
                        left join size s on bsib.size_id = s.id
                        inner join mobile_process_production mpp on bsib.party_no = mpp.nastel_no
                        left join mobile_tables mt on mpp.mobile_tables_id = mt.id
                        left join bichuv_detail_types bdt on mpp.bichuv_detail_type_id = bdt.id
                        left join base_detail_lists bdl on mpp.base_detail_list_id = bdl.id
                        where bsib.id IN (select MAX(bsib2.id) from bichuv_slice_item_balance bsib2 where bsib2.party_no IN (%s) GROUP BY bsib2.party_no, bsib2.size_id)
                              AND mt.token = '%s' AND mpp.parent_id IS NOT NULL GROUP BY bsib.party_no, bsib.size_id;";
                $sql = sprintf($sql, $nastelNo, Constants::TOKEN_BICHUV_PRODUCTION_MATO);
                $data = Yii::$app->db->createCommand($sql)->queryAll();
                $response['items'] = $data;
                $response['status'] = true;
            }
        } catch (\Exception $e) {
            $response['status'] = false;
        }
        return $response;
    }

    public function actionCombineDetails()
    {
        $data = Yii::$app->request->post();
        $response['status'] = false;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $saved = false;
            if (!empty($data) && $data['mainCard'] && $data['child']) {
                $child = $data['child'];
                $main = $data['mainCard'];
                $minValue = min(ArrayHelper::getColumn($child, 'fact'));
                $sizeCollect = [];
                if ($minValue < 1) {
                    $response['message'] = Yii::t('app', 'Detallar toliq emas');
                    $saved = false;
                } else {
                    $saved = true;
                }
                if ($saved) {
                    foreach ($child as $item) {
                        $lastRec = BichuvSliceItemBalance::find()->where([
                            'party_no' => $item['nastel_no'],
                            'size_id' => $item['size_id'],
                        ])->asArray()->orderBy(['id' => SORT_DESC])->one();
                        if (!empty($lastRec) && $lastRec['inventory'] >= $minValue) {
                            $sizeCollect[$item['size_id']] = $item['size_id'];
                            $modelBSIB = new BichuvSliceItemBalance();
                            $modelBSIB->size_id = $item['size_id'];
                            $modelBSIB->party_no = $item['nastel_no'];
                            $modelBSIB->count = (-1) * $item['fact'];
                            $modelBSIB->inventory = $lastRec['inventory'] - $minValue;
                            $modelBSIB->entity_id = $item['id'];
                            $modelBSIB->hr_department_id = $lastRec['hr_department_id'];
                            $modelBSIB->doc_type = 2;
                            if ($modelBSIB->save()) {
                                $saved = true;
                            } else {
                                $saved = false;
                                $response['message'] = $modelBSIB->getErrors();
                                break;
                            }
                        } else {
                            $response['message'] = Yii::t('app', 'Detallar toliq emas!');
                            return $response;
                        }
                    }
                }

                if (!empty($sizeCollect) && $saved) {
                    foreach ($sizeCollect as $item) {
                        $lastRecMain = BichuvSliceItemBalance::find()->where([
                            'party_no' => $main['nastel_no'],
                            'size_id' => $item,
                        ])->asArray()->orderBy(['id' => SORT_DESC])->one();
                        $inventory = $minValue;
                        if (!empty($lastRecMain)) {
                            $inventory += $lastRecMain['inventory'];
                        }
                        $modelMainBSIB = new BichuvSliceItemBalance();
                        $modelMainBSIB->inventory = $inventory;
                        $modelMainBSIB->count = $minValue;
                        $modelMainBSIB->size_id = $item;
                        $modelMainBSIB->party_no = $main['nastel_no'];
                        if ($modelMainBSIB->save()) {
                            $saved = true;
                        } else {
                            $saved = false;
                            $response['message'] = $modelMainBSIB->getErrors();
                            break;
                        }
                    }
                }
                if ($saved) {
                    $transaction->commit();
                    $response['status'] = true;
                    $response['message'] = Yii::t('app', 'Detallar muvofaqqiyatli birlashtirildi');
                } else {
                    $transaction->rollBack();
                    $response['status'] = false;
                    $response['message'] = Yii::t('app', 'Xatolik yuz berdi!');
                }
            }
        } catch (\Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
            $transaction->rollBack();
        }
        return $response;
    }

    //Begin Ready Work Combine
    public function actionReadyWorkList()
    {

        $getData = $this->conditions(Yii::$app->request->get());

        $offset = ($getData['page'] - 1) * $getData['limit'];
        $conditions = '';

        if (!empty($getData['model'])) {
            $conditions .= " AND ml.article LIKE '%{$getData['model']}%' ";
        }
        if (!empty($getData['modelVar'])) {
            $conditions .= " AND (wc.color_code LIKE '%{$getData['modelVar']}%' OR wc.color_name LIKE '%{$getData['modelVar']}%' OR cp.code LIKE '%{$getData['modelVar']}%') ";
        }
        if (!empty($getData['customer'])) {
            $conditions .= " AND mo.musteri_id = {$getData['customer']} ";
        }
        if (!empty($getData['nastel'])) {
            $conditions .= " AND tsib.nastel_no = '{$getData['nastel']}' ";
        }
        $response = [];
        $response['status'] = false;

        try {
            $sql = "select  mrp.id,
                            tsib.nastel_no,
                            ml.name,
                            ml.article,
                            IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) AS color_code,
                            IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) AS color_name,
                            moi.id as model_order_item_id,
                            ml.id model_list_id,
                            tsib.inventory
                from tikuv_slice_item_balance tsib
                         left join mobile_process_production mpp on tsib.mobile_process_id = mpp.id
                         left join mobile_tables mt on tsib.mobile_tables_id = mt.id
                         inner join model_rel_production mrp ON tsib.nastel_no = mrp.nastel_no
                         left join model_orders_items moi on mrp.order_item_id = moi.id
                         left join model_orders mo on moi.model_orders_id = mo.id
                         left join models_list ml on moi.models_list_id = ml.id
                         left join models_variations mv on moi.model_var_id = mv.id
                         left join wms_color wc on mv.wms_color_id = wc.id
                         left join color_pantone cp on wc.color_pantone_id = cp.id
                where mt.token = '%s' %s AND tsib.id IN (select MAX(tsib2.id) from tikuv_slice_item_balance tsib2
                where tsib2.mobile_process_id = mpp.id AND tsib2.mobile_tables_id = mt.id GROUP BY tsib2.nastel_no) LIMIT %d OFFSET %d;";
            $sql = sprintf($sql, Constants::TOKEN_TIKUV_FINAL, $conditions, $getData['limit'], $offset);
            $results = Yii::$app->db->createCommand($sql)->queryAll();
            $response['status'] = true;
            $response['items'] = $results;
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    public function actionSelectedReadyList()
    {
        $getData = $this->conditions(Yii::$app->request->get());
        $offset = ($getData['page'] - 1) * $getData['limit'];
        $data = Yii::$app->request->post();
        $response['status'] = false;

        try{
            switch ($getData['type']) {
                case 'list':
                    $nastelNo = $this->getArrayToString($data);
                    $sql = "select tsib.id,  
                            mrp.id as mobile_process_id,
                            moi.id as model_order_item_id,
                            ml.id model_list_id,
                            mt.id as mobile_table_id,
                            tsib.nastel_no,
                            ml.article,
                            IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) AS colorCode,
                            IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) AS colorName,
                            tsib.inventory,
                            tsib.inventory as fact,
                            s.name as sizeName,
                            s.id as size_id
                    from tikuv_slice_item_balance tsib
                             left join size s on tsib.size_id = s.id
                             left join mobile_process_production mpp on tsib.mobile_process_id = mpp.id
                             left join mobile_tables mt on tsib.mobile_tables_id = mt.id
                             inner join model_rel_production mrp ON tsib.nastel_no = mrp.nastel_no
                             left join model_orders_items moi on mrp.order_item_id = moi.id
                             left join model_orders mo on moi.model_orders_id = mo.id
                             left join models_list ml on moi.models_list_id = ml.id
                             left join models_variations mv on moi.model_var_id = mv.id
                             left join wms_color wc on mv.wms_color_id = wc.id
                             left join color_pantone cp on wc.color_pantone_id = cp.id
                    where mt.token = '%s' AND tsib.id IN (select MAX(tsib2.id) from tikuv_slice_item_balance tsib2
                    where tsib2.mobile_process_id = mpp.id AND tsib2.mobile_tables_id = mt.id AND tsib2.nastel_no IN (%s) GROUP BY tsib2.nastel_no, tsib2.size_id) LIMIT %d OFFSET %d;";
                        $sql = sprintf($sql, Constants::TOKEN_TIKUV_FINAL, $nastelNo, $getData['limit'], $offset);
                        $results = Yii::$app->db->createCommand($sql)->queryAll();
                        $response['items'] = $results;
                        $response['status'] = true;
                        break;
                case 'combine':
                    $transaction = Yii::$app->db->beginTransaction();
                    $response['items'] = $data;
                    $minValue = min(ArrayHelper::getColumn($data, 'fact'));
                    $response['status'] = true;
                    $response['min'] = $minValue;
                    $model = new TikuvSliceItemBalance();
                    foreach ($data as $item){
                        $lastRec = TikuvSliceItemBalance::find()
                            ->where(['nastel_no' => $item['nastel_no'],'size_id' => $item['size_id']])
                            ->asArray()
                            ->orderBy(['id' =>SORT_DESC])
                            ->one();
                        $inventory = $item['inventory'];
                        if(!empty($lastRec)){

                        }



                    }
                    break;
                default:
                    $sql = "";
                    break;
            }
        }catch (\Exception $e){
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    //End Ready Work Combine

    //Begin WMS Mato Ombori
    public function actionWmsActions()
    {
        $action = Yii::$app->request->get('action');
        $data = Yii::$app->request->post();
        $response['status'] = false;
        switch ($action) {
            case 'departmentList':
                $deptType = Yii::$app->request->get('deptType');
                try {
                    $sql = "select hd.id, hd.name, u.id as userId, hd.type from users_hr_departments uhd
                        left join hr_departments hd on hd.id = uhd.hr_departments_id
                        left join users u on uhd.user_id = u.id where u.id = %d;";
                    $sql = sprintf($sql, $this->_userId);
                    $results = Yii::$app->db->createCommand($sql)->queryAll();
                    $response['status'] = true;
                    $response['items'] = [];
                    foreach ($results as $result) {
                        if ($deptType == $result['type']) {
                            $response['items'][$result['id']] = $result['name'];
                        } else {
                            $response['items'][$result['id']] = $result['name'];
                        }
                    }
                    $response['message'] = Yii::t('app', 'Success');
                } catch (Exception $e) {
                    $response['status'] = false;
                    $response['message'] = $e->getMessage();
                }
                break;
            case 'orderListWithRM':
                try {
                    $getData = Yii::$app->request->get();
                    if(empty($getData['limit'])){
                       $getData['limit'] = 20;
                    }
                    $conditions = '';
                    if (!empty($getData['lot'])) {
                        $conditions .= " AND wib.musteri_party_no LIKE '{$getData['lot']}%' ";
                    }
                    if (!empty($getData['orderNumber'])) {
                        $conditions .= " AND mo.doc_number LIKE '%{$getData['orderNumber']}%' ";
                    }
                    if (!empty($getData['rm'])) {
                        $conditions .= " AND trm.id = {$getData['rm']} ";
                    }
                    if (!empty($getData['customer'])) {
                        $conditions .= " AND wib.to_musteri = {$getData['customer']} ";
                    }
                    if (!empty($getData['en'])) {
                        $conditions .= " AND wmi.en = '{$getData['en']}' ";
                    }
                    if (!empty($getData['gramaj'])) {
                        $conditions .= " AND wmi.gramaj = '{$getData['gramaj']}' ";
                    }
                    if (!empty($getData['color'])) {
                        $conditions .= " AND wc.id = '{$getData['color']}' ";
                    }
                    if (!empty($getData['baski'])) {
                        $conditions .= " AND wd.id = '{$getData['baski']}' ";
                    }
                    $sql = "select wmi.id,
                                   wib.entity_id as entityId,
                                   wib.entity_type,
                                   0 as is_active, 
                                   trm.name                                                AS rname,
                                   rtype.name                                              AS tname,
                                   tn.name                                                 AS ne,
                                   tt.name                                                 AS thread,
                                   tpf.name                                                AS pus_fine,
                                   IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) AS color_code,
                                   IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) AS color_name,
                                   wmi.en,
                                   0 as fact, 
                                   wmi.gramaj,
                                   wib.inventory,
                                   wib.lot,
                                   wib.musteri_party_no,
                                   mo.doc_number,
                                   mo.reg_date,
                                   DATE_FORMAT(moi.load_date,'%%d/%%m/%%Y') AS load_date, 
                                   ml.article,
                                   mk.name                                                 as mk,
                                   mz.name                                                 as mz,
                                   wib.musteri_id,
                                   wib.to_musteri,
                                   wib.model_orders_items_id,
                                   wib.dep_area,
                                   mz.id as customerId,
                                   wib.department_id
                            from wms_item_balance wib
                                     left join wms_mato_info wmi on wmi.id = wib.entity_id
                                     LEFT JOIN musteri mk ON wib.musteri_id = mk.id
                                     LEFT JOIN musteri mz ON wib.to_musteri = mz.id
                                     left join model_orders_items moi on wib.model_orders_items_id = moi.id
                                     LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                                     LEFT JOIN models_list ml ON ml.id = moi.models_list_id
                                     LEFT JOIN models_variations mv ON moi.model_var_id = mv.id
                                     LEFT JOIN toquv_raw_materials trm ON wmi.toquv_raw_materials_id = trm.id
                                     LEFT JOIN raw_material_type rtype ON wmi.raw_material_type_id = rtype.id
                                     LEFT JOIN toquv_ne tn ON wmi.ne_id = tn.id
                                     LEFT JOIN toquv_thread tt ON wmi.thread_id = tt.id
                                     LEFT JOIN toquv_pus_fine tpf ON wmi.pus_fine_id = tpf.id
                                     LEFT JOIN wms_color wc ON wmi.wms_color_id = wc.id
                                     LEFT JOIN color_pantone cp ON wc.color_pantone_id = cp.id
                                     left join hr_departments hd on wib.department_id = hd.id   
                                     left join wms_desen wd on wmi.wms_desen_id = wd.id  
                            WHERE wib.inventory > 0 AND
                                  wib.id IN
                                  (SELECT MAX(wib2.id)
                                   FROM wms_item_balance wib2
                                   WHERE wib2.entity_type = (select wic.id from wms_item_category wic where wic.token = '%s')
                                   AND wib2.department_id = (select hr.id from hr_departments hr where hr.token = '%s')
                                  GROUP BY wib2.entity_id, wib2.entity_type, wib2.model_orders_items_id,  wib2.musteri_party_no, wib2.department_id, wib2.dep_area)
                                  AND 1=1 %s ORDER BY moi.id DESC LIMIT %d";
                    $sql = sprintf($sql, Constants::TYPE_MATERIAL_RM, HrDepartments::TOKEN_MATERIAL_WAREHOUSE, $conditions, $getData['limit']);
                    $results = Yii::$app->db->createCommand($sql)->queryAll();
                    $response['status'] = true;
                    $response['items'] = $results;
                    $response['message'] = Yii::t('app', 'Success');
                } catch (Exception $e) {
                    $response['status'] = false;
                    $response['message'] = $e->getMessage();
                }
                break;
            case 'customerList':
                try {
                    $getData = Yii::$app->request->get();
                    $query = Musteri::find()
                        ->alias('ct')
                        ->select(['ct.name','ct.id']);
                    $response['status'] = true;
                    $response['items'] = $this->prepareSelectOptions($query, $getData);
                    $response['message'] = Yii::t('app', 'Success');
                } catch (Exception $e) {
                    $response['status'] = false;
                    $response['message'] = $e->getMessage();
                }
                break;
            case 'rmList':
                try {
                    $getData = Yii::$app->request->get();
                    $query = ToquvRawMaterials::find()
                        ->alias('ct')
                        ->select(['ct.name','ct.id']);
                    $response['status'] = true;
                    $response['items'] = $this->prepareSelectOptions($query, $getData);
                    $response['message'] = Yii::t('app', 'Success');
                } catch (Exception $e) {
                    $response['status'] = false;
                    $response['message'] = $e->getMessage();
                }
                break;
            case 'colorList':
                try {
                    $getData = Yii::$app->request->get();
                    $query = WmsColor::find()
                        ->select(["concat(IF(ct.color_pantone_id IS NULL, ct.color_code, cp.code),' ',IF(ct.color_pantone_id IS NULL, ct.color_name, cp.name)) AS lname","ct.id"])
                        ->alias("ct")
                        ->leftJoin("color_pantone as cp","cp.id = ct.color_pantone_id");
                    $response['status'] = true;
                    $response['items'] = $this->prepareSelectOptions($query, $getData, 'lname', 'having');
                    $response['message'] = Yii::t('app', 'Success');
                } catch (Exception $e) {
                    $response['status'] = false;
                    $response['message'] = $e->getMessage();
                }
                break;
            case 'baskiList':
                try {
                    $getData = Yii::$app->request->get();
                    $query = WmsDesen::find()
                        ->select(['concat(ct.name,"-",ct.code, "-(", wbt.name,")") as lname','ct.id'])
                        ->alias('ct')
                        ->joinWith(['wmsBaskiType' => function ($q) {
                            $q->alias('wbt');
                            $q->select(['wbt.id']);
                        }]);
                    $response['status'] = true;
                    $response['items'] = $this->prepareSelectOptions($query, $getData,'lname','having');
                    $response['message'] = Yii::t('app', 'Success');
                } catch (Exception $e) {
                    $response['status'] = false;
                    $response['message'] = $e->getMessage();
                }
                break;
            case 'orderList':
                try {
                    $getData = Yii::$app->request->get();
                    $conditions = '';
                    if(!empty($getData['name'])){
                        $words = explode(' ', trim($getData['name']));
                        $lastKey = array_key_last($words);
                        foreach ($words as $key=>$word) {
                            if($key == 0) $conditions .= " ( ";
                            $conditions .= " mo.doc_number like '%{$word}%' OR m.name like '%{$word}%' OR ml.article like '%{$word}%' ";
                            if($key == $lastKey) $conditions .= " ) AND ";
                        }
                    }
                    if(empty($getData['limit'])){
                        $getData['limit'] = 20;
                    }
                    $existsIds = join(", ", $data);
                    $sql = "select IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) AS color_code,
                                   IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) AS color_name,
                                   mo.doc_number,
                                   DATE_FORMAT(mo.reg_date,'%%d/%%m/%%Y') AS reg_date,
                                   DATE_FORMAT(moi.load_date,'%%d/%%m/%%Y') AS load_date,
                                   ml.article,
                                   moi.id,
                                   m.name as customer,
                                   m.id as customerId
                            from  model_orders_items moi
                                     LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                                     LEFT JOIN musteri m on mo.musteri_id = m.id
                                     LEFT JOIN models_list ml ON ml.id = moi.models_list_id
                                     LEFT JOIN models_variations mv ON moi.model_var_id = mv.id
                                     LEFT JOIN wms_color wc ON mv.wms_color_id = wc.id
                                     LEFT JOIN color_pantone cp ON wc.color_pantone_id = cp.id
                                     left join wms_desen wd on mv.wms_desen_id = wd.id
                            WHERE %s mo.status > 3 AND moi.id NOT IN (%s) ORDER BY mo.id DESC limit %d;";
                    $sql = sprintf($sql, $conditions, $existsIds, $getData['limit']);
                    $results = Yii::$app->db->createCommand($sql)->queryAll();
                    $items = [];
                    foreach ($results as $result) {
                        $template = "<code>{$result['doc_number']}</code> <strong>Model:</strong><code>{$result['article']}</code> <strong>Rangi:</strong> <code>{$result['color_code']}({$result['color_name']})</code><strong>Buyurtmachi:</strong> <code>{$result['customer']}</code> <strong>Yuklama sanasi:</strong><code>{$result['load_date']}</code><strong>Sana:</strong><code>{$result['reg_date']}</code>";
                        array_push($items,['value' => $result['id'],'label' => $template, 'customer' => $result['customerId']]);
                    }
                    $response['status'] = true;
                    $response['items'] = $items;
                    $response['sql'] = $sql;
                    $response['message'] = Yii::t('app', 'Success');
                } catch (Exception $e) {
                    $response['status'] = false;
                    $response['message'] = $e->getMessage();
                }
                break;
            case 'changeOrder':
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $isSaved = false;
                    $errors = [];
                    $response['status'] = false;
                    $response['message'] = Yii::t('app','Order Item Id not found');
                    $orderItemId = Yii::$app->request->get('orderItemId');
                    $customerId = Yii::$app->request->get('customerId');
                    if(empty($orderItemId)) return $response;
                    foreach ($data as $item) {
                        $lastRec = WmsItemBalance::find()
                            ->where([
                                'entity_id' => $item['entityId'],
                                'entity_type' => $item['entity_type'],
                                'musteri_party_no' => $item['musteri_party_no'],
                                'department_id' => $item['department_id'],
                                'dep_area' => $item['dep_area'],
                                'model_orders_items_id' => $item['model_orders_items_id'],
                                'to_musteri' => $item['customerId']
                            ])
                            ->orderBy(['id' => SORT_DESC])
                            ->one();
                        $lastRecNewModelOrder = WmsItemBalance::find()
                            ->where([
                                'entity_id' => $item['entityId'],
                                'entity_type' => $item['entity_type'],
                                'musteri_party_no' => $item['musteri_party_no'],
                                'department_id' => $item['department_id'],
                                'dep_area' => $item['dep_area'],
                                'model_orders_items_id' => $orderItemId,
                                'to_musteri' => $customerId
                            ])
                            ->asArray()
                            ->orderBy(['id' => SORT_DESC])
                            ->one();
                        if(!empty($lastRec)){
                            $isSaved = true;
                            if($item['fact'] > $lastRec->inventory){
                                $response['status'] = false;
                                $response['message'] = Yii::t('app', 'Omborda qoldiq yetarli emas!');
                                return  $response;
                            }
                            $lastRecModel = new WmsItemBalance();
                            $lastRecModel->attributes = $lastRec->attributes;
                            $lastRecModel->quantity = (-1)*$item['fact'];
                            $lastRecModel->inventory -= $item['fact'];
                            $lastRecModel->isNewRecord = true;
                            if(!$lastRecModel->save()){
                                $isSaved = false;
                                $errors['modelLast'] = $lastRecModel->getErrors();
                                break;
                            }
                            $newRecInventory = $item['fact'];
                            if(!empty($lastRecNewModelOrder)){
                                $newRecInventory += $lastRecNewModelOrder['inventory'];
                            }
                            $newRecModel = new WmsItemBalance();
                            $newRecModel->attributes = $lastRec->attributes;
                            $newRecModel->quantity = $item['fact'];
                            $newRecModel->inventory = $newRecInventory;
                            $newRecModel->model_orders_items_id = $orderItemId;
                            $newRecModel->to_musteri = $customerId;
                            $newRecModel->isNewRecord = true;
                            if(!$newRecModel->save()){
                                $isSaved = false;
                                $errors['modelNew'] = $newRecModel->getErrors();
                                break;
                            }
                            $changedOrderModel = new WmsChangeOrderHistory();
                            $changedOrderModel->from_wib = $lastRecModel->id;
                            $changedOrderModel->to_wib = $newRecModel->id;
                            if(!$changedOrderModel->save()){
                                $isSaved = false;
                                $errors['changedOrderHistory'] = $changedOrderModel->getErrors();
                                break;
                            }
                        }
                    }
                    if($isSaved){
                        $transaction->commit();
                        $response['status'] = true;
                        $response['message'] = Yii::t('app', 'Success');
                    }else{
                        $transaction->rollBack();
                        $response['status'] = false;
                        $response['errors'] = $errors;
                        $response['message'] = Yii::t('app', 'Error');
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    $response['status'] = false;
                    $response['message'] = $e->getMessage();
                }
                break;
        }
        return $response;
    }

    private function getArrayToString($data = [], $separate = ',', $fieldName = 'nastel_no'){
        $nastelNo = "";
        if(!empty($data)){
            $lastKey = array_key_last($data);
            foreach ($data as $key => $item) {
                $nastelNo .= "'{$item[$fieldName]}'";
                if($lastKey !== $key){
                    $nastelNo .= ", ";
                }
            }
        }
        return $nastelNo;
    }

    /**
     * @param $query
     * @param $getData
     * @param string $fieldName
     * @param string $conditionType
     * @return array
     */
    private function prepareSelectOptions($query, $getData, $fieldName = 'name', $conditionType= 'where'){

        if(empty($getData['limit'])){
            $getData['limit'] = 20;
        }
        if(!empty($getData['name'])){
            $words = explode(' ', trim($getData['name']));
            foreach ($words as $word) {
                switch ($conditionType){
                    case 'where':
                        $query->andFilterWhere(['like',$fieldName,$word]);
                        break;
                    case 'having':
                        $query->andFilterHaving(['like',$fieldName,$word]);
                        break;
                }
            }
            $query->andFilterWhere(['ct.status' => 1]);
        }else{
            $query->where('1=2');
        }
//        $sql = $query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql;
//        return ['sql' => $sql];
        $items = $query->asArray()->limit($getData['limit'])->all();

        $results = [];
        foreach ($items as $item) {
            array_push($results,['value' => $item['id'],'label' => $item[$fieldName]]);
        }
        return $results;
    }

    //End WMS Mato Ombori
}
