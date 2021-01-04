<?php

namespace app\modules\toquv\controllers;

use app\models\Users;
use app\modules\toquv\models\ToquvMakine;
use Yii;
use app\modules\toquv\models\ToquvMakineUsers;
use app\modules\toquv\models\ToquvMakineUsersSearch;
use app\modules\toquv\controllers\BaseController;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ToquvMakineUsersController implements the CRUD actions for ToquvMakineUsers model.
 */
class ToquvMakineUsersController extends BaseController
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
     * Lists all ToquvMakineUsers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvMakineUsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $makine = ToquvMakine::getMakine();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'makine' => $makine,
        ]);
    }

    /**
     * Displays a single ToquvMakineUsers model.
     * @param integer $toquv_makine_id
     * @param integer $users_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = Users::findOne($id);
        $makine = ToquvMakine::getUsersMakine($id);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model,
                'makine' => $makine
            ]);
        }
        return $this->render('view', [
            'model' => $model,
            'makine' => $makine
        ]);
    }

    /**
     * Updates an existing ToquvMakineUsers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $toquv_makine_id
     * @param integer $users_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = Users::findOne($id);
        $makine = ToquvMakine::getMakine();
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                $is_saved = false;
                ToquvMakineUsers::deleteAll(['users_id'=>$model->id]);
                if (!empty($data['toquv_makine_id'])) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        foreach ($data['toquv_makine_id'] as $item) {
                            $makine_users = new ToquvMakineUsers([
                                'users_id' => $model->id,
                                'toquv_makine_id' => $item
                            ]);
                            if ($makine_users->save()) {
                                $is_saved = true;
                            } else {
                                $is_saved = false;
                                break;
                            }
                        }
                    } catch (\Exception $e) {
                        $error = $e;
                        Yii::info('Not saved users makine' . $e, 'save');
                    }
                    if ($is_saved) {
                        $transaction->commit();
                        $response['status'] = 0;
                    } else {
                        $response['status'] = 1;
                        $response['errors'] = $error ?? [];
                    }
                }else{
                    $response['status'] = 0;
                }
                return $response;
            }
            $transaction = Yii::$app->db->beginTransaction();
            $is_saved = false;
            try {
                ToquvMakineUsers::deleteAll(['users_id'=>$model->id]);
                foreach ($data['toquv_makine_id'] as $item) {
                    $makine_users = new ToquvMakineUsers([
                        'users_id' => $model->id,
                        'toquv_makine_id' => $item
                    ]);
                    if($makine_users->save()){
                        $is_saved = true;
                    }else{
                        $is_saved = false;
                        break;
                    }
                }
            }catch (\Exception $e){
                Yii::info('Not saved users makine' . $e, 'save');
            }
            if ($is_saved) {
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'makine' => $makine
                ]);
            }
        }
        if (Yii::$app->request->isAjax) {
            /*return $this->renderAjax('update', [
                'model' => $model,
                'makine' => $makine
            ]);*/
            $makine = ToquvMakineUsers::find()->select('toquv_makine_id')->where(['users_id'=>$id])->asArray()->all();
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 0;
            $response['makine'] = ArrayHelper::getColumn($makine,'toquv_makine_id');
            return $response;
        }

        return $this->render('update', [
            'model' => $model,
            'makine' => $makine
        ]);
    }
    /**
     * Finds the ToquvMakineUsers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $toquv_makine_id
     * @param integer $users_id
     * @return ToquvMakineUsers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
