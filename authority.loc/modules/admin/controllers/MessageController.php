<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Attachments;
use app\modules\admin\models\MessageAttachments;
use app\modules\admin\models\MessageAttachmentsUz;
use Yii;
use app\modules\admin\models\MessageUz;
use app\modules\admin\models\MessageUzSearch;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * MessageController implements the CRUD actions for MessageUz model.
 */
class MessageController extends Controller
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

    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

    /**
     * Lists all MessageUz models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessageUzSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MessageUz model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id)?$this->findModel($id):false;
        if($model){
            return $this->render('view', [
                'model' => $model,
            ]);
        }
        else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Not found data'));
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Creates a new MessageUz model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MessageUz();
        if ($model->load(Yii::$app->request->post())) {
            $saved = MessageUz::getDataSave($model);

            if($saved){
                Yii::$app->session->setFlash('success', "Successfully saved");
                return $this->redirect(['view', 'id' => $saved]);
            }
            else{
                Yii::$app->session->setFlash('error', Yii::t("app","Not saved"));
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MessageUz model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model){
            $model->date = date('d.m.Y', strtotime($model->date));
            $images = MessageAttachmentsUz::find()->where(['message_id' => $model->id])->all();
            if($images){
                $showImages = MessageAttachmentsUz::getImages($images);
            }
            if ($model->load(Yii::$app->request->post())){
                $saved = MessageUz::getDataSave($model,$id);

                if($saved){
                    Yii::$app->session->setFlash('success', "{$id} - raqamli xabar yangilandi!");
                    return $this->redirect(['view', 'id' => $saved]);
                }
                else{
                    Yii::$app->session->setFlash('error', Yii::t("app","Not saved"));
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }

            return $this->render('update', [
                'model' => $model,
                'showImages' => $showImages,
            ]);
        }
        else{
            Yii::$app->session->setFlash('error', 'No data found');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Deletes an existing MessageUz model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $model = MessageAttachmentsUz::find()->where(['message_id' => $id])->all();
        if($model){
            foreach ($model as $item) {
                $attachments = $item->attachments;
                if($attachments){
                    $item->delete();
                    $attachments->delete();
                }
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the MessageUz model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MessageUz the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MessageUz::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
