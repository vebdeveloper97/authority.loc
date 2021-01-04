<?php

namespace app\modules\base\controllers;

use app\modules\base\models\ModelsVariations;
use app\modules\base\models\NewModelBarcodeForm;
use app\modules\base\models\Size;
use app\modules\base\models\SizeColRelSize;
use Yii;
use app\modules\base\models\Goods;
use app\modules\base\models\BarcodeSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BarcodeController implements the CRUD actions for Goods model.
 */
class BarcodeController extends BaseController
{

    /**
     * Lists all Goods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BarcodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAddNewModelBarcode()
    {
        if(Yii::$app->request->isPost){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $data = Yii::$app->request->post();
            if(!empty($data)){
                try{
                    $transaction = Yii::$app->db->beginTransaction();
                    $saved = false;
                    $color = $data['NewModelBarcodeForm']['color'];
                    $modelId = $data['NewModelBarcodeForm']['model'];
                    $modelVarId = $data['NewModelBarcodeForm']['model_var'];
                    $sizeColId = $data['NewModelBarcodeForm']['size'];
                    $modelNo = $data['NewModelBarcodeForm']['article'];
                    $modelName = $data['NewModelBarcodeForm']['name'];
                    $code = $data['NewModelBarcodeForm']['code'];
                    $sizeItems = SizeColRelSize::find()->with(['size'])->where(['sc_id' => $sizeColId])->asArray()->all();
                    if(!empty($sizeItems)){
                        foreach ($sizeItems as $item) {
                            $check = Goods::findOne([
                                'model_id' => $modelId,
                                'size_type' => $item['size']['size_type_id'],
                                'size' => $item['size_id'],
                                'color' => $color
                            ]);
                            $all = Goods::find()->orderBy(['id' => SORT_DESC]);
                            $count = $all->count();
                            $barcode = ($count == 0) ? 100000000 : $all->one()->barcode + 1;
                            if (empty($check)) {
                                $dataGoods = [
                                    'barcode' => $barcode,
                                    'is_inside' => Goods::TYPE_MODEL_INSIDE,
                                    'color' => $color,
                                    'model_no' => $modelNo,
                                    'model_id' => $modelId,
                                    'size_type' => $item['size']['size_type_id'],
                                    'size' => $item['size_id'],
                                    'name' => $modelName,
                                    'model_var' => $modelVarId,
                                    'category' => null,
                                    'sub_category' => null,
                                    'model_type' => null,
                                    'season' => null
                                ];
                                $goods = new Goods($dataGoods);
                                if ($goods->save()) {
                                    $saved = true;
                                } else {
                                    $saved = false;
                                    break;
                                }
                            }
                            else{
                                $saved = true;
//                                Yii::$app->session->setFlash('success', Yii::t('app','Ushbu {model} modelning {color} uchun barcode oldin yaratilgan', ['model' => $modelNo,'color' => $code]));
//                                return $this->redirect('index');
                            }
                        }
                    }
                    if($saved){
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app','Muvaffaqiyatli bajarildi'));
                        return $this->redirect('index');
                    }
                }catch (\Exception $e){

                }
            }
        }
        $model = new NewModelBarcodeForm();
        return $this->renderAjax('new-model-form', ['model' => $model]);
    }

    public function actionGetModelVarsViaAjax(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $modelId = Yii::$app->request->get('id');
        $response = [];
        $response['status'] = false;
        if(!empty($modelId)){
            $modelVars = ModelsVariations::find()
                ->select([
                    'color_pantone.name_ru as name',
                    'color_pantone.code',
                    'models_variations.id',
                    'color_pantone.id as colorId',
                    'models_list.name as modelName',
                    'models_list.article'
                    ])
                ->leftJoin('color_pantone','color_pantone.id = models_variations.color_pantone_id')
                ->leftJoin('models_list','models_variations.model_list_id = models_list.id')
                ->andFilterWhere(['model_list_id' => $modelId])
                ->andWhere(['not', ['color_pantone.code' => null]])
                ->asArray()->all();
            if(!empty($modelVars)){
                $response['status'] = true;
                $response['items'] = $modelVars;
            }
        }
        return $response;
    }

    /**
     * Displays a single Goods model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Goods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Goods();
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                if ($model->save()) {
                    $response['status'] = 0;
                } else {
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Goods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                if ($model->save()) {
                    $response['status'] = 0;
                } else {
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if(Yii::$app->request->isAjax){
            if($this->findModel($id)->delete()){
                echo "success";
            }else{
                echo "fail";
            }
            exit();
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "barcode_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => Goods::find()->select([
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
     * Finds the Goods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionBarcodeGenerate($id, $barcode)
    {
        $model  = Goods::find()->where(['id' => $id])->one();
        $lang = 'ru';
        if(Yii::$app->request->isPost){
            $lang = Yii::$app->request->post('lang','en');
        }
        $this->view->title = Yii::t('app','Generate Barcode');
        return $this->render('barcode-generate',[
            'model' => $model,
            'barcode' => $barcode,
            'lang' => $lang
        ]);
    }
    public function actionBarcodeCheck()
    {
        if (Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();
            $id = $data['id'];
            $barcode = $data['barcode'];
            $fieldNumber = $data['field'];
            $desc = $data['desc'];
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $model = Goods::find()->where(['barcode'=>$barcode])->orWhere(['barcode1'=>$barcode])->orWhere(['barcode2'=>$barcode])->one();
            if ($model){
                $response['status'] = 0;
                $response['message'] = Yii::t('app', 'Bu barkod mavjud');
                $response['model'] = $model;
            }else{
                $goods = Goods::findOne($id);
                if($goods) {
                    $goods->{'barcode'.$fieldNumber} = $barcode;
                    $goods->{'desc'.((int)$fieldNumber+1)} = $desc;
                    $goods->save();
                    $response['message'] = Yii::t('app', 'Saved Successfully');
                }
            }
            return$response;
        }
    }
}
