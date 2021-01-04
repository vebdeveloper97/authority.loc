<?php

namespace app\modules\toquv\controllers;

use Yii;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvDepartmentsSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ToquvDepartmentsController
 * @package app\modules\toquv\controllers
 */

class ToquvDepartmentsController extends BaseController
{
    /**
     * Lists all ToquvDepartments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvDepartmentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ToquvDepartments model.
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
     * @return array|string|Response
     */
    public function actionCreate()
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');
        $model = new ToquvDepartments();
        $model->created_by = Yii::$app->user->id;
        $tdDataAsArray = ToquvDepartments::find()->where(['status' => ToquvDepartments::STATUS_ACTIVE])->asArray()->all();
        $model->cp['parents'] = ArrayHelper::map($tdDataAsArray, 'id','name');

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
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');
        $model = $this->findModel($id);

        $data = Yii::$app->request->post();
        $tdDataAsArray = ToquvDepartments::find()
            ->andWhere(['status' => ToquvDepartments::STATUS_ACTIVE])
            ->andWhere(['<>','id', $id])
            ->asArray()->all();
        $model->cp['parents'] = ArrayHelper::map($tdDataAsArray, 'id','name');

        if(Yii::$app->request->isPost){
            $data['ToquvDepartments']['created_by'] = Yii::$app->user->id;
            if ($model->load($data) && $model->save()) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['status'=>0];
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if($this->findModel($id)->delete()){
            echo "success";
        }else{
            echo "fail";
        }

        exit();
    }

    /**
     * @param $id
     * @return ToquvDepartments|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = ToquvDepartments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    /**
     * @return array
     */
    public function actionChangeStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('pk');
        $status = Yii::$app->request->post('value');
        $model = ToquvDepartments::find()->where(['id' => $id])->one();

        $model->status = (int)$status;
        $result = ['success'=>false];
        if($model->save()) {
            $text = $model->getStatusList($model->status);
            $btnClass = $model->status == 1 ? 'btn btn-xs btn-success' : 'btn btn-xs btn-danger';
            $button = Html::button($text, ['class' => $btnClass]);
            $result = ['success' => true, 'btn' => $button, 'id' => $model->id];
        }
        return $result;
    }

    public function actionGetMusteriAddress()
    {
        $department_id = Yii::$app->request->post('id');
        $musteri = ToquvDepartments::getMusteriAddressByParentId($department_id);
        echo json_encode($musteri);
        return die;
    }
}
