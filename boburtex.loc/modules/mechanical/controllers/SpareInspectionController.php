<?php

namespace app\modules\mechanical\controllers;

use app\modules\bichuv\models\SpareItemDocItemBalance;
use app\modules\mechanical\models\SpareInspectionItems;
use app\modules\mechanical\models\SpareItemRelHrEmployee;
use Yii;
use app\modules\mechanical\models\SpareInspection;
use app\modules\mechanical\models\search\SpareInspectionSearch;
use app\modules\mechanical\controllers\BaseController;
use yii\base\DynamicModel;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SpareInspectionController implements the CRUD actions for SpareInspection model.
 */
class SpareInspectionController extends BaseController
{
    public $slug;
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
                if ( SpareInspection::hasControlTypeLabel($slug) ) {
                    $flag = true;
                    $this->slug = $slug;
                }
            }
            if (!$flag) {
                throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
            }
            if (Yii::$app->authManager->getPermission(Yii::$app->controller->id . "/" . $this->slug . "/" . Yii::$app->controller->action->id)) {
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
     * Lists all SpareInspection models.
     * @return mixed
     */
    public function actionIndex()
    {
        $spare_list = [];
        $control_type = SpareInspection::getControlTypeBySlug($this->slug);

        $searchModel = new SpareInspectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$control_type);
        switch ($control_type){
            case SpareInspection::CONTROL_TYPE_EXPECTED:
                $spare_list = SpareItemRelHrEmployee::getSpareList();
                break;

        }
        return $this->render($this->slug.'/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'spareList' => $spare_list,
        ]);
    }

    /**
     * Displays a single SpareInspection model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render($this->slug.'/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SpareInspection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SpareInspection();
        $slug = $this->slug;
        $controlType = $model::getControlTypeBySlug($slug);
        $request = Yii::$app->request;

        switch ($controlType){
            case SpareInspection::CONTROL_TYPE_UNEXPECTED:
                $model->scenario = $model::SCENARIO_UNEXPEXTED;
                $model->control_type = $controlType;
                $models = [new SpareInspectionItems(['scenario' => SpareInspectionItems::SCENARIO_UNEXPECTED])];
                if ($request->isPost){
                    $dataItems = $request->post()['SpareInspectionItems'];
                    if (!empty($dataItems)){
                        foreach ($dataItems as $key => $dataItem){
                            $models[$key] = new SpareInspectionItems(['scenario' => SpareInspectionItems::SCENARIO_UNEXPECTED]);
                        }
                    }

                    if ($model->load($request->post()) && Model::loadMultiple($models,$request->post())) {
                        $transaction = Yii::$app->db->beginTransaction();
                        $saved = false;
                        try{
                            if ($saved = $model->save()){
                                foreach ($models as $itemModel){
                                    $itemModel->spare_inspection_id = $model->id;
                                    if($saved && $saved = $itemModel->save()){
                                        $saved = true;
                                    }else{
                                        if (!$itemModel->save())
                                            $saved = false;
                                        Yii::$app->session->setFlash('error' , Yii::t('app','Saqlashda xatolik!'));
                                        break;
                                    }
                                }
                            }
                            if($saved){
                                Yii::$app->session->setFlash('success', Yii::t('app','Saved Successfully'));
                                $transaction->commit();
                                return $this->redirect(['view', 'id' => $model->id,'slug' => $slug]);
                            }else{
                                $transaction->rollBack();
                            }
                        }catch(\Exception $e){
                            Yii::info('Not saved'.$e,'save');
                            $transaction->rollBack();
                        }
                    }
                }

            break;
        }

        return $this->render($this->slug.'/create', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * Updates an existing SpareInspection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = self::findModel($id);
        $slug = $this->slug;
        $controlType = $model::getControlTypeBySlug($slug);
        $request = Yii::$app->request;

        switch ($controlType){
            case SpareInspection::CONTROL_TYPE_UNEXPECTED:
                $model->scenario = $model::SCENARIO_UNEXPEXTED;
                $model->control_type = $controlType;
                $models = (!empty($model->spareInspectionItems)) ? $model->spareInspectionItems :[new SpareInspectionItems(['scenario' => SpareInspectionItems::SCENARIO_UNEXPECTED])];
                if ($request->isPost){
                    $dataItems = $request->post()['SpareInspectionItems'];
                    if (!empty($dataItems)){
                        foreach ($dataItems as $key => $dataItem){
                            $models[$key] = new SpareInspectionItems(['scenario' => SpareInspectionItems::SCENARIO_UNEXPECTED]);
                        }
                    }
                    if ($model->load($request->post()) && Model::loadMultiple($models,$request->post())) {
                        $transaction = Yii::$app->db->beginTransaction();
                        $saved = false;
                        try{
                            if ($saved = $model->save()){
                                SpareInspectionItems::deleteAll(['spare_inspection_id' => $model->id]);
                                foreach ($models as $itemModel){
                                    $itemModel->spare_inspection_id = $model->id;
                                    if($saved && $saved = $itemModel->save()){
                                        $saved = true;
                                    }else{
                                        if (!$itemModel->save())
                                            $saved = false;
                                        Yii::$app->session->setFlash('error' , Yii::t('app','Saqlashda xatolik!'));
                                        break;
                                    }
                                }
                            }
                            if($saved){
                                Yii::$app->session->setFlash('success', Yii::t('app','Saved Successfully'));
                                $transaction->commit();
                                return $this->redirect(['view', 'id' => $model->id,'slug' => $slug]);
                            }else{
                                $transaction->rollBack();
                            }
                        }catch(\Exception $e){
                            Yii::info('Not saved'.$e,'save');
                            $transaction->rollBack();
                        }
                    }
                }

                break;
        }

        return $this->render($this->slug.'/update', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * Deletes an existing SpareInspection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        SpareInspectionItems::deleteAll(['spare_inspection_id' => $id]);
        $model->delete();
        return $this->redirect(['index','slug' => $this->slug]);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "spare-inspection_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => SpareInspection::find()->select([
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
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionEnded($id){
        $model = $this->findModel($id);
        $items = $model->spareInspectionItems;
        $controlType = $model->control_type;

        switch ($controlType){
            case SpareInspection::CONTROL_TYPE_UNEXPECTED:
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try{
                    /** kelayotgan itemlari ombordagi bilan solishtiradi*/
                    if (!empty($items)){
                        foreach ($items as $item) {
                            $data = [];
                            $data['entity_id'] = $item['spare_item_id'];
                            $data['department_id'] = null; // TODO extiyot qismlari omborni id beriladi
                            $lastRecordInventory = SpareItemDocItemBalance::getLastRecord($data);
                            if ($lastRecordInventory < $item->quantity){
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error',Yii::t('app','Omborda maxsulot yetarli emas')) ;
                                return $this->redirect(Yii::$app->request->referrer);
                            }else{
                                $saved = true;
                            }
                        }
                    }
                    /** Extiyot qismlari omboridan ishlatilgan maxsulotlarni ayrib tashlaydi **/
                    if ($saved){
                        foreach ($items as $item){
                            $lastSpareItemBalance = SpareItemDocItemBalance::findOne(['id' => $item['spare_item_balance_id']]);
                            $newSpareItemBalance = new SpareItemDocItemBalance();
                            $newSpareItemBalance->attributes = $lastSpareItemBalance->getAttributes();
                            $newSpareItemBalance->quantity = -1 * $item['quantity'];
                            $newSpareItemBalance->inventory = $lastSpareItemBalance->inventory - $item['quantity'];
                            if ($newSpareItemBalance->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                    if($saved){
                        $model->status = $model::STATUS_ENDED;
                        if ($model->save()){
                            $transaction->commit();
                            Yii::$app->session->setFlash('success' ,Yii::t('app','Saved Successfully'));
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    }else{
                        Yii::$app->session->setFlash('error' ,Yii::t('app','Saqlashda xatolik!'));
                        $transaction->rollBack();
                    }
                }catch(\Exception $e){
                    Yii::info('Not saved'.$e,'save');
                    $transaction->rollBack();
                }
                break;
        }

    }

    /**
     * @return array
     * @throws NotFoundHttpException
     * Extiyot qismalari omboridagi item qoldigini olib keladi
     */
    public function  actionGetSpareItemRemain(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $dynamicModel = new DynamicModel(['spare-item']);
        $dynamicModel->addRule(['spare-item'],'integer');
        $dynamicModel->setAttributes(['spare-item' => $data['spareItem']]);
        $response = [];
        $response['status'] = false;
        if ($dynamicModel->validate()){
            //TODO teshiriib spare doc item balance olib qo'yish kerak
            $item = SpareItemDocItemBalance::find()
                ->select(['id','inventory'])
                ->where(['entity_id' => $data['spareItem']])
                ->asArray()
                ->orderBy(['id' => SORT_DESC])
                ->one();
            if (!empty($item) && ($item['inventory'] > 0)){
                $response['status'] = true;
                $response['item'] = $item;
            }
            return $response;
        }else{
            return [
                'error' => true,
            ];
        }
        throw new NotFoundHttpException();
    }

    /**
     * @return array
     * Mashinalar qidiruvi
     */
    public function actionSearchMashine(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $response = [];
        $response['status'] = false;
        $spares = SpareItemRelHrEmployee::getSpareList($data);
        if (!empty($spares)){
            $response['items'] = $spares;
            $response['status'] = true;
        }

        return $response;
    }

    public function actionHistory(){
        
        $id = Yii::$app->request->post('id');
        $control_type = SpareInspection::getControlTypeBySlug($this->slug);
        $searchModel = new SpareInspectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$control_type,$id);

        if (Yii::$app->request->isAjax){
            return $this->renderAjax($this->slug.'/_history', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        return $this->render($this->slug.'/_history', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCron(){
        
    }
    /**
     * Finds the SpareInspection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SpareInspection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SpareInspection::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
