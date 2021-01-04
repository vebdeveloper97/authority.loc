<?php

namespace app\modules\bichuv\controllers;

use app\modules\bichuv\models\SpareItem;
use app\modules\bichuv\models\SpareItemDocItemBalance;
use app\modules\bichuv\models\SpareItemDocItems;
use app\modules\bichuv\models\SpareItemDocItemsSearch;
use app\modules\bichuv\models\SpareItemProperty;
use Yii;
use app\modules\bichuv\models\SpareItemDoc;
use app\modules\bichuv\models\SpareItemDocSearch;
use yii\db\Exception;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SpareItemDocController implements the CRUD actions for SpareItemDoc model.
 */
class SpareItemDocController extends BaseController
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

    /**
     * @param $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $slug = Yii::$app->request->get('slug');
            $flag = false;
            if (!empty($slug)) {
                if (array_key_exists($slug, SpareItemDoc::getDocTypeBySlug())) {
                    $flag = true;
                    $this->slug = $slug;
                }
            }
            if (!$flag) {
                throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Lists all SpareItemDoc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpareItemDocSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SpareItemDoc model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new SpareItemDocItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render("view/_view_{$this->slug}", [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new SpareItemDoc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SpareItemDoc();
        $sapreItemDocItems = [new SpareItemDocItems()];
        $lastId = SpareItemDoc::find()->orderBy(['id' => SORT_DESC])->one() ?? 0;
        $model->reg_date = date('Y.m.d');
        $model->doc_number = 'MM-'.$lastId['id'].'/'.date('Y/m/d');
        $request = Yii::$app->request;

        if ($model->load($request->post())){
            $data = $request->post();
            if($this->slug = SpareItemDoc::DOC_TYPE_INCOMING_LABEL){
                $model->status = SpareItemDoc::STATUS_ACTIVE;
                if($model->save()){
                    if($model->getAllSave($data, $model->id)){
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                        return $this->redirect(['view', 'slug' => $this->slug, 'id' => $model->id]);
                    }
                    else{
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Document Items saqlanmadi'));
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                }
                else{
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Document saqlanmadi'));
                    return $this->redirect(Yii::$app->request->referrer);
                }


            }
        }

        return $this->render('create', [
            'model' => $model,
            'sapreItemDocItems' => $sapreItemDocItems,
        ]);
    }

    /**
     * Updates an existing SpareItemDoc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $sapreItemDocItems = $model->spareItemDocItems;
        if(empty($item)){
            $item = [new SpareItemDocItems()];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                $data = Yii::$app->request->post();
                $models = $data['SpareItemDocItems'];
                SpareItemDocItems::deleteAll([
                    'spare_item_doc_id' => $model->id
                ]);
                foreach ($models as $item){
                    $items = new SpareItemDocItems();
                    $items->spare_item_doc_id = $model->id;
                    $items->entity_id = $item['entity_id'];
                    $items->quantity = $item['quantity'];
                    $items->price_sum = $item['price_sum'];
                    $items->to_area = $item['to_area'];
                    $items->musteri_id =$model->musteri_id;
                    $items->status = SpareItemDocItems::STATUS_ACTIVE;
                    if($items->save()){
                        $saved = true;
                        unset($items);
                    }
                    else{
                        $saved = false;
                    }
                }
                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Updated'));
                    return $this->redirect(['view', 'slug' => $this->slug, 'id' => $model->id]);
                }
                else{
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }catch(\Exception $e){
                Yii::info('error Message '.$e->getMessage(),'save');
            }
            return $this->redirect(['view', 'slug' => $this->slug, 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'sapreItemDocItems' => $sapreItemDocItems
        ]);
    }

    /**
     * Deletes an existing SpareItemDoc model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndFinish($id)
    {
        $model = $this->findModel($id);
        if ($model->status < SpareItemDocItems::STATUS_SAVED) {
            $musteriId = $model->musteri_id;
            $slug = Yii::$app->request->get('slug');
            switch ($model->document_type) {
                case 1:
                    if ($slug == SpareItemDoc::DOC_TYPE_INCOMING_LABEL) {
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            $items = $model->getSpareItemDocItems()->asArray()->all();

                            $saved = false;
                            if (!empty($items)) {
                                $modelId = $model->id;
                                $deptTo = $model->to_department;
                                foreach ($items as $item) {
                                    $modelBIB = new SpareItemDocItemBalance();
                                    $musteri_id = $item['musteri_id'] ?? $musteriId;
                                    $checkExists = $modelBIB::getLastRecord($item, $musteri_id);
                                    $inventory = $item['quantity'];
                                    if ($checkExists) {
                                        $inventory = $checkExists;
                                    }
                                    $modelBIB->setAttributes([
                                        'entity_id' => $item['entity_id'],
                                        'doc_type' => 1,
                                        'inventory' => $inventory,
                                        'quantity' => $item['quantity'],
                                        'doc_id' => $modelId,
                                        'from_area' => $item['from_area'],
                                        'to_department' => $deptTo,
                                        'department_id' => $deptTo,
                                    ]);
                                    if ($modelBIB->save()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            } else {
                                $saved = true;
                            }
                            $model->updateCounters(['status' => 2]);
                            if ($saved) {
                                $transaction->commit();
                            } else {
                                $transaction->rollBack();
                            }
                        } catch (Exception $e) {
                            Yii::info('Not changed status to 3', 'save');
                        }
                    }
                    break;
            }
        }
        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "spare-item-doc_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => SpareItemDoc::find()->select([
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
     * Finds the SpareItemDoc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SpareItemDoc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SpareItemDoc::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAjaxBarcodeSpare()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response['status'] = 0;
        $barcode = $request->get('barcode');
        $slug = $request->get('slug');
        $department = $request->get('department');
        $model = new SpareItem();
        $getSpares = $model->getSpares($barcode, $slug, $department);
        if($getSpares){
            $response['status'] = 1;
            $response['results'] = $getSpares;
            return $response;
        }
        else{
            return $response;
        }
    }
}
