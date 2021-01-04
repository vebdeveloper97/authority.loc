<?php

namespace app\modules\base\controllers;

use app\modules\base\models\BaseMethodSeam;
use app\modules\base\models\BaseMethodSizeItems;
use app\modules\base\models\BaseMethodSizeItemsChilds;
use app\modules\base\models\BaseModel;
use app\modules\base\models\ModelOrders;
use setasign\Fpdi\PdfParser\Filter\Flate;
use Yii;
use app\modules\base\models\BaseMethod;
use app\modules\base\models\BaseMethodSearch;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * BaseMethodController implements the CRUD actions for BaseMethod model.
 */
class BaseMethodController extends Controller
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
     * Lists all BaseMethod models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BaseMethodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BaseMethod model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $modelItems = $model->baseMethodSizeItems;
        $modelItemsChild = [];
        if($modelItems){
            foreach ($modelItems as $modelItem) {
                if($modelItem->baseMethodSizeItemsChilds){
                    $modelItemsChild[] = $modelItem->baseMethodSizeItemsChilds;
                }
            }
        }
        else{
            $modelItems = [new BaseMethodSizeItems()];
            $modelItemsChild = [[new BaseMethodSizeItemsChilds()]];
        }
        return $this->render('view', [
            'model' => $model,
            'modelItems' => $modelItems,
            'modelItemsChild' => $modelItemsChild,
        ]);
    }

    /**
     * Creates a new BaseMethod model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $baseMethodSeam = new BaseMethodSeam();
        $model = new BaseMethod();
        $lastId = BaseMethod::find()->select('id')->orderBy(['id'=>SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $model->doc_number = 'BM-'.$lastId.'/'.date('m.d.Y');
        $modelItems = [new BaseMethodSizeItems()];
        $modelItemsChilds = [[new BaseMethodSizeItemsChilds()]];

        if ($model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                if($model->save()){
                    $saved = true;
                }
                $MethodItemsSize = $data['BaseMethodSizeItems'];

                /** Base Method Size Items save*/
                if(!empty($MethodItemsSize)){
                    foreach ($MethodItemsSize as $k => $item) {
                        if(!empty($item['size_id'])){
                            /** begin O'lchamlarni aniqlash uchun ishlatiladi */
                            $isSize = BaseMethodSizeItems::findOne([
                                'size_id' => $item['size_id'],
                                'base_method_id' => $model->id,
                            ]);
                            if($isSize){
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Siz oldin bundey o\'chamni saqlagansiz!'));
                                Yii::info('error message Bunday o\'lcham mavjud!');
                                $saved = false;
                                break;
                            }
                            /** end O'lchamlarni aniqlash uchun ishlatiladi */
                            $itemsSize = new BaseMethodSizeItems();
                            $itemsSize->setAttributes([
                                'size_id' => $item['size_id'],
                                'base_method_id' => $model->id,
                            ]);
                            if($itemsSize->save() && $saved){
                                $saved = true;
                                /** BaseMethodSizeItemsChilds save */
                                $MethodItemsSizeChilds = $data['BaseMethodSizeItemsChilds'][$k];
                                if(isset($MethodItemsSizeChilds) && !empty($MethodItemsSizeChilds)){
                                    foreach ($MethodItemsSizeChilds as $methodItemsSizeChild) {
                                        $itemsSizeChilds = new BaseMethodSizeItemsChilds();
                                        $itemsSizeChilds->setAttributes([
                                            'base_method_seam_id' => $methodItemsSizeChild['base_method_seam_id'],
                                            'time' => $methodItemsSizeChild['time'],
                                            'base_method_size_items_id' => $itemsSize->id,
                                        ]);
                                        if($itemsSizeChilds->save()){
                                            $saved = true;
                                            unset($itemsSizeChilds);
                                        }
                                        else{
                                            $saved = false;
                                            Yii::info('error message BaseMethodItemsSizeChilds Saqlanmadi', 'save');
                                            break 2;
                                        }
                                    }
                                }
                                /** / BaseMethodSizeItemsChilds save Yakunlandi */
                                unset($itemsSize);
                            }
                            else{
                                $saved = false;
                                Yii::info('error message BaseMethodItemsSize Saqlanmadi', 'save');
                                break;
                            }
                        }
                        else{
                            Yii::info('Erorr message Size biri bosh ', 'save');
                            $saved = false;
                            break;
                        }
                    }
                }
                /** /Base Method Size Items save Yakunlandi */
                
                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                    return $this->redirect(['index']);
                }
                else{
                    $transaction->rollBack();
                    return $this->refresh();
                }
            }
            catch (\Exception $e){
                Yii::info('error message '.$e->getMessage(), 'save');
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelItems' => $modelItems,
            'modelItemsChilds' => $modelItemsChilds,
            'baseMethodSeam' => $baseMethodSeam,
        ]);
    }

    /**
     * Save And Finish
     * */
    public function actionSaveAndFinish()
    {
        $index = Yii::$app->request->get('id');
        if(!isset($index) && empty($index))
            return $this->redirect(Yii::$app->request->referrer);
        $data = Yii::$app->request->post();
        $methodSizeItem = $data['BaseMethodSizeItems'][$index];
        if($methodSizeItem){
            $sizeItems = BaseMethodSizeItems::findOne($methodSizeItem['id']);
            if($sizeItems){
                $sizeItems->status = BaseModel::STATUS_SAVED;
                if($sizeItems->save()){
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Saqlash va tugatish bajarildi'));
                    return $this->redirect(Yii::$app->request->referrer);
                }
                else{
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Saqlash va tugatish bajarilmadi'));
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
            else{
                Yii::$app->session->setFlash('error', Yii::t('app', 'BaseMethodSizeItems ma\'lumotlar bo\'sh'));
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        else{
            Yii::$app->session->setFlash('error', Yii::t('app', "Aniqlay olmadi bu indexni - {$index} "));
            return $this->redirect(Yii::$app->request->referrer);
        }

    }

    /**
     * Updates an existing Person model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelItems = $model->getBaseMethodSizeItems()->where(['status' => BaseMethod::STATUS_ACTIVE])->all()?$model->getBaseMethodSizeItems()->where(['status' => BaseMethod::STATUS_ACTIVE])->all():false;
        $modelItemsChilds = [];

        if ($modelItems) {
            foreach ($modelItems as $indexHouse => $modelHouse) {
                $rooms = $modelHouse->baseMethodSizeItemsChilds;
                if($rooms){
                    $modelItemsChilds[$indexHouse] = $rooms;
                }
            }
        }
        else{
            $modelItems = [new BaseMethodSizeItems()];
        }

        if($model->load(Yii::$app->request->post())){
            $data = Yii::$app->request->post();
            $transaction =  Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                if(!empty($modelItems)){
                    foreach ($modelItems as $modelItem) {
                        if($modelItem->status != BaseMethodSizeItems::STATUS_SAVED)
                            $modelItem->delete();
                    }
                }
                if($model->save()) $saved = true;

                $MethodItemsSize = $data['BaseMethodSizeItems'];

                /** Base Method Size Items save*/
                if(!empty($MethodItemsSize)){
                    foreach ($MethodItemsSize as $k => $item) {
                        if(!empty($item['size_id'])){
                            /** begin O'lchamlarni aniqlash uchun ishlatiladi */
                            $isSize = BaseMethodSizeItems::findOne([
                                'size_id' => $item['size_id'],
                                'base_method_id' => $model->id,
                            ]);
                            if($isSize){
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Siz oldin bundey o\'chamni saqlagansiz!'));
                                Yii::info("error message {$item['size_id']} - id nomerli o\'lcham mavjud!");
                                $saved = false;
                                break;
                            }
                            /** end O'lchamlarni aniqlash uchun ishlatiladi */
                            $itemsSize = new BaseMethodSizeItems();
                            $itemsSize->setAttributes([
                                'size_id' => $item['size_id'],
                                'base_method_id' => $model->id,
                            ]);
                            if($itemsSize->save() && $saved){
                                $saved = true;
                                /** BaseMethodSizeItemsChilds save */
                                $MethodItemsSizeChilds = $data['BaseMethodSizeItemsChilds'][$k];
                                if(isset($MethodItemsSizeChilds) && !empty($MethodItemsSizeChilds)){
                                    foreach ($MethodItemsSizeChilds as $methodItemsSizeChild) {
                                        $itemsSizeChilds = new BaseMethodSizeItemsChilds();
                                        $itemsSizeChilds->setAttributes([
                                            'base_method_seam_id' => $methodItemsSizeChild['base_method_seam_id'],
                                            'time' => $methodItemsSizeChild['time'],
                                            'base_method_size_items_id' => $itemsSize->id,
                                        ]);
                                        if($itemsSizeChilds->save()){
                                            $saved = true;
                                            unset($itemsSizeChilds);
                                        }
                                        else{
                                            $saved = false;
                                            Yii::info('error message BaseMethodItemsSizeChilds Saqlanmadi', 'save');
                                            break 2;
                                        }
                                    }
                                }
                                /** / BaseMethodSizeItemsChilds save Yakunlandi */
                                unset($itemsSize);
                            }
                            else{
                                $saved = false;
                                Yii::info('error message BaseMethodItemsSize Saqlanmadi', 'save');
                                break;
                            }
                        }
                        else{
                            Yii::info('Erorr message Size biri bosh ', 'save');
                            $saved = false;
                            break;
                        }
                    }
                }
                /** /Base Method Size Items save Yakunlandi */

                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else{
                    $transaction->rollBack();
                    return $this->refresh();
                }
                
            }
            catch(\Exception $e){
                Yii::info('erro message '.$e, 'save');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelItems' => (empty($modelItems)) ? [new BaseMethodSizeItems()] : $modelItems,
            'modelItemsChilds' => (empty($modelItemsChilds)) ? [[new BaseMethodSizeItemsChilds()]] : $modelItemsChilds
        ]);
    }

    /**
     * Deletes an existing BaseMethod model.
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

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "base-method_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => BaseMethod::find()->select([
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
     * Finds the BaseMethod model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BaseMethod the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BaseMethod::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
