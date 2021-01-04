<?php

namespace app\modules\base\controllers;

use app\modules\base\models\BaseModelDocumentItems;
use app\modules\base\models\BaseModelSizes;
use app\modules\base\models\BaseModelTableFile;
use app\modules\base\models\BaseModelTikuvFiles;
use app\modules\base\models\BaseModelTikuvNote;
use Yii;
use app\modules\base\models\BaseModelDocument;
use app\modules\base\models\BaseModelDocumentSearch;
use app\modules\base\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BaseModelDocumentController implements the CRUD actions for BaseModelDocument model.
 */
class BaseModelDocumentController extends BaseController
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
     * Lists all BaseModelDocument models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BaseModelDocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BaseModelDocument model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $modelItems = $model->baseModelDocumentItems?$model->baseModelDocumentItems:false;
        /** Malumotlarni oynaga chiqarish */
        if($modelItems){
            foreach ($modelItems as $k => $modelItem) {
                $docItemsId = null;
                $size = $modelItem->baseModelSizes;
                $filesTable = $modelItem->baseModelTableFiles;
                $filesTikuv = $modelItem->baseModelTikuvFiles;
                $noteItems = $modelItem->baseModelTikuvNotes;

                if($noteItems){
                    foreach ($noteItems as $key => $noteItem) {
                        $note[$k][$key] = $noteItem;
                    }
                }
                if($size){
                    foreach ($size as $item) {
                        $docItemsId = $item['doc_items_id'];
                        $array[$item['doc_items_id']][] = $item['size_id'];
                    }
                }
                if($filesTable){
                    foreach ($filesTable as $key => $item) {
                        $allTable[$k]['id'][] = $item['id'];
                        $arrayTable[$item['doc_items_id']][] = $item['attachment_id'];
                        if($item->attachments){
                            foreach ($item->attachments as $attachment) {
                                $allTable[$k][] = $attachment['path'];
                            }
                        }
                    }
                }

                if($filesTikuv){
                    foreach ($filesTikuv as $item) {
                        $allTikuv[$k]['id'][] = $item['id'];
                        $arrayTikuv[$item['doc_items_id']][] = $item['attachment_id'];
                        if($item->attachments){
                            foreach ($item->attachments as $attachment) {
                                $allTikuv[$k][] = $attachment['path'];
                            }
                        }
                    }
                }
                $modelSize = BaseModelSizes::findOne(['doc_items_id' => $docItemsId]);
                foreach ($array as $item) {
                    $modelSize['size_id'] = $item;
                }
                foreach ($arrayTikuv as $item) {
                    $modelSize['tikuv_file'] = $item;
                }
                foreach ($arrayTable as $item){
                    $modelSize['table_file'] = $item;
                }
                $modelSize['add_info'] = $modelItem['add_info'];
                $modelSize['items_id'] = $modelItem['id'];
                $sizes[] = $modelSize;
            }
        }
        else{
            $sizes = [new BaseModelSizes()];
            $note = [[new BaseModelTikuvNote()]];
        }
        /** Table Filelarini yig'ib olish */
        if($allTable){
            $pluginOptionsTable = BaseModelDocument::getFileShow($allTable);
        }
        /** Tikuv Filelarini yig'ib olish */
        if($allTikuv){
            $pluginOptionsTikuv = BaseModelDocument::getFileShow($allTikuv);
        }

        return $this->render('view', [
            'model' => $model,
            'sizes' => $sizes,
            'note' => $note,
            'pluginOptionsTable' => $pluginOptionsTable?$pluginOptionsTable:false,
            'pluginOptionsTikuv' => $pluginOptionsTikuv?$pluginOptionsTikuv:false,
        ]);
    }

    /**
     * Creates a new BaseModelDocument model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BaseModelDocument();
        $bmdId = BaseModelDocument::find()->orderBy(['id' => SORT_DESC])->asArray()->one();
        $lastId = $bmdId?$bmdId['id']+1:1;
        $model->doc_number = "BMD-".$lastId.'/'.date('d.m.Y');

        $sizes = [new BaseModelSizes()];
        $note = [[new BaseModelTikuvNote()]];

        if ($model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            $result = $model->getSaveData($data);
            if($result)
                return $this->redirect(['view', 'id' => $model->id]);
            else
                return $this->refresh();
        }

        return $this->render('create', [
            'model' => $model,
            'sizes' => $sizes,
            'note' => $note
        ]);
    }

    /**
     * Updates an existing BaseModelDocument model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelItems = $model->getBaseModelDocumentItems()->where(['status' => $model::STATUS_ACTIVE])->all()?$model->getBaseModelDocumentItems()->where(['status' => $model::STATUS_ACTIVE])->all():false;
        $sizes = [];
        $array = [];
        $arrayTable = [];
        $arrayTikuv = [];
        $allTikuv = [];
        $allTable = [];
        $note = [];

        /** Malumotlarni oynaga chiqarish */
        if($modelItems){
            foreach ($modelItems as $k => $modelItem) {
                $docItemsId = null;
                $size = $modelItem->baseModelSizes;
                $filesTable = $modelItem->baseModelTableFiles;
                $filesTikuv = $modelItem->baseModelTikuvFiles;
                $noteItems = $modelItem->baseModelTikuvNotes;

                if($noteItems){
                    foreach ($noteItems as $key => $noteItem) {
                        $note[$k][$key] = $noteItem;
                    }
                }
                if($size){
                    foreach ($size as $item) {
                        $docItemsId = $item['doc_items_id'];
                        $array[$item['doc_items_id']][] = $item['size_id'];
                    }
                }
                if($filesTable){
                    foreach ($filesTable as $key => $item) {
                        $allTable[$k]['id'][] = $item['id'];
                        $arrayTable[$item['doc_items_id']][] = $item['attachment_id'];
                        if($item->attachments){
                            foreach ($item->attachments as $attachment) {
                                $allTable[$k][] = $attachment['path'];
                            }
                        }
                    }
                }

                if($filesTikuv){
                    foreach ($filesTikuv as $item) {
                        $allTikuv[$k]['id'][] = $item['id'];
                        $arrayTikuv[$item['doc_items_id']][] = $item['attachment_id'];
                        if($item->attachments){
                            foreach ($item->attachments as $attachment) {
                                $allTikuv[$k][] = $attachment['path'];
                            }
                        }
                    }
                }
                $modelSize = BaseModelSizes::findOne(['doc_items_id' => $docItemsId]);
                foreach ($array as $item) {
                    $modelSize['size_id'] = $item;
                }
                foreach ($arrayTikuv as $item) {
                    $modelSize['tikuv_file'] = $item;
                }
                foreach ($arrayTable as $item){
                    $modelSize['table_file'] = $item;
                }
                $modelSize['add_info'] = $modelItem['add_info'];
                $modelSize['items_id'] = $modelItem['id'];
                $sizes[] = $modelSize;
            }
        }
        else{
            $sizes = [new BaseModelSizes()];
            $note = [[new BaseModelTikuvNote()]];
        }
        /** Table Filelarini yig'ib olish */
        if($allTable){
            $pluginOptionsTable = BaseModelDocument::getFileShow($allTable);
        }
        /** Tikuv Filelarini yig'ib olish */
        if($allTikuv){
            $pluginOptionsTikuv = BaseModelDocument::getFileShow($allTikuv);
        }
        if ($model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            if($modelItems){
                foreach ($modelItems as $modelItem) {
                    $modelItem->delete();
                }
            }
            
            $result = $model->getSaveData($data, $oldId = $model->id);
            
            if($result)
                return $this->redirect(['view', 'id' => $model->id]);
            else
                return $this->refresh();
        }
        return $this->render('update', [
            'model' => $model,
            'sizes' => $sizes,
            'note' => $note,
            'pluginOptionsTable' => $pluginOptionsTable?$pluginOptionsTable:false,
            'pluginOptionsTikuv' => $pluginOptionsTikuv?$pluginOptionsTikuv:false,
        ]);
    }

    public function actionDeleteFiles()
    {
        $type = Yii::$app->request->get('type');
        $id = Yii::$app->request->get('id');
        $deleteOne = null;
        $message = null;
        if ($type && $id){
            switch ($type){
                case 'table': $deleteOne = BaseModelTableFile::findOne($id); $deleteOne->delete(); $message = Yii::t('app', "Table Fayl ning {$id} - raqamdagi fayili o'chirildi"); break;
                case 'tikuv': $deleteOne = BaseModelTikuvFiles::findOne($id); $deleteOne->delete(); $message = Yii::t('app', "Tikuv Fayl ning {$id} - raqamdagi fayili o'chirildi");  break;
                default: $message = Yii::t('app', "Bunday type mavjud emas!");
            }
            Yii::$app->session->setFlash('success', $message);
            return $this->redirect(Yii::$app->request->referrer);
        }
        else{
            Yii::$app->session->setFlash('error', Yii::t('app', "Parametrlar yetarli emas!"));
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionDeleteItems()
    {
        $id = Yii::$app->request->get('id');
        if($id){
            $docItems = BaseModelDocumentItems::findOne($id)?BaseModelDocumentItems::findOne($id):false;
            if($docItems){
                $tikuv = $docItems->baseModelTikuvFiles?$docItems->baseModelTikuvFiles:false;
                $table = $docItems->baseModelTableFiles?$docItems->baseModelTableFiles:false;
                if($tikuv){
                    foreach ($tikuv as $item) {
                        $item->delete();
                    }
                }
                if($table){
                    foreach ($table as $item) {
                        $item->delete();
                    }
                }
                $docItems->delete();

                Yii::$app->session->setFlash('success', Yii::t('app', "Hujjatning {$id} - raqamidagi element o'chirildi!"));
                return $this->redirect(Yii::$app->request->referrer);
            }
            else{
                Yii::$app->session->setFlash('success', Yii::t('app', "Hujjatning {$id} - raqamidagi element mavjud emas!"));
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        else{
            Yii::$app->session->setFlash('error', Yii::t('app', "Parametr mavjud emas!"));
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionSaveItems()
    {
        $id = Yii::$app->request->get('id');
        if($id){
            $docItems = BaseModelDocumentItems::findOne($id)?BaseModelDocumentItems::findOne($id):false;
            if($docItems){
                $docItems->status = BaseModelDocumentItems::STATUS_SAVED;
                if($docItems->save()){
                    Yii::$app->session->setFlash('success', Yii::t('app', "Hujjatning {$id} - raqami saqlandi!"));
                    return $this->redirect(Yii::$app->request->referrer);
                }
                Yii::$app->session->setFlash('error', Yii::t('app', "Hujjatning {$id} - raqami saqlanmadi!"));
                return $this->redirect(Yii::$app->request->referrer);
            }
            else{
                Yii::$app->session->setFlash('error', Yii::t('app', "Hujjatning {$id} - raqami topilmadi!"));
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        else{
            Yii::$app->session->setFlash('error', Yii::t('app', "Parametr mavjud emas!"));
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Deletes an existing BaseModelDocument model.
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
        $filename = "base-model-document_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => BaseModelDocument::find()->select([
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
     * Finds the BaseModelDocument model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BaseModelDocument the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BaseModelDocument::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
