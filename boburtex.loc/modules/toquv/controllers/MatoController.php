<?php

namespace app\modules\toquv\controllers;

use app\models\Users;
use app\modules\base\models\ModelOrdersItems;
use app\modules\toquv\models\MatoInfo;
use app\modules\toquv\models\Musteri;
use app\modules\toquv\models\RollInfo;
use app\modules\toquv\models\RollMoveInfo;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvDocItemsRelOrder;
use app\modules\toquv\models\ToquvDocumentItems;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvInstructionRm;
use app\modules\toquv\models\ToquvMatoItemBalance;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\toquv\models\Unit;
use Yii;
use app\modules\toquv\models\ToquvKalite;
use app\modules\toquv\models\MatoSearch;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * MatoController implements the CRUD actions for ToquvKalite model.
 */
class MatoController extends BaseController
{
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

    /**
     * Lists all ToquvKalite models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MatoSearch();
        $params = [];
        $params['mak'] = Yii::$app->request->get('mak') ?? false;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, null, $params['mak']);
        if($params['mak']){
            return $this->render('index-makine', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'mak' => $params['mak'],
            ]);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mak' => $params['mak']
        ]);
    }
    public function actionBrak()
    {
        $searchModel = new MatoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,true);

        return $this->render('brak', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single ToquvKalite model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id = null, $brak = null)
    {
        $searchModel = new MatoSearch();
        $dataProvider = $searchModel->searchView(Yii::$app->request->queryParams,$id, 1, $brak);
        $dataProvider1 = $searchModel->searchView(Yii::$app->request->queryParams,$id,3, $brak);
        $model = ToquvKalite::getOneKalite($id);
        $moi = ModelOrdersItems::findOne($model['moi_id']);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'dataProvider' => $dataProvider,
                'dataProvider1' => $dataProvider1,
                'model' => $model,
                'moi' => $moi,
                'brak' => $brak
            ]);
        }
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'dataProvider1' => $dataProvider1,
            'model' => $model,
            'moi' => $moi,
            'brak' => $brak
        ]);
    }

    /**
     * @param null $id
     * @param null $mato_id
     * @param null $pus_fine_id
     * @param null $thread_length
     * @param null $finish_en
     * @param null $finish_gramaj
     * @param null $brak
     * @return string|Response
     * @throws \yii\db\Exception
     */
    public function actionSaveAndFinish($id = null, $mato_id = null, $pus_fine_id = null, $thread_length = null, $finish_en = null, $finish_gramaj = null, $brak = null)
    {
        $t = Yii::$app->request->get('t',1);
        $model = new ToquvDocuments();
        $from_department = ToquvDepartments::findOne(['token'=>'TOQUV_MATO_SEH'])['id'];
        $to_department = ToquvDepartments::findOne(['token'=>'TOQUV_MATO_SKLAD'])['id'];
        $model->from_department = $from_department;
        $model->to_department = $to_department;
        $sql = "select u.id, u.user_fio
                    from toquv_user_department tud
                    left join users u on tud.user_id = u.id
                    where  tud.department_id = :id AND u.user_role <> 1 LIMIT 25;
                    ";
        $to_employe = Yii::$app->db->createCommand($sql)->bindValue(':id', $to_department)->queryAll();
        if(Users::findOne(20)){
            $model->to_employee = Users::findOne(20)['id'];
        }
        $type = ToquvRawMaterials::ENTITY_TYPE_MATO;
        $kalite = ToquvKalite::getOneKalite($id,1, $brak);
        /*$kalite_all = ToquvKalite::getAllKalite($id, $mato_id, $pus_fine_id, $thread_length, $finish_en, $finish_gramaj,1, $brak);*/
        $lastId = $model::find()->select('id')->orderBy(['id' => SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $br = ($brak)?"-{$brak}-":"-";
        $model->doc_number = "TK" .$br. $lastId . "/" . date('d.m.Y');
        $model->reg_date = date('d.m.Y');
        $searchModel = new MatoSearch();
        $dataProvider = $searchModel->searchView(Yii::$app->request->queryParams,$id, 1, $brak);
        $dataProvider->pagination->pageSize=1000;
        $isSaved = false;
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $dataTDI = $data['ToquvDocumentItems'];
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->load($data) && $model->save()) {
                    $data['ToquvDocumentExpense']['document_id'] = $model->id;
                    $flagToquvItems = false;
                    foreach ($data['Items'] as $item) {
                        $t_kalite = ToquvKalite::findOne($item['id']);
                        $roll_check = RollInfo::findOne([
                            'code' => $t_kalite->code,
                            'entity_id' => $t_kalite->toquv_raw_materials_id,
                            'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                            'quantity' => $t_kalite->quantity,
                            'tir_id' => $t_kalite->toquv_instruction_rm_id,
                            'moi_id' => $t_kalite->toquvInstructionRm->moi_id,
                            'toquv_kalite_id' => $t_kalite->id,
                            'toquv_departments_id' => $to_department,
                            'old_departments_id' => $from_department,
                            'sort_name_id' => $t_kalite->sort_name_id
                        ]);
                        if($roll_check&&!empty($roll_check)){
                            Yii::$app->session->setFlash('error', $t_kalite->code." kodli rulon omborga jo'natib bo'lingan!");
                            $transaction->rollBack();
                            break;
                            return $this->render('save-and-finish', [
                                'model' => $model,
                                'kalite' => $kalite,
                                'kalite_all' => $dataProvider->models,
                                'to_employe' => ArrayHelper::map($to_employe,'id','user_fio'),
                                'brak' => $brak,
                                'searchModel' => $searchModel,
                            ]);
                        }
                        if ($t_kalite) {
                            $tir = ToquvInstructionRm::findOne($t_kalite->toquv_instruction_rm_id);
                            if ($tir) {
                                $mato = MatoInfo::findOne([
                                    'entity_id' => $t_kalite->toquv_raw_materials_id,
                                    'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                                    'pus_fine_id' => $tir->toquv_pus_fine_id,
                                    'thread_length' => $tir->thread_length,
                                    'finish_en' => $tir->finish_en,
                                    'finish_gramaj' => $tir->finish_gramaj,
                                    'type_weaving' => $tir->type_weaving,
                                    'musteri_id' => $tir->toquvRmOrder->toquvOrders->musteri_id,
                                    'toquv_rm_order_id' => $tir->toquv_rm_order_id,
                                ]);
                                if($mato){
                                    $mato->model_musteri_id = $tir->toquvRmOrder->toquvOrders->model_musteri_id;
                                    $mato->model_code = $tir->toquvRmOrder->model_code;
                                    $mato->color_pantone_id = $tir->toquvRmOrder->color_pantone_id;
                                    $mato->save();
                                }
                                if (!$mato) {
                                    $mato = new MatoInfo([
                                        'entity_id' => $t_kalite->toquv_raw_materials_id,
                                        'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                                        'pus_fine_id' => $tir->toquv_pus_fine_id,
                                        'thread_length' => $tir->thread_length,
                                        'finish_en' => $tir->finish_en,
                                        'finish_gramaj' => $tir->finish_gramaj,
                                        'type_weaving' => $tir->type_weaving,
                                        'toquv_rm_order_id' => $tir->toquv_rm_order_id,
                                        'musteri_id' => $tir->toquvRmOrder->toquvOrders->musteri_id,
                                        'model_musteri_id' => $tir->toquvRmOrder->toquvOrders->model_musteri_id,
                                        'model_code' => $tir->toquvRmOrder->model_code,
                                        'color_pantone_id' => $tir->toquvRmOrder->color_pantone_id,
                                        'toquv_instruction_id' => $tir->toquv_instruction_id,
                                        'toquv_instruction_rm_id' => $tir->id
                                    ]);
                                    $mato->save();
                                }
                                if ($mato->hasErrors()) {
                                    \yii\helpers\VarDumper::dump($mato->getErrors(), 10, true);
                                    \yii\helpers\VarDumper::dump($tir, 10, true);
                                    die;
                                }
                            }else{
                                $flagToquvItems = false;
                                break;
                            }
                        }else{
                            $flagToquvItems = false;
                            break;
                        }
                        if ($mato) {
                            $modelDI = ToquvDocumentItems::findOne(['toquv_document_id' => $model->id, 'entity_id' => $mato['id'], 'lot' => $item['sort_id'], 'entity_type' => $type]);
                            $modelDI = ($modelDI) ? $modelDI : new ToquvDocumentItems(['roll_count' => 0]);
                            $modelDI->setAttributes([
                                'toquv_document_id' => $model->id,
                                'entity_id' => $mato->id,
                                'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                                'quantity' => $modelDI->quantity + $item['quantity'],
                                'unit_id' => ($unit = Unit::findOne(['code' => 'KG'])) ? $unit['id'] : 2,
                                'document_qty' => $modelDI->document_qty + $item['quantity'],
                                'price_sum' => 1,
                                'price_usd' => 1,
                                'tib_id' => $dataTDI['tib_id'],
                                'is_own' => 1,
                                'lot' => $item['sort_id'],
                                'roll_count' => $modelDI->roll_count + 1
                            ]);
                            if ($modelDI->save()) {
                                if ($t_kalite) {
                                    $t_kalite->setAttributes([
                                        'send_date' => date('Y-m-d H:i:s'),
                                        'send_user_id' => Yii::$app->user->identity->id,
                                        'status' => ToquvKalite::STATUS_SAVED
                                    ]);
                                    if($t_kalite->save()){

                                    }else{
                                        $flagToquvItems = false;
                                        break;
                                    }
                                }
                                $tdiro = ToquvDocItemsRelOrder::findOne([
                                    'toquv_document_items_id' => $modelDI->id,
                                    'toquv_orders_id' => $dataTDI['toquv_orders_id'],
                                    'toquv_rm_order_id' => $dataTDI['toquv_rm_order_id'],
                                ]);
                                if (!$tdiro) {
                                    $TDIRO = new ToquvDocItemsRelOrder([
                                        'toquv_document_items_id' => $modelDI->id,
                                        'toquv_orders_id' => $dataTDI['toquv_orders_id'],
                                        'toquv_rm_order_id' => $dataTDI['toquv_rm_order_id'],
                                    ]);
                                    $TDIRO->save();
                                }
                                $flagToquvItems = true;
                            }else{
                                $flagToquvItems = false;
                                break;
                            }
                        } else {
                            Yii::$app->session->setFlash('error', Yii::t('app', 'Hatolik yuz berdi'));
                            $flagToquvItems = false;
                            break;
                        }

                        $roll_info = new RollInfo([
                            'code' => $t_kalite->code,
                            'entity_id' => $t_kalite->toquv_raw_materials_id,
                            'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                            'quantity' => $t_kalite->quantity,
                            'tir_id' => $t_kalite->toquv_instruction_rm_id,
                            'moi_id' => $t_kalite->toquvInstructionRm->moi_id,
                            'toquv_kalite_id' => $t_kalite->id,
                            'toquv_departments_id' => $to_department,
                            'old_departments_id' => $from_department,
                            'sort_name_id' => $t_kalite->sort_name_id
                        ]);
                        if ($roll_info->save()) {
                            $move_info = new RollMoveInfo([
                                'toquv_documents_id' => $model->id,
                                'roll_info_id' => $roll_info->id,
                                'entity_type' => $roll_info->entity_type,
                                'quantity' => $roll_info->quantity,
                                'code' => $roll_info->code,
                                'from_department' => $from_department,
                                'to_department' => $to_department
                            ]);
                            $move_info->save();
                        }
                    }
                    if ($flagToquvItems) {
                        $model->updateCounters(['status' => 2]);
                    }
                    if ($flagToquvItems) {
                        $date = date('d/m/Y');
                        $cloneAcceptDocModel = $model;
                        $cloneAccept = new ToquvDocuments();
                        $cloneAcceptDocModel->document_type = $model::DOC_TYPE_INCOMING;
                        $cloneAcceptDocModel->status = 1;
                        $cloneAcceptDocModel->parent_doc_id = $model->id;
                        $cloneAcceptDocModel->doc_number = "TMO{$br}{$model->id}/{$date}";
                        $cloneAcceptDocModel->action = 2;
                        $cloneAccept->attributes = $cloneAcceptDocModel->attributes;
                        $isClone = false;
                        if ($cloneAccept->save()) {
                            $isClone = true;
                            $flagIB = true;
                        }
                        $isSaved = false;
                        if ($isClone) {
                            if ($model->toquvDocumentItems) {
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
                                        $isSaved = true;
                                    }else{
                                        $isSaved = false;
                                        break;
                                    }
                                }
                                /*$TDItems = $cloneAccept->getToquvDocumentItems()->asArray()->all();
                                $flagIB = false;
                                if (!empty($TDItems)) {
                                    //items loop
                                    foreach ($TDItems as $item) {
                                        $flagIB = false;
                                        $ItemBalanceModel = new ToquvMatoItemBalance();
                                        $item['department_id'] = $cloneAccept->to_department;
                                        $item['musteri_id'] = $cloneAccept->musteri_id;
                                        $lastRec = ToquvMatoItemBalance::getLastRecordMoving($item);
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
                                            $attributesTIB['lot'] = $item['lot'];
                                            $attributesTIB['count'] = $item['quantity'];
                                            $attributesTIB['roll_count'] = $item['roll_count'];
                                            $attributesTIB['department_id'] = $cloneAccept->to_department;
                                            $attributesTIB['from_department'] = ($from_department)?$from_department:null;
                                            $attributesTIB['document_type'] = $cloneAccept->document_type;
                                            $attributesTIB['musteri_id'] = $item['musteri_id'];
                                            $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($cloneAccept->reg_date));
                                        } else {
                                            $attributesTIB['entity_id'] = $item['entity_id'];
                                            $attributesTIB['entity_type'] = $item['entity_type'];
                                            $attributesTIB['is_own'] = $item['is_own'];
                                            $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                            $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                            $attributesTIB['document_id'] = $cloneAccept->id;
                                            $attributesTIB['inventory'] = $item['quantity'];
                                            $attributesTIB['roll_inventory'] = $item['roll_count'];
                                            $attributesTIB['lot'] = $item['lot'];
                                            $attributesTIB['count'] = $item['quantity'];
                                            $attributesTIB['roll_count'] = $item['roll_count'];
                                            $attributesTIB['department_id'] = $cloneAccept->to_department;
                                            $attributesTIB['from_department'] = ($from_department)?$from_department:null;
                                            $attributesTIB['document_type'] = $cloneAccept->document_type;
                                            $attributesTIB['musteri_id'] = $item['musteri_id'];
                                            $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($cloneAccept->reg_date));
                                            $lastRec = new ToquvMatoItemBalance();
                                        }
                                        $ItemBalanceModel->setAttributes($attributesTIB);
                                        if ($ItemBalanceModel->save()) {
                                            $flagIB = true;
                                        }

                                    }
                                }*/
                            }
                        }
                    }
                    if ($isSaved&&$flagToquvItems){
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                        return $this->redirect(["view", 'id' => $id,
                            'mato_id'=>$mato_id,
                            'pus_fine_id'=>$pus_fine_id,
                            'thread_length'=>$thread_length,
                            'finish_en'=>$finish_en,
                            'finish_gramaj'=>$finish_gramaj,
                            'brak'=>$brak,
                        ]);
                    }else{
                        $transaction->rollBack();
                    }
                }
            }catch (\Exception $e){
                Yii::info('Not saved' . $e, 'save');
                $transaction->rollBack();
                return $this->render('save-and-finish', [
                    'model' => $model,
                    'kalite' => $kalite,
                    'kalite_all' => $dataProvider->models,
                    'to_employe' => ArrayHelper::map($to_employe,'id','user_fio'),
                    'brak' => $brak,
                    'searchModel' => $searchModel,
                ]);
            }
        }
        return $this->render('save-and-finish', [
            'model' => $model,
            'kalite' => $kalite,
            'kalite_all' => $dataProvider->models,
            'to_employe' => ArrayHelper::map($to_employe,'id','user_fio'),
            'brak' => $brak,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "mato_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => ToquvKalite::find()->select([
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
     * Finds the ToquvKalite model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvKalite the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvKalite::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
