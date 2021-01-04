<?php

namespace app\modules\base\controllers;

use app\components\Util;
use app\models\Notifications;
use app\models\UploadForms;
use app\modules\base\models\Attachments;
use app\modules\base\models\BasePatternMiniPostal;
use app\modules\base\models\BasePatternMiniPostalSizes;
use app\modules\base\models\BasePatternsVariations;
use app\modules\base\models\ModelMiniPostalFiles;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersVariations;
use app\modules\base\models\Size;
use app\modules\hr\models\HrDepartments;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use app\modules\base\models\BasePatterns;
use app\modules\base\models\BasePatternItems;
use app\modules\base\models\BasePatternsSearch;
use app\modules\base\models\BasePatternRelAttachment;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * BasePatternsController implements the CRUD actions for BasePatterns model.
 */
class BasePatternsController extends BaseController
{
    /**
     * Lists all BasePatterns models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BasePatternsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $id
     * */
    public function actionNewVariant()
    {
        $app = Yii::$app;
        $id = $app->request->get('id');
        /** Oxirgi variant nomerini olish */
        $lastNumber = BasePatternsVariations::find()
            ->where(['base_patterns_id' => $id])
            ->orderBy(['id' => SORT_DESC])
            ->one();
        if(!empty($lastNumber)){
            $number = $lastNumber['variant_no'] + 1;
            $modelVariant = new BasePatternsVariations();
            $modelVariant->base_patterns_id = $id;
            $modelVariant->variant_no = $number;
            $modelVariant->status = BasePatternsVariations::STATUS_ACTIVE;
            if($modelVariant->save()){
                $app->session->setFlash('success', Yii::t('app', $number.' - variant yaratildi'));
                return $this->redirect($app->request->referrer);
            }
            else{
                $app->session->setFlash('error', Yii::t('app', 'Error'));
                return $this->redirect($app->request->referrer);
            }
        }
        else{
            $app->session->setFlash('error', Yii::t('app', 'Variant nomer mavjud emas!'));
            return $this->redirect($app->request->referrer);
        }
    }
    
    /**
     * Displays a single BasePatterns model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $modelItems = new BasePatternItems();
        $searchModel = new BasePatternsSearch();
        /** Modelxona uchun modelOrdersVariations tabledan tasdiqlangan variantlar */
        $modelVar = new ModelOrdersVariations();

        /** Variantlar sonini olish */
        $variantCount = BasePatternsVariations::find()
            ->where(['base_patterns_id' => $id])
            ->asArray()
            ->all();
        /** Oxirgi variant nomerini olish */
        $variantNo = BasePatternsVariations::find()
            ->where(['base_patterns_id' => $id])
            ->orderBy(['id' => SORT_DESC])
            ->one();
        if($modelItems->load(Yii::$app->request->post())){
            if($modelItems->save()){
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfull'));
                return $this->redirect(Yii::$app->request->referrer);
            }
            else{
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'modelItems' => $modelItems,
            'variantCount' => $variantCount,
            'modelVar' => $modelVar,
        ]);
    }

    /**
     * @params $id
     * */
    public function actionOrdersPattern()
    {
        $app = Yii::$app;
        $id = $app->request->get('id');
        $transaction = $app->db->beginTransaction();
        try{
            $saved = false;
            /** BasePatternni statusini ozgartirish */
            $base = BasePatterns::updateAll(
                ['status' => 3],
                ['id' => $id]
            );

            $modelVariations = $app->request->post()['ModelOrdersVariations'];
            /** ModelOrdersVariation dagi malumotlarga basePatterns idni qoshish uchun oladi */
            $model = ModelOrdersVariations::findOne([
                'model_orders_id' => $modelVariations['model_orders_id'],
                'status' => 3
            ]);
            $model->base_patterns_id = $modelVariations['base_patterns_id'];
            $model->status = 3;
            if($model->save()){
                /** Model Orders malumotlarini olish */
                $modelOrders = ModelOrders::findOne($modelVariations['model_orders_id']);
                /** Marketingga xabar yuborish */
                $notification = new Notifications();
                /** Notificationni update qilamiz yani oldingisini statusini ozgartirib qoyamiz */
                $updateNotification = Notifications::findOne([
                    'doc_id' => $modelVariations['model_orders_id'],
                    'type' => 1,
                    'status' => 1
                ]);
                $updateNotification->status = ModelOrders::STATUS_SAVED;
                /** Notificationga malumotlarni joylaymiz */
                $dep_from = HrDepartments::findOne(['token' => ModelOrders::TOKEN_MARKETING]);
                $notification->setAttributes([
                    'doc_id' => $modelVariations['model_orders_id'],
                    'type' => 1,
                    'dept_from' => $dep_from->id,
                    'body' => $modelOrders->doc_number.' - Documentga qolip biriktirildi',
                    'to' => Yii::$app->user->id,
                    'status' => ModelOrders::STATUS_ACTIVE,
                    'module' => Yii::$app->controller->module->id,
                    'actions' => Yii::$app->controller->action->id,
                    'controllers' => Yii::$app->controller->id,
                    'pharams' => json_encode(['id'=>$modelVariations['model_orders_id']])
                ]);
                if($notification->save() && $updateNotification->save()){
                    $transaction->commit();
                    $app->session->setFlash('success', Yii::t('app', "Buyurtma qolipga biriktirildi"));
                    return $this->redirect(['model-orders/check-order', 'id' => $modelVariations['model_orders_id']]);
                }
                else{
                    $transaction->rollBack();
                    $app->session->setFlash('error', Yii::t('app', "Error"));
                    return $this->redirect($app->request->referrer);
                }
            }else{
                $transaction->rollBack();
                $app->session->setFlash('error', Yii::t('app', "Error"));
                return $this->redirect($app->request->referrer);
            }
        }
        catch(\Exception $e){
            Yii::info('Error Message '.$e->getMessage(),'save');
        }
    }

    /**
     * Creates a new BasePatterns model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BasePatterns();
        $model->cp['upload'] = new UploadForms();

        $data = Yii::$app->request->post();
        if (Yii::$app->request->isPost && $model->load($data)) {
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            /** BasePattern Fayllari*/
            $baseFile = $_FILES['BasePatternMiniPostal'];
            try {

                if ($model->load($data) && $model->save()) {
                    /** BasePatternsVariations ga saqlab ketish */
                    $basePatternsVariations = new BasePatternsVariations();
                    $basePatternsVariations->setAttributes([
                        'base_patterns_id' => $model->id,
                        'variant_no' => 1,
                        'status' => BasePatternsVariations::STATUS_ACTIVE,
                    ]);

                    if($basePatternsVariations->save())
                        $saved = true;
                    else
                        $saved = false;
                    
                    /** BasePattern rasmlarini saqlash */
                    if (!empty($data['BasePatterns']['path'])) {
                        $modelId = $model->id;
                        foreach ($data['BasePatterns']['path'] as $item){
                            $attachments = new Attachments();
                            $attachments->setAttributes([
                                'path' => $item,
                                'status' => Attachments::STATUS_ACTIVE
                            ]);
                            if($attachments->save()){
                                $saved = true;
                                $basePatternsAttachments = new BasePatternRelAttachment();
                                $basePatternsAttachments->setAttributes([
                                    'base_pattern_id' => $modelId,
                                    'attachment_id' => $attachments->id,
                                    'status' => BasePatternRelAttachment::STATUS_ACTIVE,
                                    'type' => 1
                                ]);
                                if($basePatternsAttachments->save() && $saved){
                                    $saved = true;
                                    unset($basePatternsAttachments);
                                }
                                else{
                                    $saved = false;
                                    break;
                                }
                                unset($attachments);
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                    /** MiniPostal save*/
                    if(!empty($data['BasePatternMiniPostal'])){
                        foreach($data['BasePatternMiniPostal'] as $k => $miniPostalData){
                            if(isset($miniPostalData['name']) && isset($miniPostalData['size'])){
                                $basePatterns = new BasePatternMiniPostal();
                                $namePath = substr($miniPostalData['name'], strrpos($miniPostalData['name'],"/") + 1);
                                $basePatterns->setAttributes([
                                    'base_patterns_id' => $model->id,
                                    'path' => $miniPostalData['name'],
                                    'name' => $namePath,
                                ]);
                                if($basePatterns->save()){
                                    $saved = true;
                                    foreach($miniPostalData['size'] as $key => $val){
                                        if(!empty($val)){
                                            $miniPostalSize = new BasePatternMiniPostalSizes();
                                            $miniPostalSize->setAttributes([
                                                'base_pattern_mini_postal_id' => $basePatterns->id,
                                                'size_id' => $val,
                                            ]);
                                            if($miniPostalSize->save()){
                                                $saved = true;
                                                unset($miniPostalSize);
                                            }
                                            else{
                                                $saved = false;
                                                break 2;
                                            }
                                        }
                                        else{
                                            break;
                                        }
                                    }
                                    $saved = true;
                                    unset($basePatterns);
                                }
                                else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                    }
                }

                if ($saved){
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                Yii::info('Error Message '.$e->getMessage(), 'save');
            }
        }
        return $this->render('create', [
            'model' => $model,
            'postals' => [new BasePatternMiniPostal()],
        ]);
    }

    /**
     * Updates an existing BasePatterns model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->status == BasePatterns::STATUS_SAVED)
            return $this->redirect(['index']);

        $model->cp['upload'] = new UploadForms();
        $postals = $model->basePatternMiniPostal;
        $delAttachment = $model->basePatternRelAttachments;

        /** MiniPostal size ni olish uchun */
        $array = [];
        foreach ($postals as $key => $postal) {
            foreach ($postal->basePatternMiniPostalSizes as $item) {
                $array[$key][] = $item['size']['id'];
            }
            $postal['size'] = $array[$key];
        }

        /** BasePatterns Path(Rasmlarini olish)*/
        $paths = $model->basePatternRelAttachments;
        $img = [];
        foreach ($paths as $path) {
            $img[] = $path['attachment']['path'];
        }
        $model['path'] = $img;
        $data = Yii::$app->request->post();
        if (Yii::$app->request->isPost) {
            try {
                $saved = false;
                $transaction = Yii::$app->db->beginTransaction();
                if ($model->load($data) && $model->save()) {
                    $saved = true;
                    foreach ($delAttachment as $item) {
                        if($item)
                            $item->delete();
                    }
                    /** Fayllar bilan ishlash basePatterns */
                    $baseFile = $data['BasePatterns']['path'];
                    if (!empty($baseFile)){
                        foreach($baseFile as $k => $v){
                            if(!empty($v)){
                                $extension = explode('.', $v);
                                $name = explode('/', $v);
                                /** Attachmentsga saqlash */
                                $attachments = new Attachments();
                                $attachments->setAttributes([
                                    'name' => $name[count($name) - 1],
                                    'extension' => $extension[count($extension) - 1],
                                    'path' => $v,
                                    'status' => 1,
                                    'type' => 1
                                ]);

                                $saved = $saved && $attachments->save();

                                $relation = new BasePatternRelAttachment();
                                $relation->setAttributes([
                                    'base_pattern_id' => $model->id,
                                    'attachment_id' => $attachments->id,
                                    'status' => BasePatternRelAttachment::STATUS_ACTIVE,
                                    'type' => 1,
                                ]);

                                $saved = $saved && $relation->save();
                            }
                        }
                    }


                    /** BasePatternMiniPostal fayllarini olish */
                    $files = BasePatternMiniPostal::find()->select(['name', 'extension', 'path'])->where(['base_patterns_id' => $model->id])->all();
                    /** mini postalni va size ni o'chirish */
                    $miniPostalIds = BasePatternMiniPostal::find()->select('id')->where(['base_patterns_id' => $model->id])->column();
                    foreach ($miniPostalIds as $miniPostalId) {
                        $delMiniPostal = BasePatternMiniPostal::findOne($miniPostalId);
                        $miniPostalSize = BasePatternMiniPostalSizes::find()->where(['base_pattern_mini_postal_id' => $delMiniPostal->id])->all();
                        if($miniPostalSize){
                            foreach ($miniPostalSize as $item) {
                                if($item)
                                    $item->delete();
                            }
                        }
                        if($delMiniPostal)
                            $delMiniPostal->delete();
                    }
                    $baseFiles = $_FILES['BasePatternMiniPostal'];
                    /** BasePatternMiniPostal Files uploads */
                    if(!empty($baseFiles)){
                        $array = [];
                        $i = 0;
                        foreach ($baseFiles['name'] as $k => $item) {
                            $extension = $baseFiles['type'];
                            if(!empty($item['name'])){
                                if(!is_dir('/uploads/mini_postal')){
                                    FileHelper::createDirectory('uploads/mini_postal');
                                }
                                $names = time().'_'.$i.$item['name'];
                                $url = 'uploads/mini_postal/'.$names;
                                $array1[$k][] = $url;
                                $saved = move_uploaded_file($baseFiles['tmp_name'][$k]['name'], $url);
                                $i++;
                            }
                        }
                        $i++;
                    }

                    /** MiniPostal save*/
                    if(!empty($data['BasePatternMiniPostal'])){
                        foreach($data['BasePatternMiniPostal'] as $k => $miniPostalData){
                            $basePatterns = new BasePatternMiniPostal();
                            $namePath = substr($miniPostalData['name'], strrpos($miniPostalData['name'],"/") + 1);
                            $basePatterns->setAttributes([
                                'base_patterns_id' => $model->id,
                                'path' => $miniPostalData['name'],
                                'name' => $namePath,
                            ]);
                            if($basePatterns->save()){
                                $saved = true;
                                foreach($miniPostalData['size'] as $key => $val){
                                    $miniPostalSize = new BasePatternMiniPostalSizes();
                                    $miniPostalSize->setAttributes([
                                        'base_pattern_mini_postal_id' => $basePatterns->id,
                                        'size_id' => $val,
                                    ]);
                                    if($miniPostalSize->save()){
                                        $saved = true;
                                        unset($miniPostalSize);
                                    }
                                    else{
                                        $saved = false;
                                        break 2;
                                    }
                                }
                                $saved = true;
                                unset($basePatterns);
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }
                    }

                    if($saved){
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Update'));
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                    else{
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                }
            } catch (\Exception $e) {
                Yii::info('Not saved' . $e, 'save');
                $transaction->rollBack();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'postals' => (!empty($postals)) ? $postals : [new BasePatternMiniPostal()],
            'array' => $array
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndFinish($id){
        $model = $this->findModel($id);
        $model->status = 3;
        $model->save();
        Yii::$app->session->setFlash('success',Yii::t('app','Saved Successfully'));
        return $this->redirect(['view','id' => $id]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    public function actionFileUpload($id=null){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new UploadForms();
        if(Yii::$app->request->isPost){
            if($model->files = UploadedFile::getInstances($model, 'files')){
                return $model->uploadFile('pattern');
            }
        }
        return false;
    }
    public function actionExportExcel()
    {
        header('Content-Type: application/vnd.ms-excel');
        $filename = "base-patterns_" . date("d-m-Y-His") . ".xls";
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => BasePatterns::find()->select([
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

    public function actionChangePatternName(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        $result = [];
        $result['status'] = false;

        $id = Yii::$app->request->post('pk');
        $value = Yii::$app->request->post('value');
        if (!empty($id) && !empty($value)){
            $model = BasePatterns::findOne(['id' => $id]);
            if (!empty($model)){
                $model['name'] = $value;
                if ($model->save()){
                    return ['message' => '', 'output' => $model['name'], 'status' => true];
                }else{
                    return ['message' => Yii::t('app','Bunday nomdagi qolip mavjud'), 'output' => null, 'status' => false];
                }
            }
        }
        return false;
    }

    /**
     * @param null $q
     * @return mixed
     * @throws \yii\db\Exception
     * Qolip mini postal o'lchamlari ajax oqrali olib kelindi
     */
    public function actionSizeAjaxList($q = null){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_null($q)){
            $query = "SELECT s.id, s.name text FROM size as s WHERE s.name LIKE '{$q}%'";
            $result['results'] = Yii::$app->db->createCommand($query)->queryAll();
            return $result;
        }
    }
    /**
     * Finds the BasePatterns model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BasePatterns the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BasePatterns::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
