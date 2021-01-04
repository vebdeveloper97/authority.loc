<?php

namespace app\modules\base\controllers;

use Yii;
use app\modules\base\models\ModelTypes;
use app\modules\base\models\ModelTypesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ModelTypesController implements the CRUD actions for ModelTypes model.
 */
class ModelTypesController extends Controller
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
     * Lists all ModelTypes models.
     * @param $level
     * @return mixed
     */

    public function actionIndex()
    {
        $treeViewQuery = ModelTypes::getQueryForTreeView();

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'treeViewQuery' => $treeViewQuery,
            ]);
        }
        return $this->render('index', [
            'treeViewQuery' => $treeViewQuery,
        ]);
    }

    /**
     * Displays a single ModelTypes model.
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
     * Creates a new ModelTypes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $level =  Yii::$app->request->get('level',1);
        $model = new ModelTypes();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index','level' => $level]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ModelTypes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $level =  Yii::$app->request->get('level',1);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index','level' => $level]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ModelTypes model.
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
     * Finds the ModelTypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModelTypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ModelTypes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
