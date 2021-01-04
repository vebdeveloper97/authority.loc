<?php

namespace app\modules\toquv\controllers;

use app\modules\base\models\ModelOrdersItems;
use app\modules\toquv\models\MatoSearch;
use app\modules\toquv\models\SortName;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvDocItemsRelOrder;
use app\modules\toquv\models\ToquvDocumentItems;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvItemBalance;
use app\modules\toquv\models\ToquvKalite;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\toquv\models\Unit;
use Yii;
use app\modules\toquv\models\RollInfo;
use app\modules\toquv\models\RollMoveInfo;
use app\modules\toquv\models\RollInfoSearch;
use app\modules\toquv\controllers\BaseController;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * RollInfoController implements the CRUD actions for RollInfo model.
 */
class RollInfoController extends BaseController
{
    public $slug;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function beforeAction($action)
    {
        $slug = Yii::$app->request->get('slug');
        if (!empty($slug)) {
            $this->slug = $slug;
        }
        //TODO Registratsiya department -> RollInfoSearch::init and create permission
        if (parent::beforeAction($action)) {
            $slug = Yii::$app->request->get('slug');
            if (Yii::$app->authManager->getPermission(Yii::$app->controller->id."/".Yii::$app->controller->action->id)) {
                if (!Yii::$app->user->can(Yii::$app->controller->id . "/" . $this->slug . "/" . Yii::$app->controller->action->id)) {
                    throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
                }
            }
            return true;
        } else {
            return false;
        }
    }
    /**
     * Lists all RollInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RollInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RollInfo model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id = null)
    {
        $searchModel = new RollInfoSearch();
        $dataProvider = $searchModel->searchView(Yii::$app->request->queryParams,$id);
        $model = $searchModel->getInfo($id);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);
        }
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionSaveAndFinish($id = null)
    {
        $searchModel = new RollInfoSearch();
        $model = new ToquvDocuments();
        $model->from_department = $searchModel->department;
        $sql = "select u.id, u.user_fio
                    from toquv_user_department tud
                    left join users u on tud.user_id = u.id
                    where  tud.department_id = :id AND u.user_role <> 1 LIMIT 1;
                    ";
        $to_employe = Yii::$app->db->createCommand($sql)->bindValue(':id', $to_department)->queryAll();
        $type = ToquvRawMaterials::ENTITY_TYPE_MATO;
        $roll = $searchModel->getInfo($id);
        $roll_info = $searchModel->getInfo($id,true);
        $roll_all = $searchModel->searchView('',$id,true);
        $lastId = $model::find()->select('id')->orderBy(['id' => SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $model->doc_number = "RK-" . $lastId . "/" . date('d.m.Y');
        $model->reg_date = date('d.m.Y');
        $models = [new ToquvDocumentItems()];
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $TDIModelName = ToquvDocumentItems::getModelName();
            $dataTDI = Yii::$app->request->post($TDIModelName, []);
            if (isset($data[$TDIModelName])) {
                unset($data[$TDIModelName]);
            }
            $dataTdi = $data['ToquvDocItems'];
            if ($model->load($data) && $model->save()) {
                foreach ($dataTDI as $item) {
                    $flagToquvItems = false;
                    $modelDI = new ToquvDocumentItems();
                    $savedDataTDI = [];
                    $savedDataTDI[$TDIModelName] = $item;
                    $savedDataTDI[$TDIModelName]['toquv_document_id'] = $model->id;
                    $savedDataTDI[$TDIModelName]['unit_id'] = 2;
                    $savedDataTDI[$TDIModelName]['price_usd'] = !empty($item['price_usd']) ? $item['price_usd'] : 0;
                    $savedDataTDI[$TDIModelName]['price_sum'] = !empty($item['price_sum']) ? $item['price_sum'] : 0;
                    if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                        $TDIRO = new ToquvDocItemsRelOrder([
                            'toquv_document_items_id' => $modelDI->id,
                            'toquv_orders_id' => $item['order_id'],
                            'toquv_rm_order_id' => $item['order_item_id'],
                        ]);
                        $TDIRO->save();
                        if(!empty($item['child'])){
                            foreach ($item['child'] as $key => $m) {
                                $new_item = new ToquvDocumentItems([
                                    'entity_id' => $m['entity_id'],
                                    'entity_type' => ToquvDocuments::ENTITY_TYPE_IP,
                                    'quantity' => $m['quantity'],
                                    'lot' => $m['lot'],
                                    'unit_id' => 2,
                                    'toquv_document_id' => $model->id,
                                    'document_qty' => $m['quantity'],
                                    'tib_id' => $modelDI->id,
                                    'is_own' => 2
                                ]);
                                $new_item->save();
                            }
                        }
                        $flagToquvItems = true;
                    }
                }
                $flagToquvItems = false;
                foreach ($data['Items'] as $item) {
                    $modelDi = ToquvDocumentItems::findOne(['toquv_document_id'=>$model->id,'entity_id'=>$dataTdi['entity_id'],'lot'=>$item['sort_id'],'entity_type'=>$type]);
                    $modelDi = ($modelDi)?$modelDi:new ToquvDocumentItems(['roll_count' => 0]);
                    $r_info = RollInfo::findOne($item['id']);
                    $flagToquvItems = false;
                    if($r_info){
                        $r_info->setAttributes([
                            'toquv_departments_id' => $model->to_department,
                            'old_departments_id' => $model->from_department,
                        ]);
                        if($r_info->save()){
                            $move_info = new RollMoveInfo([
                                'toquv_documents_id' => $model->id,
                                'roll_info_id' => $r_info->id,
                                'entity_type' => $r_info->entity_type,
                                'quantity' => $r_info->quantity,
                                'code' => $r_info->code,
                                'from_department' => $model->from_department,
                                'to_department' => $model->to_department
                            ]);
                            $move_info->save();
                        }
                    }
                    $modelDi->setAttributes([
                        'toquv_document_id' => $model->id,
                        'entity_id' => $dataTdi['entity_id'],
                        'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                        'quantity' => $modelDi->quantity + $item['quantity'],
                        'unit_id' => ($unit = Unit::findOne(['code'=>'KG']))?$unit['id']:2,
                        'document_qty' => $modelDi->document_qty + $item['quantity'],
                        'tib_id' => $dataTdi['tib_id'],
                        'is_own' => 1,
                        'lot' => $item['sort_id'],
                        'roll_count' => $modelDi->roll_count + 1
                    ]);
                    if ($modelDi->save()) {
                        $tdiro = ToquvDocItemsRelOrder::findOne([
                            'toquv_document_items_id' => $modelDi->id,
                            'toquv_orders_id' => $dataTdi['toquv_orders_id'],
                            'toquv_rm_order_id' => $dataTdi['toquv_rm_order_id'],
                        ]);
                        if(!$tdiro) {
                            $TDIRO = new ToquvDocItemsRelOrder([
                                'toquv_document_items_id' => $modelDi->id,
                                'toquv_orders_id' => $dataTdi['toquv_orders_id'],
                                'toquv_rm_order_id' => $dataTdi['toquv_rm_order_id'],
                            ]);
                            $TDIRO->save();
                        }
                        $flagToquvItems = true;
                    }
                }
                if($flagToquvItems){
                    $date = date('d/m/Y');
                    $cloneAcceptDocModel = $model;
                    $cloneAccept = new ToquvDocuments();
                    $cloneAcceptDocModel->document_type = $model::DOC_TYPE_INCOMING;
                    $cloneAcceptDocModel->status = 3;
                    $cloneAcceptDocModel->doc_number = "RMD-{$model->id}/{$date}";
                    $cloneAcceptDocModel->action = 2;
                    $cloneAccept->attributes = $cloneAcceptDocModel->attributes;
                    $isClone = false;
                    if($cloneAccept->save()){
                        $isClone = true;
                    }
                    $flagIB = false;
                    if ($isClone) {
                        if($model->toquvDocumentItems){
                            foreach ($model->toquvDocumentItems as $toquvDocumentItem) {
                                $relOrder = $toquvDocumentItem->toquvDocItemsRelOrders;
                                $modelAcceptItems = new ToquvDocumentItems();
                                $modelAcceptItems->attributes = $toquvDocumentItem->attributes;
                                $modelAcceptItems->toquv_document_id = $cloneAccept->id;
                                if ($modelAcceptItems->save()) {
                                    foreach ($relOrder as $rel) {
                                        $newRelOrder = new ToquvDocItemsRelOrder();
                                        $newRelOrder->attributes = $rel->attributes;
                                        $newRelOrder->toquv_document_items_id = $modelAcceptItems->id;
                                        $newRelOrder->save();
                                    }
                                    $flagIB = true;
                                }
                            }
                            $TDItems = $cloneAccept->getToquvDocumentItems()->asArray()->all();
                            $flagIB = false;
                            if (!empty($TDItems)) {
                                //items loop
                                foreach ($TDItems as $item) {
                                    $flagIB = false;
                                    $ItemBalanceModel = new ToquvItemBalance();
                                    $item['department_id'] = $cloneAccept->from_department;
                                    $lastRecFrom = ToquvItemBalance::getLastRecordMoving($item);
                                    $item['department_id'] = $cloneAccept->to_department;
                                    $lastRec = ToquvItemBalance::getLastRecordMoving($item);
                                    //tekwirish
                                    if (!empty($lastRec)) {
                                        $attributesTIB['entity_id'] = $item['entity_id'];
                                        $attributesTIB['entity_type'] = $item['entity_type'];
                                        $attributesTIB['is_own'] = $item['is_own'];
                                        $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                        $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                        $attributesTIB['document_id'] = $cloneAccept->id;
                                        $attributesTIB['inventory'] = $lastRec['inventory'] + $item['quantity'];
                                        $attributesTIB['roll_inventory'] = $lastRec['roll_inventory'] + $item['roll_count'];
                                        $attributesTIB['quantity_inventory'] = $lastRec['quantity_inventory'] + $item['count'];
                                        $attributesTIB['lot'] = $item['lot'];
                                        $attributesTIB['count'] = $item['quantity'];
                                        $attributesTIB['roll_count'] = $item['roll_count'];
                                        $attributesTIB['quantity'] = $item['count'];
                                        $attributesTIB['department_id'] = $cloneAccept->to_department;
                                        $attributesTIB['document_type'] = $cloneAccept->document_type;
                                        $attributesTIB['musteri_id'] = $item['musteri_id'];
                                        $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($cloneAccept->reg_date));
                                        $ItemBalanceModel->setAttributes($attributesTIB);
                                    } else {
                                        $attributesTIB['entity_id'] = $item['entity_id'];
                                        $attributesTIB['entity_type'] = $item['entity_type'];
                                        $attributesTIB['is_own'] = $item['is_own'];
                                        $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                        $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                        $attributesTIB['document_id'] = $cloneAccept->id;
                                        $attributesTIB['inventory'] = $item['quantity'];
                                        $attributesTIB['roll_inventory'] = $item['roll_count'];
                                        $attributesTIB['quantity_inventory'] = $item['count'];
                                        $attributesTIB['lot'] = $item['lot'];
                                        $attributesTIB['count'] = $item['quantity'];
                                        $attributesTIB['roll_count'] = $item['roll_count'];
                                        $attributesTIB['quantity'] = $item['count'];
                                        $attributesTIB['department_id'] = $cloneAccept->to_department;
                                        $attributesTIB['document_type'] = $cloneAccept->document_type;
                                        $attributesTIB['musteri_id'] = $item['musteri_id'];
                                        $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($cloneAccept->reg_date));
                                        $ItemBalanceModel->setAttributes($attributesTIB);
                                    }
                                    if ($ItemBalanceModel->save()) {
                                        $newFromItembalance = new ToquvItemBalance();
                                        $newFromItembalance->attributes = $lastRecFrom->attributes;
                                        $newFromItembalance->setAttributes([
                                            'inventory'      => $lastRecFrom['inventory'] - $item['quantity'],
                                            'roll_inventory' => $lastRecFrom['roll_inventory'] - $item['roll_count'],
                                            'quantity_inventory' => $lastRecFrom['quantity_inventory'] - $item['count'],
                                            'count'          => (-1) * $item['quantity'],
                                            'roll_count'     => (-1) * $item['roll_count'],
                                            'quantity'     => (-1) * $item['count'],
                                            'reg_date'       => date('Y-m-d H:i:s', strtotime($cloneAccept->reg_date)),
                                            'to_department'  => $cloneAccept->to_department
                                        ]);
                                        $newFromItembalance->save();
                                        $flagIB = true;
                                    }

                                }
                            }
                        }
                    }
                    if ($flagIB){
                        $model->updateCounters(['status' => 2]);
                    }
                }
                Yii::$app->session->setFlash('success', Yii::t('app', 'Muvaffaqiyatli bajarildi'));
                return $this->redirect(["index", 'slug' => $this->slug
                ]);
            }
        }
        return $this->render('save-and-finish', [
            'model' => $model,
            'roll' => $roll,
            'roll_all' => $roll_all,
            'to_employe' => ArrayHelper::map($to_employe,'id','user_fio'),
            'roll_info' => $roll_info,
            'brak' => null,
            'models' => $models,
        ]);
    }

    /**
     * @param $id
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionGetDepartmentUser($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 0;
        if (!empty($id)) {
            $sql = "select u.id, u.user_fio
                    from toquv_user_department tud
                    left join users u on tud.user_id = u.id
                    where  tud.department_id = :id AND u.user_role <> 1 LIMIT 1;
                    ";
            $result = Yii::$app->db->createCommand($sql)->bindValue(':id', $id)->queryOne();
            if ($result) {
                $response['status'] = 1;
                $response['id'] = $result['id'];
                $response['name'] = $result['user_fio'];
            }
        }
        return $response;
    }
    public function actionAjaxRequestMatoMoving($q, $index, $dept, $type=null, $sort=null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $isOwn = Yii::$app->request->get('isOwn',1);
        $response = [];
        $response['results'] = [];
        $response['p'] = ['index' => $index];
        $tip = ($type==null)?ToquvDocuments::ENTITY_TYPE_MATO:$type;
        $st = ($sort==null)?SortName::findOne(['code'=>'SORT1'])['id']:$sort;
        if (!empty($q)) {
            $params = [];
            $params['query'] = $q;
            $params['dept'] = $dept;
            $searchModel = new ToquvDocuments();
            $res = $searchModel->searchMatoMoving($params,$type=$tip,$sort=$st);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $pf = Yii::t('app','Pus Fine');
                    $lth = Yii::t('app','Thread Length');
                    $gr = Yii::t('app','Finish Gramaj');
                    $en = Yii::t('app','Finish En');
                    $order = Yii::t('app','Buyurtmachi');
                    $dt = Yii::t('app',"Ko'rsatma sanasi");
                    $reg_date = date('d.m.Y H:i', strtotime($item['reg_date']));
                    $name = "{$item['mato']} | {$pf}:{$item['pus_fine']} | {$lth}:{$item['length']} | {$gr}:{$item['gramaj']} | {$en}:{$item['en']}) ({$order}:{$item['mushteri']}) ({$dt}:{$reg_date})";

                    array_push($response['results'], [
                        'id' => $item['id'],
                        'text' => $name,
                        'entity_id' => $item['rmid'],
                        'order_id' => $item['order_id'],
                        'order_item_id' => $item['order_item_id'],
                        'index' => $index,
                        'remain' => $item['remain'],
                        'roll' => number_format($item['roll']),
                        'lot' => $item['lot'],
                        'count' => $item['count'],
                    ]);
                }
            } else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                    'remain' => 0,
                ];
            }
        }
        return $response;
    }
    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "roll-info_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => RollInfo::find()->select([
                'id',
            ])->all(),
            'columns' => [
                'id',
            ],
            'headers' => [
                'id' => 'Id',
            ],
            'autoSize' => true,
        ]);
    }
    /**
     * Finds the RollInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RollInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RollInfo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
