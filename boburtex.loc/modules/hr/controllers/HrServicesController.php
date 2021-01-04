<?php

namespace app\modules\hr\controllers;

use Yii;
use app\modules\hr\models\HrServices;
use app\modules\hr\models\HrServicesSearch;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HrServicesController implements the CRUD actions for HrServices model.
 */
class HrServicesController extends Controller
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
            $slug = Yii::$app->request->get('slug');
            $flag = false;

            if (!empty($slug)) {
                if (array_key_exists($slug, HrServices::getServiceTypeBySlug())) {
                    $flag = true;
                    $this->slug = $slug;
                }
            }
            if (!$flag) {
                throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
            }
           if (Yii::$app->authManager->getPermission(Yii::$app->controller->id . "/" . Yii::$app->controller->action->id)) {
                if (!Yii::$app->user->can(Yii::$app->controller->id . "/" . $this->slug . "/" . Yii::$app->controller->action->id)) {
                    throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
                }
            }
            return true;
        } else {
            return false;
        }
    }
    /**
     * Lists all HrServices models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HrServicesSearch();
        $serviceType = "";
        switch ($this->slug) {
            case HrServices::SERVICE_TYPE_RAGBAT_LABEL:
                $serviceType = HrServices::SERVICE_TYPE_RAGBAT;
                break;
            case HrServices::SERVICE_TYPE_JARIMA_LABEL:
                $serviceType = HrServices::SERVICE_TYPE_JARIMA;
                break;
            case HrServices::SERVICE_TYPE_OGOHLANTIRISH_LABEL:
                $serviceType = HrServices::SERVICE_TYPE_OGOHLANTIRISH;
                break;
            case HrServices::SERVICE_TYPE_XIZMAT_SAFARI_LABEL:
                $serviceType = HrServices::SERVICE_TYPE_XIZMAT_SAFARI;
                break;
            case HrServices::SERVICE_TYPE_MALAKA_OSHIRISH_LABEL:
                $serviceType = HrServices::SERVICE_TYPE_MALAKA_OSHIRISH;
                break;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $serviceType);

        return $this->render("index/_index_{$this->slug}",
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Displays a single HrServices model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render("view/_view_{$this->slug}", [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new HrServices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HrServices();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                $model->reg_date = date('Y-m-d H:i:s');
                // TODO form tekshirib o'zgartirishim kerak update bilan birga
                if(empty($model->hr_country_id) && empty($model->region_id)){
                    $model->region_type = '';
                    $model->district_id = '';
                } elseif (empty($model->region_id)){
                    $model->district_id = '';
                }

                if($model->save()){
                   $saved = true;
                }
                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success','Saqlandi');
                    return $this->redirect(['view', 'id' => $model->id, 'slug' => $this->slug]);
                }else{
                    Yii::$app->session->setFlash('error','Xatolik');
                    $transaction->rollBack();
                }
            }catch(\Exception $e){
                Yii::info('Not saved'.$e,'save');
                $transaction->rollBack();
            }

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing HrServices model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                $model->reg_date = $model->oldAttributes['reg_date'];

                if(empty($model->hr_country_id) && empty($model->region_id)){
                    $model->region_type = '';
                    $model->district_id = '';
                } elseif (empty($model->region_id)){
                    $model->district_id = '';
                }

                if($model->save()){
                    $saved = true;
                }
                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success','Saqlandi');
                    return $this->redirect(['view', 'id' => $model->id, 'slug' => $this->slug]);
                }else{
                    Yii::$app->session->setFlash('error','Xatolik');
                    $transaction->rollBack();
                }
            }catch(\Exception $e){
                Yii::info('Not saved'.$e,'save');
                $transaction->rollBack();
            }

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HrServices model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index','slug' => $this->slug]);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "hr-services_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => HrServices::find()->select([
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
     * Finds the HrServices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HrServices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HrServices::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
