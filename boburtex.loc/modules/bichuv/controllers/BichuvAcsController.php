<?php

namespace app\modules\bichuv\controllers;

use app\modules\bichuv\models\BichuvAcsAttachment;
use app\modules\bichuv\models\BichuvAcsProperties;
use app\modules\bichuv\models\BichuvAcsProperty;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\SpareItem;
use app\modules\bichuv\models\SpareItemProperty;
use app\modules\bichuv\models\SpareItemSearch;
use app\modules\toquv\models\ToquvDocumentItems;
use app\modules\toquv\models\Unit;
use moonland\phpexcel\Excel;
use Yii;
use app\modules\bichuv\models\BichuvAcs;
use app\modules\bichuv\models\BichuvAcsSearch;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * BichuvAcsController implements the CRUD actions for BichuvAcs model.
 */
class BichuvAcsController extends BaseController
{
    /**
     * Lists all BichuvAcs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BichuvAcsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new BichuvAcs();
        $property = new BichuvAcsProperties();

        $request = Yii::$app->request;
        if($request->isPost && $model->load($request->post())){
            $data = $request->post();
            $dataProvider = $searchModel->search(null,$data);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'property' => $property,
        ]);
    }

    /**
     * Displays a single BichuvAcs model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BichuvAcs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        if(!$request->isAjax)
            return $this->redirect('index');

        $model = new BichuvAcs();
        $bichuvAcsPro = [new BichuvAcsProperties()];

        if($model->load($request->post())){
            $data = $request->post();
            $result = $model->acsSave($data['BichuvAcsProperties']);
            if($result){
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                return $this->redirect(['index']);
            }
            else{
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                return $this->redirect($request->referrer);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'bichuvAcsPro' => $bichuvAcsPro,
        ]);
    }

    /**
     * Updates an existing BichuvAcs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
//        if(!Yii::$app->request->isAjax)
//            return $this->redirect('index');

        $model = $this->findModel($id);

        $modelAcsProperty = BichuvAcsProperties::find()->where(['bichuv_acs_id' => $model->id])->all();
        if(empty($modelAcsProperty)){
           $modelAcsProperty = [new BichuvAcsProperties()];
        }
        if ($model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            $result = $model->acsUpdate($data['BichuvAcsProperties']);
            if($result){
                Yii::$app->session->setFlash('success', Yii::t('app', 'Update : {name}'));
                return $this->redirect(['index']);
            }else{
                Yii::$app->session->setFlash('error', Yii::t('error'));
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'bichuvAcsPro' => $modelAcsProperty,
        ]);
    }
    public function actionImage($id)
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $img = Yii::$app->request->post('imageSnapshot');
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            if($res = $model->uploadBase64($model->id, $img)){
                $response['status'] = $res;
            }else{
                $response['status'] = 0;
                $response['errors'] = $model->getErrors();
            }
            return $response;
        }else {

            return $this->renderAjax('image', [
                'model' => $model,
            ]);
        }
    }
    public function actionCarousel($id)
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');
        $model = $this->findModel($id);
        $image = $model->bichuvAcsAttachments;
        if (!$image) {return false;}
        return $this->renderAjax('carousel', [
            'model' => $model,
            'image' => $image
        ]);
    }
    public function actionDeleteImage($id)
    {
        $model = BichuvAcsAttachment::findOne($id);
        $response = [];
        $response['status'] = 0;
        if($model->deleteOne()){
            $response['status'] = 1;
        }
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }
        return $this->redirect('index');
    }

    public function actionCopy($id)
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');
        $original = $this->findModel($id);

        $bichuvAcsPro = [new BichuvAcsProperties()];
        $model = new BichuvAcs();
        $model->attributes = $original->attributes;
        $model->sku = null;
        $model->barcode = null;

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            if($model->save()){
                $response['status'] = 0;
                return $response;
            }else{
                $response['status'] = 1;
                $response['errors'] = $model->getErrors();
                return $response;
            }
        }

        return $this->renderAjax('copy', [
            'model' => $model,
            'bichuvAcsPro' => $bichuvAcsPro,
        ]);
    }

    /**
     * Deletes an existing BichuvAcs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if($model = $this->findModel($id)){
            $name = $model->name;
            $model->delete();
            Yii::$app->session->setFlash('success', Yii::t('app', "Delete : {$name}"));
            return $this->redirect(['index']);
        }else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Malumotni o\'chirib bo\'lmadi'));
            return $this->redirect(['index']);
        }

        exit();
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "aksesuar_royxati".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        Excel::export([
            'models' => BichuvAcs::find()->select([
                'sku',
                'name',
                'property_id',
                'unit_id',
                'barcode',
                'add_info'
                ])->all(),
            'columns' => [
                'sku',
                'name',
                'propertyName',
                'unitName',
                'barcode',
                'add_info:ntext',
            ],
            'headers' => [
                'sku' => 'Artikul / Kodi',
                'property_id' => 'Property ID',
                'barcode' => 'Barkod',
                'unit_id' => 'Unit ID',
                'add_info' => 'Izoh'
            ],
            'autoSize' => true,
        ]);

    }
    /**
     * Finds the BichuvAcs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BichuvAcs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BichuvAcs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionCreateNewItem()
    {
        $this->enableCsrfValidation = false;
        $name = trim(Yii::$app->request->post('name'));
        $getModel = trim(Yii::$app->request->post('model'));
        $model = null;

        if($getModel == 'bichuv-acs-property') {
            $model = new BichuvAcsProperty();
            $model->name = $name;
        }

        if($getModel == 'unit') {
            $model = new Unit();
            $model->name = $name;
        }

        if($model->save()){
            return $model->id;
        }else{
            return "fail";
        }
    }

    public function actionBarcodeGenerate($id)
    {
        $model  = BichuvAcs::find()->where(['id' => $id])->one();
        $this->view->title = Yii::t('app','Generate Barcode');
        if(Yii::$app->request->isPost){
            return $this->render('barcode-generate',[
                'model' => $model,
                'quantity' => $_POST['BichuvAcs']['barcode_quantity']
            ]);
        }
        return $this->render('barcode-generate',[
            'model' => $model,
            'quantity' => 1
        ]);
    }

    public function actionAjaxBarcodeAcs()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response['status'] = 0;
        $barcode = $request->get('barcode');
        $slug = $request->get('slug');
        $department = $request->get('department');
        $model = new BichuvDoc();
        $getAcs = $model->getAcs($barcode, $slug, $department);
        Yii::debug($getAcs,'data_acs');
        if($getAcs){
            $response['status'] = 1;
            $response['results'] = $getAcs;
            return $response;
        }
        else{
            return $response;
        }
    }

    /** ajax save */
    public function actionDataSave()
    {
        $request = Yii::$app->request;
        $model = new BichuvAcs();
        $bichuvAcsPro = [new BichuvAcsProperties()];
        $data = $request->post();

        if (Yii::$app->request->isAjax) {
            $saved = false;
            if ($model->load($request->post()) && $saved = $model->acsSave($data['BichuvAcsProperties'])) {
                Yii::$app->response->format = 'json';
                if($saved){
                    $result = [
                        'status' => 1,
                        'success' => true,
                        'selected_id' => $model->id,
                    ];
                    $result['title'] = $model->sku . ' ' . $model->name;
                    return $result;
                }
                else{
                    $result = [
                        'status' => 0,
                        'success' => false,
                    ];
                    return $result;
                }
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'bichuvAcsPro' => $bichuvAcsPro,
        ]);
    }
}
