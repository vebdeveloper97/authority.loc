<?php


namespace app\modules\base\controllers;


use app\modules\hr\models\HrDepartments;
use app\modules\wms\models\WmsDocument;
use app\modules\wms\models\WmsDocumentItems;
use app\modules\wms\models\WmsDocumentRel;
use app\modules\wms\models\WmsDocumentSearch;
use app\modules\wms\Wms;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class VariationAcsMaterialController extends BaseController
{
    public $slug;
    public $modelRoomDepId;

    public function init()
    {
        parent::init();
        $this->slug = Yii::$app->request->get('slug');

        $this->modelRoomDepId = HrDepartments::find()
            ->select('id')
            ->andWhere([
                'token' => HrDepartments::TOKEN_MODEL_ROOM,
            ])
            ->scalar();
    }

    public function actionIndex() {
        $searchModel = new WmsDocumentSearch(['department_id' => $this->modelRoomDepId]);
        $queryParams = Yii::$app->request->queryParams;

        $dataProvider = $searchModel->search($queryParams);

        return $this->render("{$this->slug}/index", [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $app = Yii::$app;
        $request = $app->request;
        $document = new WmsDocument();
        $documentItems = [new WmsDocumentItems()];

        switch ($this->slug) {
            case 'query_material':
                $document->setScenario(WmsDocument::SCENARIO_QUERY_FROM_MODEL_ROOM);
                $lastId = WmsDocument::getLastId();
                $date = date('Y');
                $document->doc_number = "MS-{$lastId}/{$date}";
                $document->reg_date = date('d.m.Y');
                $document->from_department = HrDepartments::getDepartmentIdByToken(HrDepartments::TOKEN_MODEL_ROOM);
                break;
        }

        if ($request->isPost) {
            $docItemsData = $request->post('WmsDocumentItems', []);
            foreach (array_keys($docItemsData) as $index) {
                $documentItems[$index] = new WmsDocumentItems(['scenario' => WmsDocumentItems::SCENARIO_QUERY_FROM_MODEL_ROOM]);
            }

            if ($document->load($request->post()) && Model::loadMultiple($documentItems, $request->post())) {
                $isSaved = false;

                switch ($this->slug) {
                    case 'query_material':
                        $transaction = $app->db->beginTransaction();
                        try {
                            /** modelxana uchun doc type => 6 (so'rov) qilib doc yaratiladi */
                            $document->document_type = WmsDocument::DOCUMENT_TYPE_QUERY;
                            $document->department_id = $this->modelRoomDepId;
                            $isSaved = $document->save();

                            if ($isSaved) {
                                foreach ($documentItems as $documentItem) {
                                    $documentItem->setAttributes([
                                        'wms_document_id' => $document['id'],
                                    ]);

                                    if (!($isSaved = $documentItem->save())) {
                                        Yii::error($documentItem->getErrors(), 'save');
                                        break;
                                    }
                                }
                            }

                            if ($isSaved) {
                                $transaction->commit();
                            }
                            else {
                                $transaction->rollBack();
                            }
                        } catch (\Throwable $exception) {
                            $isSaved = false;
                            $transaction->rollBack();
                            Yii::error($exception->getMessage(), 'exception');
                        }
                        break;
                }

                if ($isSaved) {
                    $app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                    return $this->redirect(["{$this->slug}/view", 'id' => $document['id']]);
                }
                else {
                    $app->session->setFlash('error', Yii::t('app', 'An error occurred'));
                }
            }
            else {
                $app->session->setFlash('error', Yii::t('app', 'No data received'));
            }
        }

        return $this->render("{$this->slug}/create", [
            'document' => $document,
            'documentItems' => $documentItems,
        ]);
    }

    public function actionView($id) {
        $app = Yii::$app;
        $request = $app->request;
        $document = $this->findDocument($id);
        $documentItems = $document->wmsDocumentItems;

        return $this->render("{$this->slug}/view", [
            'document' => $document,
            'documentItems' => $documentItems,
        ]);
    }

    public function actionUpdate() {

        return $this->render("{$this->slug}/update");
    }

    public function actionSaveAndFinish($id) {
        $app = Yii::$app;
        $document = $this->findDocument($id);

        if ($document->status < WmsDocument::STATUS_SAVED) {
            $documentItems = $document->wmsDocumentItems;
            $isSaved = false;

            switch ($this->slug) {
                case 'query_material':
                    /** Mato ombori uchun doc yaratish doc_type => 2 (moving) */
                    $transaction = $app->db->beginTransaction();
                    try {
                        $document->status = WmsDocument::STATUS_SAVED;
                        $isSaved = $document->save();

                        /** modelxona uchun doc type => 6 (so'rov) qilib doc yaratiladi */
                        if ($isSaved) {
                            $lastId = WmsDocument::getLastId();
                            $documentForMW = new WmsDocument();
                            $documentForMW->setAttributes([
                                'document_type' => WmsDocument::DOCUMENT_TRANSFER_ON_REQUEST,
                                'doc_number' => 'MK' . $lastId . '/' . date('Y'),
                                'reg_date' => date('d.m.Y'),
                                'from_department' => $document['to_department'],
                                'to_department' => $document['from_department'],
                                'from_employee' => $document['to_employee'],
                                'to_employee' => $document['from_employee'],
                                'department_id' => $document['to_department'],
                            ]);

                            $isSaved = $documentForMW->save();
                            if ($isSaved) {
                                foreach ($documentItems as $documentItem) {
                                    $documentItemForMW = new WmsDocumentItems();
                                    $documentItemForMW->setAttributes([
                                        'wms_document_id' => $documentForMW['id'],
                                        'quantity' => $documentItem['quantity'],
                                        'entity_id' => $documentItem['entity_id'],
                                        'entity_type' => $documentItem['entity_type']
                                    ]);

                                    $isSaved = $documentItemForMW->save();
                                    if (!$isSaved) {
                                        Yii::error($documentItemForMW->getErrors(), 'save');
                                        break;
                                    }
                                }

                                if ($isSaved) {
                                    /** modelxona va mato ombori doclarini ulash */
                                    $wmsDocumentRel = new WmsDocumentRel();
                                    $wmsDocumentRel->setAttributes([
                                        'parent' => $document['id'],
                                        'child' => $documentForMW['id'],
                                        'status' =>  WmsDocumentRel::STATUS_PENDING,
                                    ]);

                                    $isSaved = $wmsDocumentRel->save();
                                    if (!$isSaved) {
                                        Yii::error($wmsDocumentRel->getErrors(), 'save');
                                    }
                                }
                            }
                        }

                        if ($isSaved) {
                            $transaction->commit();
                            $msg = 'The request was sent successfully';
                        }
                        else {
                            $transaction->rollBack();
                        }
                    } catch (\Throwable $exception) {
                        $isSaved = false;
                        $transaction->rollBack();
                        Yii::error($exception->getMessage(), 'exception');
                    }
                    break;
            }

            if ($isSaved) {
                $app->session->setFlash('success', Yii::t('app', $msg));
            }
            else {
                $app->session->setFlash('error', Yii::t('app', 'An error occurred'));
            }

            return $this->redirect(["/base/variation-acs-material/{$this->slug}/view", 'id' => $document['id']]);
        }
    }

    public function findDocument($id)
    {
        $document = WmsDocument::findOne(['id' => $id]);
        if ($document === null) {
            throw new NotFoundHttpException();
        }

        return $document;
    }
}