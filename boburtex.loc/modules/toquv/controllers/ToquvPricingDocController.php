<?php

namespace app\modules\toquv\controllers;

use Yii;
use app\modules\toquv\models\ToquvPricingDoc;
use app\modules\toquv\models\ToquvPricingDocSearch;
use app\modules\toquv\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\toquv\models\ToquvPricingItem;
use app\modules\toquv\models\ToquvPricingItemSearch;
/**
 * ToquvPricingDocController implements the CRUD actions for ToquvPricingDoc model.
 */
class ToquvPricingDocController extends BaseController
{
    public $slug;

    public function beforeAction($action)
    {
        if(parent::beforeAction($action)){
            $slug = Yii::$app->request->get('slug');
            $flag = false;
            if(!empty($slug)){
                if(array_key_exists($slug, ToquvPricingDoc::getDocTypeBySlug())){
                    $flag = true;
                    $this->slug = $slug;
                }
            }
            if(!$flag){
                throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
            }
            return true;
        }else{
            return false;
        }
    }
    /**
     * Lists all ToquvPricingDoc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvPricingDocSearch();
        switch ($this->slug){
            case ToquvPricingDoc::DOC_TYPE_IP_LABEL:
                $docType = ToquvPricingDoc::DOC_TYPE_IP;
                break;
            case ToquvPricingDoc::DOC_TYPE_MATO_LABEL:
                $docType = ToquvPricingDoc::DOC_TYPE_MATO;
                break;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $docType);

        return $this->render("index/_index_{$this->slug}", [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ToquvPricingDoc model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new ToquvPricingItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        return $this->render("view/_view_{$this->slug}", [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new ToquvPricingDoc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        switch ($this->slug){
            case ToquvPricingDoc::DOC_TYPE_IP_LABEL:
                $docName = "PI";
                $docType = ToquvPricingDoc::DOC_TYPE_IP;
                break;
            case ToquvPricingDoc::DOC_TYPE_MATO_LABEL:
                $docName = "PM";
                $docType = ToquvPricingDoc::DOC_TYPE_MATO;
                break;
        }
        $model = new ToquvPricingDoc();
        $model->reg_date = date('d.m.Y');
        $lastId = $model::find()->select('id')->orderBy(['id'=>SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $model->doc_number =  $docName.$lastId . "/" . date('Y');
        $model->doc_type = $docType;

        if ($model->load(Yii::$app->request->post())&&$model->save()) {
            if(Yii::$app->request->post('doc')){
                $doc = Yii::$app->request->post('doc');
                $model->savePricing($doc);
            }
            Yii::$app->session->setFlash('success', Yii::t('app','Saved Successfully'));
            return $this->render('update', ['model' => $model]);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing ToquvPricingDoc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(Yii::$app->request->post('doc')){
                $doc = Yii::$app->request->post('doc');
                $model->savePricing($doc);
            }
            if(Yii::$app->request->post('remove')){
                $remove = Yii::$app->request->post('remove');
                $model->removePricing($remove);
            }
            Yii::$app->session->setFlash('success', Yii::t('app','Saved Successfully'));
            // return $this->redirect(['view', 'id' => $model->id]);
            return $this->render('update', ['model' => $model]);
        }
        if($model->status!==ToquvPricingDoc::STATUS_SAVED){
            return $this->render('update', [
                'model' => $model,
            ]);
        }else{
            $searchModel = new ToquvPricingItemSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
            return $this->render("view/_view_{$this->slug}", [
                'model' => $this->findModel($id),
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }
    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndFinish($id){

        $model = $this->findModel($id);
        if($model->status !== ToquvPricingDoc::STATUS_SAVED){
            $model->status = ToquvPricingDoc::STATUS_SAVED;
            $model->save();
        }
        return $this->redirect(['view','id' => $id,'slug' => $this->slug]);
    }
    /**
     * Deletes an existing ToquvPricingDoc model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(!empty($model->toquvPricingItems)){
            foreach ($model->toquvPricingItems as $item){
                $item->delete();
            }
        }
        $model->delete();

        return $this->redirect(['index']);
    }
    public function actionAjaxRequest(){
        if(Yii::$app->request->isAjax){
            $response = [];
            $response['status'] = 0;
            $response['message'] = 'Error';
            $data = Yii::$app->request->post();
            $raw = ($data['raw'])?$data['raw']:0;
            $ne = ($data['ne'])?$data['ne']:0;
            $thread = ($data['thread'])?$data['thread']:0;
            $data = Yii::$app->request->post();    
            $name = ($data['name'])?$data['name']:0;
            $id = ($data['id'])?$data['id']:0;
            // switch ($this->slug){
            //     case ToquvPricingDoc::DOC_TYPE_IP_LABEL:
            //         $result = ToquvPricingDoc::getAllIp($ne,$thread,$name,$id,ToquvPricingDoc::DOC_TYPE_IP);
            //         break;
            //     case ToquvPricingDoc::DOC_TYPE_MATO_LABEL:
            //         $type = ($data['type'])?$data['type']:0;
            //         $result = ToquvPricingDoc::getAllIp($ne,$thread,$name,$id,ToquvPricingDoc::DOC_TYPE_IP);
            //         break;
            // }
            $result = ToquvPricingDoc::getAllIp($ne,$thread,$name,$id,$raw,$this->slug); 
            if($result !== 0){
                $response['status'] = 1;
                $response['message'] = 'Success';
                $response['data'] = $result;
            }else{
                $response['message'] = Yii::t("app","Ma'lumotlar topilmadi");
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $response;
        }
        return false;
    }
    /**
     * Finds the ToquvPricingDoc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvPricingDoc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvPricingDoc::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
