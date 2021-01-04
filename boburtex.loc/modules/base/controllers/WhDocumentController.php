<?php

namespace app\modules\base\controllers;

use app\modules\base\models\BoyahaneMixingItems;
use app\modules\base\models\WhDocumentItems;
use app\modules\base\models\WhDocumentItemsSearch;
use app\modules\base\models\WhItemBalance;
use Yii;
use app\modules\base\models\WhDocument;
use app\modules\base\models\WhDocumentSearch;
use yii\db\Exception;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * WhDocumentController implements the CRUD actions for WhDocument model.
 */
class WhDocumentController extends BaseController
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
                    'delete' => ['POST', 'GET'],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $slug = Yii::$app->request->get('slug');
            $flag = false;
            if (!empty($slug)) {
                if (array_key_exists($slug, WhDocument::getDocTypeBySlug())) {
                    $flag = true;
                    $this->slug = $slug;
                }
            }
            if (!$flag) {
                throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
            }
            if (Yii::$app->authManager->getPermission(Yii::$app->controller->id . "/" . Yii::$app->controller->action->id)) {
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
     * Lists all WhDocument models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WhDocumentSearch();
        $entityType = 1;
        $department_field = 'to_department';
        switch ($this->slug) {
            case WhDocument::DOC_TYPE_INCOMING_LABEL:
                $docType = WhDocument::DOC_TYPE_INCOMING;
                $countPending = WhDocument::getCountPending($searchModel->getDepartments());
                break;
            case WhDocument::DOC_TYPE_MOVING_LABEL:
                $docType = WhDocument::DOC_TYPE_MOVING;
                $department_field = 'from_department';
                break;
            case WhDocument::DOC_TYPE_SELLING_LABEL:
                $docType = WhDocument::DOC_TYPE_SELLING;
                $department_field = 'from_department';
                break;
            case WhDocument::DOC_TYPE_OUTGOING_LABEL:
                $docType = WhDocument::DOC_TYPE_OUTGOING;
                $department_field = 'from_department';
                break;
            case WhDocument::DOC_TYPE_RETURN_LABEL:
                $docType = WhDocument::DOC_TYPE_RETURN;
                $department_field = 'from_department';
                break;
            case WhDocument::DOC_TYPE_PENDING_LABEL:
                $docType = WhDocument::DOC_TYPE_PENDING;
                $countPending = WhDocument::getCountPending($searchModel->getDepartments());
                break;
            case WhDocument::DOC_TYPE_MIXING_LABEL:
                $department_field = 'from_department';
                $docType = WhDocument::DOC_TYPE_MIXING;
                break;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $docType, $department_field);

        return $this->render("index/index_{$this->slug}", [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'countPending' => $countPending,
        ]);
    }

    /**
     * Displays a single WhDocument model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new WhDocumentItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        $dataProvider->pagination = false;
        return $this->render("view/view_{$this->slug}", [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new WhDocument model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $model = new WhDocument();
        $models = [new WhDocumentItems()];
        //$modelTDE = new BichuvDocExpense();
        $model->reg_date = date('d.m.Y');
        $lastId = $model::find()->select('id')->orderBy(['id'=>SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $model->doc_number =  "W00".$lastId . date('-dm')."/" . date('Y');
        if (Yii::$app->request->isPost) {
            //VarDumper::dump(Yii::$app->request->post(WhDocumentItems::getModelName(), 10,true));exit();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $data = Yii::$app->request->post();
                $data['WhDocument']['reg_date'] = date('Y-m-d H:i:s', strtotime($data['WhDocument']['reg_date']." ".date('H:i:s')));
                $DIModelName = WhDocumentItems::getModelName();
                $dataTDI = Yii::$app->request->post($DIModelName, []);
                if(isset($data[$DIModelName])){
                    unset($data[$DIModelName]);
                }
                if ($model->load($data) && $model->save()) {
                    /*$data['BichuvDocExpense']['document_id'] = $model->id;
                    if(!empty($data['BichuvDocExpense']['price']) && $data['BichuvDocExpense']['price'] > 0){
                        if($modelTDE->load($data) && $modelTDE->save()){

                        }
                    }*/
                    if ($model->document_type == $model::DOC_TYPE_MIXING) {
                        $mixing_item = Yii::$app->request->post("WhDocument")['mixing_item'];
                        $modelBMI = new BoyahaneMixingItems();
                        $modelBMI->wh_document_id = $model->id;
                        $modelBMI->entity_id = $mixing_item['new_item'];
                        $modelBMI->entity_type = 1;
                        $modelBMI->quantity = $mixing_item['quantity'];
                        $modelBMI->unit_id = $mixing_item['unit_id'];
                        $modelBMI->save();

                    }
                    foreach ($dataTDI as $item){
                        $modelDI = new WhDocumentItems();
                        $savedDataTDI[$DIModelName] = $item;
                        $savedDataTDI[$DIModelName]['wh_document_id'] = $model->id;
                        $savedDataTDI[$DIModelName]['status'] = $model::STATUS_ACTIVE;
                        $modelDI->load($savedDataTDI);
                        if ($model->document_type == $model::DOC_TYPE_INCOMING) {
                            $modelDI->incoming_price = $item['incoming_price'] ? $item['incoming_price'] : 0;
                            $modelDI->wh_price = $item['incoming_price'] ? $item['incoming_price'] : 0;
                            $modelDI->wh_pb_id = $item['incoming_pb_id'];
                            $modelDI->package_qty = $item['package_qty'];
                        } elseif ($model->document_type == $model::DOC_TYPE_MOVING) {
                            $modelDI->wh_price = $modelDI->whItemBalance->wh_price;
                            $modelDI->wh_pb_id = $modelDI->whItemBalance->wh_pb_id;
                            $modelDI->document_qty = $item['quantity'];
                            $modelDI->lot = $modelDI->whItemBalance->lot;
                            $modelDI->package_qty = $item['package_qty'];
                            $modelDI->package_type = $modelDI->whItemBalance->package_type;
                        } elseif ($model->document_type == $model::DOC_TYPE_SELLING) {
                            $modelDI->wh_price = $modelDI->whItemBalance->wh_price;
                            $modelDI->wh_pb_id = $modelDI->whItemBalance->wh_pb_id;
                            $modelDI->sell_price = $modelDI->whItemBalance->wh_price;
                            $modelDI->sell_pb_id = $modelDI->whItemBalance->wh_pb_id;
                            $modelDI->document_qty = $item['quantity'];
                            $modelDI->lot = $modelDI->whItemBalance->lot;
                            $modelDI->package_qty = $item['package_qty'];
                            $modelDI->package_type = $modelDI->whItemBalance->package_type;
                        } elseif ($model->document_type == $model::DOC_TYPE_OUTGOING) {
                            $modelDI->wh_price = $modelDI->whItemBalance->wh_price;
                            $modelDI->wh_pb_id = $modelDI->whItemBalance->wh_pb_id;
                            $modelDI->sell_price = $modelDI->whItemBalance->wh_price;
                            $modelDI->sell_pb_id = $modelDI->whItemBalance->wh_pb_id;
                            $modelDI->document_qty = $item['quantity'];
                            $modelDI->lot = $modelDI->whItemBalance->lot;
                            $modelDI->package_qty = $item['package_qty'];
                            $modelDI->package_type = $modelDI->whItemBalance->package_type;
                        } elseif ($model->document_type == $model::DOC_TYPE_MIXING) {
//                            echo "<pre>";
////                            print_r($_POST);
//                            print_r($modelDI);
//                            print_r($modelDI->whItemBalance);
//                            $transaction->rollBack();
//                            die;

                            $modelDI->entity_id = $modelDI->whItemBalance->entity_id;
                            $modelDI->package_qty = $modelDI->whItemBalance->package_qty;
                            $modelDI->wh_price = $modelDI->whItemBalance->wh_price;
                            $modelDI->wh_pb_id = $modelDI->whItemBalance->wh_pb_id;
                            $modelDI->sell_price = $modelDI->whItemBalance->wh_price;
                            $modelDI->sell_pb_id = $modelDI->whItemBalance->wh_pb_id;
                            $modelDI->document_qty = $item['quantity'];
                            $modelDI->lot = $modelDI->whItemBalance->lot;
                            $modelDI->package_qty = $item['package_qty'];
                            $modelDI->package_type = $modelDI->whItemBalance->package_type;

                        }

                        //print_r($savedDataTDI);
                        if($modelDI->save()){
                            unset($modelDI);
                            $saved = true;
                        } else {
                            $saved = false;
                            break;
                        }

                    }

                    if ($saved) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                        return $this->redirect(["view", 'id' => $model->id, 'slug' => $this->slug]);
                    } else {
                        $transaction->rollBack();
                    }

                    Yii::$app->session->setFlash('success',Yii::t('app','Saved Successfully'));
                    return $this->redirect(["view", 'id' => $model->id,'slug' => $this->slug]);
                }
            } catch (Exception $e) {
                Yii::info('All not saved ' . $e, 'save');
            }
        }

        return $this->render('create', [
            'model' => $model,
            'models' => $models,
            'modelTDE' => $modelTDE
        ]);
    }

    /**
     * Updates an existing WhDocument model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models = !empty($model->whDocumentItems) ? $model->whDocumentItems : [new WhDocumentItems()];

        /*if (!empty($model->bichuvDocExpenses) && !empty($model->bichuvDocExpenses[0])) {
            $modelTDE = $model->bichuvDocExpenses[0];
        } else {
            $modelTDE = new BichuvDocExpense();
        }*/

        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $data = Yii::$app->request->post();
                $data['WhDocument']['reg_date'] = date('Y-m-d H:i:s', strtotime($data['WhDocument']['reg_date']." ".date('H:i:s')));
                $DIModelName = WhDocumentItems::getModelName();
                $dataTDI = Yii::$app->request->post($DIModelName);
                if(isset($data[$DIModelName])){
                    unset($data[$DIModelName]);
                }

                if ($model->load($data) && $model->save()) {

                    //delete old all data
                    if (!empty($model->whDocumentItems)) {
                        WhDocumentItems::deleteAll(['wh_document_id'=>$model->id]);
                    }

                    //delete old all data
                    /*if (!empty($model->bichuvDocExpenses)) {
                        $model->bichuvDocExpenses->deleteAll();
                    }*/

                    foreach ($dataTDI as $item){
                        $modelDI = new WhDocumentItems();
                        $savedDataTDI[$DIModelName] = $item;
                        $savedDataTDI[$DIModelName]['wh_document_id'] = $model->id;
                        $savedDataTDI[$DIModelName]['status'] = $model::STATUS_ACTIVE;
                        $modelDI->load($savedDataTDI);
                        if ($model->document_type == $model::DOC_TYPE_INCOMING) {
                            $modelDI->incoming_price = $item['incoming_price'] ? $item['incoming_price'] : 0;
                            $modelDI->wh_price = $item['incoming_price'] ? $item['incoming_price'] : 0;
                            $modelDI->wh_pb_id = $item['incoming_pb_id'];
                            $modelDI->package_qty = $item['package_qty'];
                        } elseif ($model->document_type == $model::DOC_TYPE_MOVING) {
                            $modelDI->wh_price = $modelDI->whItemBalance->wh_price;
                            $modelDI->wh_pb_id = $modelDI->whItemBalance->wh_pb_id;
                            $modelDI->document_qty = $item['quantity'];
                            $modelDI->lot = $modelDI->whItemBalance->lot;
                            $modelDI->package_qty = $item['package_qty'];
                            $modelDI->package_type = $modelDI->whItemBalance->package_type;
                        } elseif ($model->document_type == $model::DOC_TYPE_SELLING) {
                            $modelDI->wh_price = $modelDI->whItemBalance->wh_price;
                            $modelDI->wh_pb_id = $modelDI->whItemBalance->wh_pb_id;
                            $modelDI->sell_price = $modelDI->whItemBalance->wh_price;
                            $modelDI->sell_pb_id = $modelDI->whItemBalance->wh_pb_id;
                            $modelDI->document_qty = $item['quantity'];
                            $modelDI->lot = $modelDI->whItemBalance->lot;
                            $modelDI->package_qty = $item['package_qty'];
                            $modelDI->package_type = $modelDI->whItemBalance->package_type;
                        } elseif ($model->document_type == $model::DOC_TYPE_OUTGOING) {
                            $modelDI->wh_price = $modelDI->whItemBalance->wh_price;
                            $modelDI->wh_pb_id = $modelDI->whItemBalance->wh_pb_id;
                            $modelDI->sell_price = $modelDI->whItemBalance->wh_price;
                            $modelDI->sell_pb_id = $modelDI->whItemBalance->wh_pb_id;
                            $modelDI->document_qty = $item['quantity'];
                            $modelDI->lot = $modelDI->whItemBalance->lot;
                            $modelDI->package_qty = $item['package_qty'];
                            $modelDI->package_type = $modelDI->whItemBalance->package_type;
                        }
                        /*$transaction->rollBack();
                        VarDumper::dump($modelDI, 10, true); exit();*/
                        if($modelDI->save()){
                            unset($modelDI);
                            $saved = true;
                        } else {
                            $saved = false;
                            break;
                        }
                    }

                    if ($saved) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                        return $this->redirect(["view", 'id' => $model->id, 'slug' => $this->slug]);
                    } else {
                        $transaction->rollBack();
                    }

                    Yii::$app->session->setFlash('success',Yii::t('app','Saved Successfully'));
                    return $this->redirect(["view", 'id' => $model->id,'slug' => $this->slug]);
                }
            } catch (Exception $e) {
                Yii::info('All not saved ' . $e, 'save');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'models' => $models,
            'modelTDE' => $modelTDE,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndFinish($id)
    {
        $model = $this->findModel($id);
        if ($model->status !== $model::STATUS_SAVED) {
            switch ($model->document_type) {
                case $model::DOC_TYPE_INCOMING:
                    $TDItems = $model->getWhDocumentItems()->asArray()->all();
                    $flagIB = false;
                    $total = [];
                    $total['sum'] = 0;
                    $total['usd'] = 0;

                    if (!empty($TDItems)) {
                        foreach ($TDItems as $item) {

                            $flagIB = false;
                            $ItemBalanceModel = new WhItemBalance();
                            $item['department_id'] = $model->to_department;
                            $inventory = WhItemBalance::getLastRecord($item);

                            $attributesTIB = [
                                'entity_id' => $item['entity_id'],
                                'entity_type' => $item['entity_type'],
                                'lot' => $item['lot'],
                                'quantity' => $item['quantity'],
                                'inventory' => ($inventory['inventory'] + $item['quantity']),
                                'package_type' => $item['package_type'],
                                'package_qty' => $item['package_qty'],
                                'package_inventory' => ($inventory['package_inventory'] + $item['package_qty']),
                                'wh_price' => $item['wh_price'],
                                'wh_pb_id' => $item['wh_pb_id'],
                                'dep_section' => $item['dep_section'],
                                'dep_area' => $item['dep_area'],
                                'wh_document_id' => $model->id,
                                'department_id' => $model->to_department,
                                'reg_date' => date('Y-m-d H:i:s')
                            ];

                            $total['sum'] += $item['price_sum'] * $item['quantity'];
                            $total['usd'] += $item['price_usd'] * $item['quantity'];

                            $ItemBalanceModel->setAttributes($attributesTIB);
                            if ($ItemBalanceModel->save()) {
                                $flagIB = true;
                            } else {
                                break;
                            }
                        }
                    }
                    if ($flagIB) {
                        $model->updateCounters(['status' => 2]);
                    }

                    // **********************    Bichuv_saldo ****************************** //

                    /*if ($total['sum'] > 0) {
                        $bichuvSaldo1 = new BichuvSaldo();
                        $attrBS1 = [
                            'musteri_id' => $model->musteri_id,
                            'department_id' => $model->to_department,
                            'operation' => '1', // income
                            'comment' => $model->doc_number,
                            'payment_method' => $model->payment_method,
                            'bd_id' => $model->id,
                            'reg_date' => date('Y-m-d H:i:s'),
                            'summa' => $total['sum'] ? $total['sum'] : $total['usd'],
                            'credit1' => $total['sum'],
                            'debit2' => $total['sum'],
                            'pb_id' => 1,
                        ];

                        $bichuvSaldo1->setAttributes($attrBS1);
                        $bichuvSaldo1->save();
                    }*/

                    /*if ($total['usd'] > 0) {
                        $bichuvSaldo2 = new BichuvSaldo();
                        $attrBS2 = [
                            'musteri_id' => $model->musteri_id,
                            'department_id' => $model->to_department,
                            'operation' => '1', // income
                            'comment' => $model->doc_number,
                            'payment_method' => $model->payment_method,
                            'bd_id' => $model->id,
                            'reg_date' => date('Y-m-d H:i:s'),
                            'summa' => $total['sum'] ? $total['sum'] : $total['usd'],
                            'credit1' => $total['usd'],
                            'debit2' => $total['usd'],
                            'pb_id' => 2,
                        ];
                        $bichuvSaldo2->setAttributes($attrBS2);
                        $bichuvSaldo2->save();
                    }

                    if ($model->paid_amount > 0) {
                        $bichuvSaldo3 = new BichuvSaldo();
                        $attrBS3 = [
                            'musteri_id' => $model->musteri_id,
                            'department_id' => $model->to_department,
                            'operation' => '2', // outcome
                            'comment' => $model->doc_number,
                            'payment_method' => $model->payment_method,
                            'bd_id' => $model->id,
                            'reg_date' => date('Y-m-d H:i:s'),
                            'summa' => $total['sum'] ? $total['sum'] : $total['usd'],
                            'debit1' => $model->paid_amount,
                            'credit2' => $model->paid_amount,
                            'pb_id' => $model->payment_method,
                        ];
                        $bichuvSaldo3->setAttributes($attrBS3);
                        $bichuvSaldo3->save();
                    }*/
                    break;
                case $model::DOC_TYPE_MOVING:
                    $TDItems = $model->getWhDocumentItems()->asArray()->all();

                    $transaction = Yii::$app->db->beginTransaction();
                    try {

                        /*     create new income document  */

                        $cloneAccept = new WhDocument();
                        $cloneAccept->setAttributes($model->attributes);
                        unset($cloneAccept->id,
                            $cloneAccept->created_by,
                            $cloneAccept->updated_by,
                            $cloneAccept->updated_at,
                            $cloneAccept->created_at);

                        $cloneAccept->document_type = $model::DOC_TYPE_PENDING;
                        $cloneAccept->reg_date = date('Y-m-d H:i:s');
                        $cloneAccept->doc_number = "WM00" . ($model->id + 1) . date('-dm') . "/" . date('Y');

                        if (!$cloneAccept->save()) {
                            Yii::$app->session->setFlash('error', Yii::t('app', 'Saqlashda xatolik!'));
                            $transaction->rollBack();
                            return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                        }

                        /*     end crating new  income document    */

                        foreach ($TDItems as $item) {
                            $item['department_id'] = $model->from_department;
                            $remain = WhItemBalance::getLastRecord($item);
                           /* $transaction->rollBack();
                            VarDumper::dump($remain, 10 , true); exit();*/

                            if (($remain['inventory'] - $item['quantity']) < 0) {
                                $lack_qty = $item['quantity'] - $remain['inventory'];
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                    ['id' => $item['id'], 'lack' => $lack_qty]));
                                $transaction->rollBack();
                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                            }

                            $ItemBalanceModel = new WhItemBalance();

                            $attributesTIB = [
                                'entity_id' => $item['entity_id'],
                                'entity_type' => $item['entity_type'],
                                'lot' => $item['lot'],
                                'quantity' => (-1) * $item['quantity'],
                                'inventory' => ($remain['inventory'] - $item['quantity']),
                                'package_type' => $item['package_type'],
                                'package_qty' => $item['package_qty'],
                                'package_inventory' => ($remain['package_inventory'] - $item['package_qty']),
                                'wh_price' => $item['wh_price'],
                                'wh_pb_id' => $item['wh_pb_id'],
                                'dep_section' => $item['dep_section'],
                                'dep_area' => $item['dep_area'],
                                'wh_document_id' => $model->id,
                                'department_id' => $model->from_department,
                                'reg_date' => date('Y-m-d H:i:s')
                            ];

                            $ItemBalanceModel->setAttributes($attributesTIB);
                            if (!$ItemBalanceModel->save()) {
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Saqlashda xatolik!'));
                                $transaction->rollBack();
                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                            }

                            /*    new income document items */
                            $cloneDItems = new WhDocumentItems();
                            $cloneDItems->setAttributes($item);
                            unset($cloneDItems->id, $cloneDItems->wh_item_balance_id);
                            $cloneDItems->wh_document_id = $cloneAccept->id;
                            $cloneDItems->quantity = abs($cloneDItems->quantity);

                            if (!$cloneDItems->save()) {
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Saqlashda xatolik!'));
                                $transaction->rollBack();
                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                            }
                            /*    new income document items */

                        }

                        $model->updateCounters(['status' => 2]);
                        $transaction->commit();

                    } catch (Exception $e) {
                        $transaction->rollBack();
                        Yii::info('All not saved ' . $e, 'save');
                    }
                    break;
                case $model::DOC_TYPE_PENDING:
                    $TDItems = $model->getWhDocumentItems()->asArray()->all();
                    $flagIB = false;
                    $total = [];
                    $total['sum'] = 0;
                    $total['usd'] = 0;

                    if (!empty($TDItems)) {
                        foreach ($TDItems as $item) {

                            $flagIB = false;
                            $ItemBalanceModel = new WhItemBalance();
                            $item['department_id'] = $model->to_department;
                            $inventory = WhItemBalance::getLastRecord($item);

                            $attributesTIB = [
                                'entity_id' => $item['entity_id'],
                                'entity_type' => $item['entity_type'],
                                'lot' => $item['lot'],
                                'quantity' => $item['quantity'],
                                'inventory' => ($inventory['inventory'] + $item['quantity']),
                                'package_type' => $item['package_type'],
                                'package_qty' => $item['package_qty'],
                                'package_inventory' => ($inventory['package_inventory'] + $item['package_qty']),
                                'wh_price' => $item['wh_price'],
                                'wh_pb_id' => $item['wh_pb_id'],
                                'wh_document_id' => $model->id,
                                'department_id' => $model->to_department,
                                'reg_date' => date('Y-m-d H:i:s')
                            ];

                            $total['sum'] += $item['price_sum'] * $item['quantity'];
                            $total['usd'] += $item['price_usd'] * $item['quantity'];

                            $ItemBalanceModel->setAttributes($attributesTIB);
                            if ($ItemBalanceModel->save()) {
                                $flagIB = true;
                            } else {
                                break;
                            }
                        }
                    }
                    if ($flagIB) {
                        $model->updateCounters(['status' => 2]);
                    }

                    // **********************    Bichuv_saldo ****************************** //

                    /*if ($total['sum'] > 0) {
                        $bichuvSaldo1 = new BichuvSaldo();
                        $attrBS1 = [
                            'musteri_id' => $model->musteri_id,
                            'department_id' => $model->to_department,
                            'operation' => '1', // income
                            'comment' => $model->doc_number,
                            'payment_method' => $model->payment_method,
                            'bd_id' => $model->id,
                            'reg_date' => date('Y-m-d H:i:s'),
                            'summa' => $total['sum'] ? $total['sum'] : $total['usd'],
                            'credit1' => $total['sum'],
                            'debit2' => $total['sum'],
                            'pb_id' => 1,
                        ];

                        $bichuvSaldo1->setAttributes($attrBS1);
                        $bichuvSaldo1->save();
                    }*/

                    /*if ($total['usd'] > 0) {
                        $bichuvSaldo2 = new BichuvSaldo();
                        $attrBS2 = [
                            'musteri_id' => $model->musteri_id,
                            'department_id' => $model->to_department,
                            'operation' => '1', // income
                            'comment' => $model->doc_number,
                            'payment_method' => $model->payment_method,
                            'bd_id' => $model->id,
                            'reg_date' => date('Y-m-d H:i:s'),
                            'summa' => $total['sum'] ? $total['sum'] : $total['usd'],
                            'credit1' => $total['usd'],
                            'debit2' => $total['usd'],
                            'pb_id' => 2,
                        ];
                        $bichuvSaldo2->setAttributes($attrBS2);
                        $bichuvSaldo2->save();
                    }

                    if ($model->paid_amount > 0) {
                        $bichuvSaldo3 = new BichuvSaldo();
                        $attrBS3 = [
                            'musteri_id' => $model->musteri_id,
                            'department_id' => $model->to_department,
                            'operation' => '2', // outcome
                            'comment' => $model->doc_number,
                            'payment_method' => $model->payment_method,
                            'bd_id' => $model->id,
                            'reg_date' => date('Y-m-d H:i:s'),
                            'summa' => $total['sum'] ? $total['sum'] : $total['usd'],
                            'debit1' => $model->paid_amount,
                            'credit2' => $model->paid_amount,
                            'pb_id' => $model->payment_method,
                        ];
                        $bichuvSaldo3->setAttributes($attrBS3);
                        $bichuvSaldo3->save();
                    }*/
                    break;
                case $model::DOC_TYPE_SELLING:
                    $TDItems = $model->getWhDocumentItems()->asArray()->all();

                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        foreach ($TDItems as $item) {
                            $item['department_id'] = $model->from_department;
                            $remain = WhItemBalance::getLastRecord($item);
                            /* $transaction->rollBack();
                             VarDumper::dump($remain, 10 , true); exit();*/

                            if (($remain['inventory'] - $item['quantity']) < 0) {
                                $lack_qty = $item['quantity'] - $remain['inventory'];
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                    ['id' => $item['id'], 'lack' => $lack_qty]));
                                $transaction->rollBack();
                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                            }

                            $ItemBalanceModel = new WhItemBalance();

                            $attributesTIB = [
                                'entity_id' => $item['entity_id'],
                                'entity_type' => $item['entity_type'],
                                'lot' => $item['lot'],
                                'quantity' => (-1) * $item['quantity'],
                                'inventory' => ($remain['inventory'] - $item['quantity']),
                                'package_type' => $item['package_type'],
                                'package_qty' => $item['package_qty'],
                                'package_inventory' => ($remain['package_inventory'] - $item['package_qty']),
                                'wh_price' => $item['wh_price'],
                                'wh_pb_id' => $item['wh_pb_id'],
                                'sell_price' => $item['sell_price'],
                                'sell_pb_id' => $item['sell_pb_id'],
                                'dep_section' => $item['dep_section'],
                                'dep_area' => $item['dep_area'],
                                'wh_document_id' => $model->id,
                                'department_id' => $model->from_department,
                                'reg_date' => date('Y-m-d H:i:s')
                            ];

                            $ItemBalanceModel->setAttributes($attributesTIB);
                            if (!$ItemBalanceModel->save()) {
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Saqlashda xatolik!'));
                                $transaction->rollBack();
                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                            }

                        }

                        $model->updateCounters(['status' => 2]);
                        $transaction->commit();

                    } catch (Exception $e) {
                        $transaction->rollBack();
                        Yii::info('All not saved ' . $e, 'save');
                    }
                    break;
                case $model::DOC_TYPE_OUTGOING:

                    $TDItems = $model->getWhDocumentItems()->asArray()->all();
                    $transaction = Yii::$app->db->beginTransaction();
                    try {

                        foreach ($TDItems as $item) {
                            $item['department_id'] = $model->from_department;
                            $remain = WhItemBalance::getLastRecord($item);
//                             $transaction->rollBack();
//                             VarDumper::dump($remain, 10 , true); exit();

                            if (($remain['inventory'] - $item['quantity']) < 0) {
                                $lack_qty = $item['quantity'] - $remain['inventory'];
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                    ['id' => $item['id'], 'lack' => $lack_qty]));
                                $transaction->rollBack();
                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                            }

                            $ItemBalanceModel = new WhItemBalance();

                            $attributesTIB = [
                                'entity_id' => $item['entity_id'],
                                'entity_type' => $item['entity_type'],
                                'lot' => $item['lot'],
                                'quantity' => (-1) * $item['quantity'],
                                'inventory' => ($remain['inventory'] - $item['quantity']),
                                'package_type' => $item['package_type'],
                                'package_qty' => $item['package_qty'],
                                'package_inventory' => ($remain['package_inventory'] - $item['package_qty']),
                                'wh_price' => $item['wh_price'],
                                'wh_pb_id' => $item['wh_pb_id'],
                                'sell_price' => $item['sell_price'],
                                'sell_pb_id' => $item['sell_pb_id'],
                                'dep_section' => $item['dep_section'],
                                'dep_area' => $item['dep_area'],
                                'wh_document_id' => $model->id,
                                'department_id' => $model->from_department,
                                'reg_date' => date('Y-m-d H:i:s')
                            ];

                            $ItemBalanceModel->setAttributes($attributesTIB);
                            if (!$ItemBalanceModel->save()) {
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Saqlashda xatolik!'));
                                $transaction->rollBack();
                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                            }

                        }

                        $model->updateCounters( ['status' => 2]);
                        $transaction->commit();

                    } catch (Exception $e) {
                        $transaction->rollBack();
                        Yii::info('All not saved ' . $e, 'save');
                    }
                    break;
                case $model::DOC_TYPE_MIXING:

                    $TDItems = $model->getWhDocumentItems()->asArray()->all();
                    $MixingItems = BoyahaneMixingItems::find()->where(['wh_document_id' => $model->id])->asArray()->all();
                    $flagIB = false;
                    $total = [];
                    $total['sum'] = 0;
                    $total['usd'] = 0;


                        $MixingItems['department_id'] = $model->from_department;
                        $remain = WhItemBalance::getLastRecord($MixingItems);
                        if (($remain['inventory'] - $MixingItems['quantity']) < 0) {
                            $lack_qty = $MixingItems['quantity'] - $remain['inventory'];
                            Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                ['id' => $MixingItems['id'], 'lack' => $lack_qty]));
                            return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                        }
                        $ItemBalanceModel = new WhItemBalance();
                        $attributesTIB = [
                            'entity_id' => $MixingItems['entity_id'],
                            'entity_type' => $MixingItems['entity_type'],
                            'lot' => $MixingItems['lot'],
                            'quantity' => (-1) * $MixingItems['quantity'],
                            'inventory' => ($remain['inventory'] - $MixingItems['quantity']),
                            'package_type' => $MixingItems['package_type'],
                            'package_qty' => $MixingItems['package_qty'],
                            'package_inventory' => ($remain['package_inventory'] - $MixingItems['package_qty']),
                            'wh_price' => $MixingItems['wh_price'],
                            'wh_pb_id' => $MixingItems['wh_pb_id'],
                            'sell_price' => $MixingItems['sell_price'],
                            'sell_pb_id' => $MixingItems['sell_pb_id'],
                            'dep_section' => $MixingItems['dep_section'],
                            'dep_area' => $MixingItems['dep_area'],
                            'wh_document_id' => $model->id,
                            'department_id' => $model->from_department,
                            'reg_date' => date('Y-m-d H:i:s')
                        ];
                        $ItemBalanceModel->setAttributes($attributesTIB);
                        if (!$ItemBalanceModel->save()) {
                            Yii::$app->session->setFlash('error', Yii::t('app', 'Saqlashda xatolik!'));
                            return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                        }


                    if (!empty($TDItems)) {
                        foreach ($TDItems as $item) {
                            $flagIB = false;
                            $ItemBalanceModel = new WhItemBalance();
                            $item['department_id'] = $model->to_department;
                            $inventory = WhItemBalance::getLastRecord($item);
                            $attributesTIB = [
                                'entity_id' => $item['entity_id'],
                                'entity_type' => $item['entity_type'],
                                'lot' => $item['lot'],
                                'quantity' => $item['quantity'],
                                'inventory' => ($inventory['inventory'] + $item['quantity']),
                                'package_type' => $item['package_type'],
                                'package_qty' => $item['package_qty'],
                                'package_inventory' => ($inventory['package_inventory'] + $item['package_qty']),
                                'wh_price' => $item['wh_price'],
                                'wh_pb_id' => $item['wh_pb_id'],
                                'dep_section' => $item['dep_section'],
                                'dep_area' => $item['dep_area'],
                                'wh_document_id' => $model->id,
                                'department_id' => $model->to_department,
                                'reg_date' => date('Y-m-d H:i:s')
                            ];
                            $total['sum'] += $item['price_sum'] * $item['quantity'];
                            $total['usd'] += $item['price_usd'] * $item['quantity'];
                            $ItemBalanceModel->setAttributes($attributesTIB);
                            if ($ItemBalanceModel->save()) {
                                $flagIB = true;
                            } else {
                                break; // TODO ayrishni tekshirish kk. Ishlamayapti
                            }
                        }
                    }
                    if ($flagIB) {
                        $model->status = $model::STATUS_SAVED;
                        $model->update(false);
                    }
                    break;
            }
        }
        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
    }

    /**
     * Deletes an existing WhDocument model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = $model::STATUS_INACTIVE;
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionAjaxRequest($q, $dept, $type)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['results'] = [];
        if (!empty($q)) {
            $params = [];
            $params['entity_type'] = $type;
            $params['department_id'] = $dept;
            $params['query'] = $q;
            $searchModel = new WhItemBalance();
            $res = $searchModel->searchEntities($params);
            //return $res;
            if (!empty($res)) {
                foreach ($res as $item) {
                    $name = $item['name'] . " " .
                            $item['type'] . " " .
                            $item['category']. " " .
                            $item['country']. " " .
                            ($item['lot'] ? "Lot:". $item['lot'] : "") .
                            " (" .$item['wh_price'] ." " .$item['currency'] ."/" .$item['unit'] .")";
                    array_push($response['results'], [
                        'id' => $item['id'],
                        'text' => $name,
                        'entity_id' => $item['entity_id'],
                        'inventory' => $item['inventory'],
                        'package_inventory' => $item['package_inventory'],
                        'package_type' => $item['package_type'],
                    ]);
                }
            } else {
                $response['results'] = [
                    'id' => '',
                    'name' => '',
                    'inventory' => 0,
                    'entity_id' => 0,
                ];
            }
        }
        return $response;
    }

    /**
     * @param $id
     * @return array
     * @throws Exception
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
                    where  tud.department_id = :id AND u.user_role <> 1 LIMIT 1";
            $result = Yii::$app->db->createCommand($sql)->bindValue(':id', $id)->queryOne();
            if ($result) {
                $response['status'] = 1;
                $response['id'] = $result['id'];
                $response['name'] = $result['user_fio'];
            }
        }
        return $response;
    }

    /**
     *
     */
    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "wh-document_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => WhDocument::find()->select([
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
     * @param $id
     * @param $type
     * @param $depId
     * @return array
     * @throws Exception
     */
    public function actionGetRemainEntity($id, $type, $depId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 0;

        $searchModel = new WhDocument();

        $params = [
            'id' => $id,
            'type' => $type,
            'depId' => $depId
        ];

        $res = $searchModel->getRemain($params);

        if (!empty($res)) {
            $response['status'] = 1;
            $response['remain'] = $res;
        }

        return $response;
    }

    /**
     * Finds the WhDocument model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WhDocument the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WhDocument::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
