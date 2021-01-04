<?php

namespace app\modules\toquv\controllers;

use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvDocumentItems;
use app\modules\toquv\models\ToquvItemBalance;
use Yii;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvMatoSkladSearch;
use app\modules\toquv\controllers\BaseController;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ToquvMatoSkladController implements the CRUD actions for ToquvDocuments model.
 */
class ToquvMatoSkladController extends BaseController
{
    public $slug;
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
     * @param $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {

            $slug = ToquvDocuments::DOC_TYPE_INCOMING_MATO_LABEL;
            $flag = false;
            if (!empty($slug)) {
                if (array_key_exists($slug, ToquvDocuments::getDocTypeBySlug())) {
                    $flag = true;
                    $this->slug = $slug;
                }
            }
            if (!$flag) {
                throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
            }
            if (Yii::$app->authManager->getPermission(Yii::$app->controller->id."/".Yii::$app->controller->action->id)) {
                if (!Yii::$app->user->can(Yii::$app->controller->id . "/" . Yii::$app->controller->action->id)) {
                    throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
                }
            }
            return true;
        } else {
            return false;
        }
    }
    /**
     * Lists all ToquvDocuments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvMatoSkladSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ToquvDocuments model.
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
     * Creates a new ToquvDocuments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvDocuments();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ToquvDocuments model.
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
     * Deletes an existing ToquvDocuments model.
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
     * @param $id
     * @return bool|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionSaveAndFinish($id)
    {
        $model = $this->findModel($id);
        if ($model->status !== ToquvItemBalance::STATUS_SAVED) {
            switch ($model->document_type) {
                case 1:
                case 2:
                    $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                    $flagIB = false;
                    if (!empty($TDItems)) {
                        //items loop
                        foreach ($TDItems as $item) {
                            $flagIB = false;
                            $ItemBalanceModel = new ToquvItemBalance();
                            $item['department_id'] = $model->from_department;
                            $item['musteri_id'] = ToquvDocumentItems::getMusteri($item['id']);
                            $lastRec = ToquvItemBalance::getLastRecordMovingMusteri($item);
                            //tekwirish
                            if (!empty($lastRec)) {
                                $attributesTIB['entity_id'] = $item['entity_id'];
                                $attributesTIB['entity_type'] = $item['entity_type'];
                                $attributesTIB['is_own'] = $item['is_own'];
                                $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                $attributesTIB['document_id'] = $model->id;
                                $attributesTIB['inventory'] = $lastRec['inventory'] + $item['quantity'];
                                $attributesTIB['lot'] = $item['lot'];
                                $attributesTIB['count'] = $item['quantity'];
                                $attributesTIB['department_id'] = $model->from_department;
                                $attributesTIB['to_department'] = $model->to_department;
                                $attributesTIB['document_type'] = $model->document_type;
                                $attributesTIB['musteri_id'] = $item['musteri_id'];
                                $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                $ItemBalanceModel->setAttributes($attributesTIB);
                                if ($ItemBalanceModel->save()) {
                                    $flagIB = true;
                                }
                            }else{
                                $attributesTIB['entity_id'] = $item['entity_id'];
                                $attributesTIB['entity_type'] = $item['entity_type'];
                                $attributesTIB['is_own'] = $item['is_own'];
                                $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                $attributesTIB['document_id'] = $model->id;
                                $attributesTIB['inventory'] = $item['quantity'];
                                $attributesTIB['lot'] = $item['lot'];
                                $attributesTIB['count'] = $item['quantity'];
                                $attributesTIB['department_id'] = $model->from_department;
                                $attributesTIB['to_department'] = $model->to_department;
                                $attributesTIB['document_type'] = $model->document_type;
                                $attributesTIB['musteri_id'] = $item['musteri_id'];
                                $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                $ItemBalanceModel->setAttributes($attributesTIB);
                                if ($ItemBalanceModel->save()) {
                                    $flagIB = true;
                                }
                            }

                        }
                    }
                    if ($flagIB) {
                        $model->updateCounters(['status' => 2]);
                    }
                    break;
            }
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "toquv-mato-sklad_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => ToquvDocuments::find()->select([
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
     * Finds the ToquvDocuments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvDocuments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvDocuments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
