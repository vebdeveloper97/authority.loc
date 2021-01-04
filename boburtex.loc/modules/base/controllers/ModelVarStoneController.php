<?php

namespace app\modules\base\controllers;

use app\modules\base\models\ModelVarPrintRelAttach;
use app\modules\base\models\ModelVarStoneRelAttach;
use Yii;
use app\modules\base\models\ModelVarStone;
use app\modules\base\models\ModelVarStoneSearch;
use app\modules\base\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ModelVarStoneController implements the CRUD actions for ModelVarStone model.
 */
class ModelVarStoneController extends BaseController
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
     * Lists all ModelVarStone models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModelVarStoneSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ModelVarStone model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $attachments = ($model->modelVarStoneRelAttaches)?$model->modelVarStoneRelAttaches:[];
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
     * Creates a new ModelVarStone model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ModelVarStone();
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
                            $rel = new ModelVarStoneRelAttach([
                                'model_var_stone_id' => $model->id,
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
                    /*$color_list = $data['ModelVarPrintsColors'];
                    $i = 0;
                    if($color_list&&$saved_image){
                        foreach($color_list as $color){
                            $model_color = new ModelVarPrintsColors([
                                'model_var_stones_id' => $model->id,
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
                    }*/
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
                Yii::info('Not saved model var stones' . $e, 'save');
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
     * Updates an existing ModelVarStone model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $attachments = ($model->modelVarStoneRelAttaches)?$model->modelVarStoneRelAttaches:[];
        if ($model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                if ($model->save()) {
                    ModelVarStoneRelAttach::deleteAll(['model_var_stone_id'=>$model->id]);
                    $image = $data['attachments'];
                    $i = 0;
                    if($image){
                        foreach($image as $item){
                            $rel = new ModelVarStoneRelAttach([
                                'model_var_stone_id' => $model->id,
                                'attachment_id' => $item,
                                'is_main' => ($i == 0) ? 1 : 0
                            ]);
                            $rel->save();
                        }
                    }
                    $response['status'] = 0;
                } else {
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            }
            if ($model->save()) {
                ModelVarStoneRelAttach::deleteAll(['model_var_stone_id'=>$model->id]);
                $image = $data['attachments'];
                $i = 0;
                if($image){
                    foreach($image as $item){
                        $rel = new ModelVarStoneRelAttach([
                            'model_var_stone_id' => $model->id,
                            'attachment_id' => $item,
                            'is_main' => ($i == 0) ? 1 : 0
                        ]);
                        $rel->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'attachments' => $attachments
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'attachments' => $attachments
        ]);
    }

    /**
     * Deletes an existing ModelVarStone model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $attachments = $model->modelVarStoneRelAttaches;
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

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "model-var-stone_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => ModelVarStone::find()->select([
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
     * Finds the ModelVarStone model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModelVarStone the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ModelVarStone::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
