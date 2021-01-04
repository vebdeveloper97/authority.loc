<?php

namespace app\modules\toquv\controllers;

use app\modules\toquv\models\KaliteMatoForm;
use app\modules\toquv\models\ToquvInstructionRm;
use app\modules\toquv\models\ToquvMakine;
use app\modules\toquv\models\ToquvMakineProcesses;
use app\modules\toquv\models\ToquvRmOrder;
use Yii;
use app\modules\toquv\models\ToquvKalite;
use app\modules\toquv\models\ToquvKaliteSearch;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ToquvKaliteController implements the CRUD actions for ToquvKalite model.
 */
class ToquvKaliteAksessuarController extends BaseController
{
    /**
     * @return array|string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        if(Yii::$app->request->post()){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $response['message'] = 'error';
            $model = new ToquvKalite();
            if ($model->saveKalite(Yii::$app->request->post('Kalite'))){
                $response['status'] = 0;
                $response['message'] = "Success";
            }
            return $response;
        }
        return $this->render('index', [
            'row' => ToquvMakine::getMakineAks(),
        ]);
    }
    public function actionAjax($id)
    {
        $makine = ToquvMakine::findOne($id);
        $model = $makine->getProccesAksList(true);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('ajax', [
                'model' => $model,
                'makine' => $makine,
                'id' => $id
            ]);
        }
        return $this->render('ajax', [
            'model' => $model,
            'makine' => $makine,
            'id' => $id
        ]);
    }
    public function actionSaveId()
    {
        $response = [];
        $response['message'] = 'Error';
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $data = Yii::$app->request->post();
            $flag = false;
            if($data['id']&&$data['status']) {
                foreach ($data['id'] as $key){
                    $item = ToquvInstructionRm::findOne($key);
                    $item->status = $data['status'];

                    if ($data['status']==2){
                        $item->finished_date= date('Y-m-d H:i:s');

                    }else if($data['status']==3){
                        $item->planed_date = date('Y-m-d H:i:s');
                    }
//                    else if ($data['status']==4){
//                     // agar Omborga chiqqan vaqtini saqlash kerak bo'lsa .. yangi column ochish kerak
//                    }
                    $item->save();
                }
                $response['message'] = 'Success';
                $response['data'] = $data;
            }
        }
        return $response;
    }
    public function actionKalite()
    {
        $searchModel = new ToquvKaliteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,2);
        $sum = ToquvKalite::getTotal($dataProvider->models,'quantity');
        $count = ToquvKalite::getTotal($dataProvider->models,'count');
        return $this->render('kalite', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sum' => $sum,
            'count' => $count,
        ]);
    }

    /**
     * @return string
     */
    public function actionInstructions(){
       $params = Yii::$app->request->queryParams;
       $model = new KaliteMatoForm();
       $instructions = $model->search($params);
        $pages = new Pagination(['totalCount' => count(ToquvKalite::getInstructionsWithKalite()),'pageSize'=>$model->limit]);
       $results = [];
       foreach ($instructions as $item){
           $results[$item['id']]['ins'] = [
                'id' => $item['id'],
                'document_number' => $item['document_number'],
                'musteri' => $item['musteri'],
                'reg_date' => $item['reg_date'],
                'sort' => $item['sort'],
                'matoid' => $item['matoid']
           ];
           $results[$item['id']]['mato'][$item['matoid']] = [
             'mato' => $item['mato'],
             'qty' => $item['qty']
           ];
       }
       return $this->render('instructions', [
             'items' => $results,
             'model' => $model,
                'pages' => $pages
           ]);
    }
    public function actionPlanlama(){
       $params = Yii::$app->request->queryParams;
//       $model = new KaliteMatoForm();
//       $instructions = $model->search($params);
//        $pages = new Pagination(['totalCount' => count(ToquvKalite::getInstructionsWithKalite()),'pageSize'=>$model->limit]);
//       $results = [];
//       foreach ($instructions as $item){
//           $results[$item['id']]['ins'] = [
//                'id' => $item['id'],
//                'document_number' => $item['document_number'],
//                'musteri' => $item['musteri'],
//                'reg_date' => $item['reg_date'],
//                'sort' => $item['sort'],
//                'matoid' => $item['matoid']
//           ];
//           $results[$item['id']]['mato'][$item['matoid']] = [
//             'mato' => $item['mato'],
//             'qty' => $item['qty']
//           ];
//       }
       return $this->render('planlama', [
//             'items' => $results,
//             'model' => $model,
//                'pages' => $pages
           ]);
    }

    /**
     * @param $id
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionInstruction($id){
        $items = ToquvKalite::getToquvKaliteWithDefects($id);
        return $this->render('instruction', ['items' => $items]);
    }

    /**
     * Displays a single ToquvKalite model.
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
     * Creates a new ToquvKalite model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvKalite();
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
     * Updates an existing ToquvKalite model.
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
     * Deletes an existing ToquvKalite model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
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

    public function actionChangeProcess($id,$mak)
    {
        $model = ToquvMakine::getTir($id);
        $makine = ToquvMakine::findOne($mak);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('change-process', [
                'model' => $model,
                'id' => $id,
                'makine' => $makine,
            ]);
        }

        return $this->render('change-process', [
            'model' => $model,
            'id' => $id,
            'makine' => $makine,
        ]);
    }
    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndFinish($id){

        $model = $this->findModel($id);
        if($model->status !== ToquvKalite::STATUS_SAVED){
            $model->status = ToquvKalite::STATUS_SAVED;
            $model->save();
        }
        return $this->redirect(['kalite','id' => $id]);
    }
    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "toquv-kalite_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => ToquvKalite::find()->select([
                'id','toquv_instructions_id','toquv_rm_order_id','toquv_makine_id','user_id','quantity','sort_name_id'
            ])->all(),
            'columns' => [
                'id',
                [
                    'attribute' => 'toquv_instructions_id',
                    'value' => function($model){
                        return $model->toquvInstructions->toquvOrder->document_number;
                    },
                ],
                [
                    'attribute' => 'toquv_rm_order_id',
                    'value' => function($model){
                        return $model->toquvRmOrder->toquvRawMaterials->name;
                    },
                ],
                [
                    'attribute' => 'toquv_makine_id',
                    'value' => function($model){
                        return $model->toquvMakine->name;
                    },
                ],

                [
                    'attribute' => 'user_id',
                    'value' => function ($model) {
                        return $model->user['user_fio'];
                    },
                ],
                [
                    'attribute' => 'quantity',
                ],
                [
                    'attribute' => 'sort_name_id',
                    'value' => function($model){
                        return $model->sortName->name;
                    },
                ],
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
