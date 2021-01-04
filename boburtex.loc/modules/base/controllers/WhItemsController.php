<?php

namespace app\modules\base\controllers;

use app\modules\base\models\WhItemCategory;
use Yii;
use app\modules\base\models\WhItems;
use app\modules\base\models\WhItemsSearch;
use app\modules\base\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WhItemsController implements the CRUD actions for WhItems model.
 */
class WhItemsController extends BaseController
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
     * Lists all WhItems models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WhItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WhItems model.
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
     * Creates a new WhItems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WhItems();
        $model->country_id = 1;

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->code) {
                $model->code = substr( str_replace(["'", " ", '"', "/", "\\"], '', $model->name), 0, 4)
                    . "_" . Yii::$app->security->generateRandomString(4);
            }
            if (!$model->barcode) {
                $model->code = substr( str_replace(["'", " ", '"', "/", "\\"], '', $model->name), 0, 2)
                     . "-" . rand(1000, 9999) . Yii::$app->security->generateRandomString(2);
            }

            $model->save();
            return $this->redirect('index');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing WhItems model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing WhItems model.
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

    /**
     * @return array
     */
    public function actionCat() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $id = $parents[0];
                $out = WhItemCategory::getList($id,true);
                return ['output'=>$out, 'selected'=>''];
            }
        }
        return ['output'=>'', 'selected'=>''];
    }

    public function actionExportExcel(){
        $searchModel = new WhItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        header('Content-Type: application/vnd.ms-excel');
        $filename = "mahsulot_".date("d.m.Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => $dataProvider->getModels(),
            'columns' => [
                'code',
                'name',
                [
                    'attribute' => 'wh_category_id',
                    'value' => function($model){
                        return $model->category->name;
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'wh_item_country_id',
                    'value' => function($model){
                        return $model->country->name;
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'unit_id',
                    'value' => function($model){
                        return $model->unit->name;
                    },
                    'format' => 'html'
                ],
                'add_info'
            ],
            'headers' => [
                'code' => Yii::t('app', 'Code'),
                'name' => Yii::t('app', 'Name'),
                'wh_item_category.name' => Yii::t('app', 'Category ID'),
                'wh_item_country.name' => Yii::t('app', 'Country ID'),
                'unit.name' =>Yii::t('app','Unit ID'),
                'add_info' => Yii::t('app','Add Info'),
            ],
            'autoSize' => true,
        ]);
    }

    /**
     * Finds the WhItems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WhItems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WhItems::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
