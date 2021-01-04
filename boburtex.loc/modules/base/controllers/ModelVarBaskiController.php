<?php

namespace app\modules\base\controllers;

use app\modules\base\models\ModelsVariationColors;
use app\modules\base\models\ModelVarBaskiColors;
use app\modules\base\models\ModelVarBaskiRelAttach;
use Yii;
use app\modules\base\models\ModelVarBaski;
use app\modules\base\models\ModelVarBaskiSearch;
use app\modules\base\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ModelVarBaskiController implements the CRUD actions for ModelVarBaski model.
 */
class ModelVarBaskiController extends BaseController
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
     * Lists all ModelVarBaski models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModelVarBaskiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ModelVarBaski model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $attachments = ($model->modelVarBaskiRelAttaches)?$model->modelVarBaskiRelAttaches:[];
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model,
                'attachments' => $attachments
            ]);
        }
        return $this->render('view', [
            'model' => $model,
            'attachments' => $attachments
        ]);
    }

    /**
     * Creates a new ModelVarBaski model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ModelVarBaski();
        $colors = [new ModelVarBaskiColors()];
        $model->code = "SP-" . strtoupper(Yii::$app->security->generateRandomString(4)) . rand(0,9999);
        if ($model->load(Yii::$app->request->post())) {
            $saved = false;
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    $saved = true;
                    $saved_image = true;
                    $image = $data['attachments'];
                    $i = 0;
                    if($image){
                        $saved_image = false;
                        foreach($image as $item){
                            $rel = new ModelVarBaskiRelAttach([
                                'model_var_baski_id' => $model->id,
                                'attachment_id' => $item,
                                'is_main' => ($i == 0) ? 1 : 0
                            ]);
                            $i++;
                            if($rel->save()){
                                $saved = true;
                                $saved_image = true;
                            }else{
                                $saved = false;
                                $saved_image = false;
                                break;
                            }
                        }
                    }
                    $color_list = $data['ModelVarBaskiColors'];
                    $i = 0;
                    if($color_list&&$saved_image){
                        foreach($color_list as $color){
                            $model_color = new ModelVarBaskiColors([
                                'model_var_baski_id' => $model->id,
                                'color_pantone_id' => $color['color_pantone_id'],
                                'add_info' => $color['add_info'],
                                'is_main' => ($i == 0) ? 1 : 0
                            ]);
                            $i++;
                            if($model_color->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                }else{
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                $response = [];
                if ($saved) {
                    $response['status'] = 0;
                    $transaction->commit();
                    $response['model'] = $model;
                    $response['message'] = Yii::t('app', 'Saved Successfully');
                    $response['model']['image'] = $model->imageOne;
                    $response['model']['brend_id'] = $model->brend['name'];
                    $response['model']['musteri_id'] = $model->musteri['name'];
                } else {
                    $response['status'] = 1;
                    $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
                    $response['errors'] = $model->getErrors();
                    $transaction->rollBack();
                }
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return $response;
                }
                if($saved){
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }catch (\Exception $e){
                Yii::info('Not saved model var baski' . $e, 'save');
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
                'colors' => $colors,
            ]);
        }
        return $this->render('create', [
            'model' => $model,
            'colors' => $colors
        ]);
    }

    /**
     * Updates an existing ModelVarBaski model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $attachments = ($model->modelVarBaskiRelAttaches)?$model->modelVarBaskiRelAttaches:[];
        $colors = ($model->modelVarBaskiColors)?$model->modelVarBaskiColors:[new ModelVarBaskiColors()];
        if ($model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            $response = [];
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $response = [];
                if ($model->save()) {
                    ModelVarBaskiRelAttach::deleteAll(['model_var_baski_id'=>$model->id]);
                    $saved_image = true;
                    $image = $data['attachments'];
                    $i = 0;
                    if($image){
                        $saved_image = false;
                        foreach($image as $item){
                            $rel = new ModelVarBaskiRelAttach([
                                'model_var_baski_id' => $model->id,
                                'attachment_id' => $item,
                                'is_main' => ($i == 0) ? 1 : 0
                            ]);
                            if($rel->save()){
                                $saved = true;
                                $saved_image = true;
                            }else{
                                $saved = false;
                                $saved_image = false;
                                break;
                            }
                        }
                    }
                    ModelVarBaskiColors::deleteAll(['model_var_baski_id'=>$model->id]);
                    $color_list = $data['ModelVarBaskiColors'];
                    $i = 0;
                    if($color_list&&$saved_image){
                        foreach($color_list as $color){
                            $model_color = new ModelVarBaskiColors([
                                'model_var_baski_id' => $model->id,
                                'color_pantone_id' => $color['color_pantone_id'],
                                'add_info' => $color['add_info'],
                                'is_main' => ($i == 0) ? 1 : 0
                            ]);
                            if($model_color->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                    $response['status'] = 0;
                } else{
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                if ($saved) {
                    $response['status'] = 0;
                    $transaction->commit();
                } else {
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                    $transaction->rollBack();
                }
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return $response;
                }
                if($saved){
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }catch (\Exception $e){
                Yii::info('Not saved model var baski' . $e, 'save');
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'attachments' => $attachments,
                'colors' => $colors,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'attachments' => $attachments,
            'colors' => $colors,
        ]);
    }

    /**
     * Deletes an existing ModelVarBaski model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $attachments = $model->modelVarBaskiRelAttaches;
        if($attachments){
            foreach($attachments as $attachment){
                $attachment->deleteOne();
            }
        }
        if(Yii::$app->request->isAjax){
            if($model->delete()){
                echo "success";
            }else{
                echo "fail";
            }
            exit();
        }
        $model->delete();
        return $this->redirect(['index']);
    }
    public function actionAjaxRequest($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['results'] = [];
        if (!empty($q)) {
            $searchModel = new ModelsVariationColors();
            $res = $searchModel->getColorList($q);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $name = "<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>"
                        .$item['tname'] . "</span></span> ".$item['ccode'] . " - <b>"
                        . $item['cname'] . "</b>";
                    array_push($response['results'], [
                        'id' => $item['id'],
                        'text' => $name,
                    ]);
                }
            } else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                ];
            }
        }
        return $response;
    }
    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "model-var-baski_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => ModelVarBaski::find()->select([
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
     * Finds the ModelVarBaski model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModelVarBaski the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ModelVarBaski::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
