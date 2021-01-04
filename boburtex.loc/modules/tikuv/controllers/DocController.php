<?php

namespace app\modules\tikuv\controllers;

use app\modules\bichuv\models\BichuvGivenRolls;
use app\modules\bichuv\models\TikuvKonveyerBichuvGivenRolls;
use app\modules\tikuv\models\TikuvDocItems;
use app\modules\tikuv\models\TikuvKonveyer;
use app\modules\tikuv\models\TikuvSliceItemBalance;
use Yii;
use app\modules\tikuv\models\TikuvDoc;
use app\modules\tikuv\models\DocSearch;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\PermissionHelper as P;

/**
 * DocController implements the CRUD actions for TikuvDoc model.
 */
class DocController extends Controller
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
                    'delete' => ['POST', 'GET'],
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
                if (array_key_exists($slug, TikuvDoc::getDocTypeBySlug())) {
                    $flag = true;
                    $this->slug = $slug;
                }
            }
            if (!$flag) {
                throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
            }
            if (Yii::$app->authManager->getPermission(Yii::$app->controller->id . "/" . Yii::$app->controller->action->id)) {
                if (!P::can(Yii::$app->controller->id . "/" . $this->slug . "/" . Yii::$app->controller->action->id)) {
                    throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Lists all TikuvDoc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocSearch();
        $docType = "";
        $entityType = 1;
        $modelType = TikuvDoc::MODEL_TYPE_SLICE;
        switch ($this->slug) {
            case TikuvDoc::DOC_TYPE_INCOMING_LABEL:
                $docType = TikuvDoc::DOC_TYPE_INCOMING;
                break;
            case TikuvDoc::DOC_TYPE_MOVING_LABEL:
                $docType = TikuvDoc::DOC_TYPE_MOVING;
                break;
            case TikuvDoc::DOC_TYPE_SELLING_LABEL:
                $docType = TikuvDoc::DOC_TYPE_SELLING;
                break;
            case TikuvDoc::DOC_TYPE_OUTGOING_LABEL:
                $docType = TikuvDoc::DOC_TYPE_OUTGOING;
                break;
            case TikuvDoc::DOC_TYPE_RETURN_LABEL:
                $docType = TikuvDoc::DOC_TYPE_RETURN;
                break;
            case TikuvDoc::DOC_TYPE_INCOMING_MATO_LABEL:
                $docType = TikuvDoc::DOC_TYPE_INCOMING;
                $entityType = 2;
                break;
            case TikuvDoc::DOC_TYPE_MOVING_MATO_LABEL:
                $docType = TikuvDoc::DOC_TYPE_MOVING;
                $entityType = 2;
                break;
            case TikuvDoc::DOC_TYPE_ACCEPTED_MATO_LABEL:
                $docType = TikuvDoc::DOC_TYPE_ACCEPTED;
                $entityType = 2;
                break;
            case TikuvDoc::DOC_TYPE_MOVING_SLICE_LABEL:
                $docType = TikuvDoc::DOC_TYPE_MOVING;
                $entityType = 2;
                break;
            case TikuvDoc::DOC_TYPE_ACCEPTED_SLICE_LABEL:
                $docType = TikuvDoc::DOC_TYPE_ACCEPTED;
                $modelType = TikuvDoc::MODEL_TYPE_SLICE;
                $entityType = 2;
                break;
            case TikuvDoc::DOC_TYPE_REPAIR_MATO_LABEL:
                $docType = TikuvDoc::DOC_TYPE_REPAIR;
                $entityType = 2;
                break;
            case TikuvDoc::DOC_TYPE_ACCEPTED_LABEL:
                $docType = TikuvDoc::DOC_TYPE_ACCEPTED;
                $entityType = 1;
                $modelType = TikuvDoc::MODEL_TYPE_SLICE;
                break;
        }
        $dataProvider = $searchModel->search_doc(Yii::$app->request->queryParams, $modelType, $docType, $entityType);
        return $this->render("index/_index_$this->slug",
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * @param $id
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $sql = "select tk.name, tk.id, bgr.id as bgr_id from tikuv_doc_items tdi
                inner join bichuv_given_rolls bgr on bgr.nastel_party = tdi.nastel_party_no
                left join tikuv_konveyer_bichuv_given_rolls tkbgr on bgr.id = tkbgr.bichuv_given_rolls_id
                left join tikuv_konveyer tk on tkbgr.tikuv_konveyer_id = tk.id
                where tdi.tikuv_doc_id = %d limit 1;";
        $sql = sprintf($sql, $id);
        $konveyer = Yii::$app->db->createCommand($sql)->queryOne();
        return $this->render("view/_view_{$this->slug}", [
            'model' => $model,
            'konveyer' => $konveyer
        ]);
    }

    /**
     * Creates a new TikuvDoc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TikuvDoc();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TikuvDoc model.
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

    public function actionAccept($id)
    {
        $konveyer_list = TikuvKonveyer::find()->asArray()->all();
        $model = TikuvDoc::findOne($id);
        $item = TikuvDocItems::findOne(['tikuv_doc_id'=>$id]);
        if($item){
            $item = $item->toArray();
        }
        $bgr = BichuvGivenRolls::findOne(['nastel_party'=>$item['nastel_party_no']]);
        if($bgr){
            $bgr = $bgr->toArray();
        }
        $bgr_konveyer = TikuvKonveyerBichuvGivenRolls::findOne(['bichuv_given_rolls_id'=>$bgr['id']]);
        if($bgr_konveyer){
            $bgr_konveyer = $bgr_konveyer->toArray();
        }
        $konveyer = new TikuvKonveyer([
            'id' => $bgr_konveyer['tikuv_konveyer_id']
        ]);
        if($data = Yii::$app->request->post()){
            return $this->redirect([
                'save-and-finish',
                'slug' => $this->slug,
                'id' => $id,
                'konveyer' => $data['TikuvKonveyer']['id'],
                'bgr' => $bgr['id']
            ]);
        }
        return $this->render('accept',[
            'konveyer' => $konveyer,
            'konveyer_list' => ArrayHelper::map($konveyer_list, 'id', 'name'),
            'id' => $id,
            'model' => $model,
        ]);
    }

    public function actionSaveAndFinish($id,$konveyer=false,$bgr=false)
    {
        $model = $this->findModel($id);
        if ($model->status < TikuvDoc::STATUS_SAVED) {
            $slug = Yii::$app->request->get('slug');
            $t = Yii::$app->request->get('t', 1);
            $modelType = $model->document_type;
            switch ($modelType) {
                case 1:
                    $transaction = Yii::$app->db->beginTransaction();
                    try {

                    } catch (Exception $e) {
                        Yii::info('Not changed status to 3 ' . $e->getMessage(), 'save');
                    }
                    break;
                case 7:
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $saved = false;
                        $items = $model->getTikuvDocItems()->with(['size'])->asArray()->all();
                        $bgr_konveyer = TikuvKonveyerBichuvGivenRolls::findOne(['bichuv_given_rolls_id' => $bgr]);
                        if($bgr_konveyer){
                            $bgr_konveyer->tikuv_konveyer_id = $konveyer;
                            $bgr_konveyer->status = TikuvKonveyerBichuvGivenRolls::STATUS_STARTED;
                        }else {
                            $bgr_konveyer = new TikuvKonveyerBichuvGivenRolls([
                                'tikuv_konveyer_id' => $konveyer,
                                'bichuv_given_rolls_id' => $bgr,
                                'status' => TikuvKonveyerBichuvGivenRolls::STATUS_STARTED
                            ]);
                        }
                        $nastelNo = null;
                        if($bgr_konveyer->save()){
                            $saved = true;
                            if (!empty($items)) {
                                $modelId = $model->id;
                                $toDept = $model->to_department;
                                $fromDept = $model->from_department;
                                $musteriId = $model->musteri_id;
                                foreach ($items as $item) {
                                    $item['department_id'] = $toDept;
                                    $modelTSIB = new TikuvSliceItemBalance();
                                    $checkExists = $modelTSIB::getLastRecord($item);
                                    $inventory = $item['quantity'];
                                    if ($checkExists) {
                                        $inventory += $checkExists['inventory'];
                                    }
                                    $nastelNo = $item['nastel_party_no'];
                                    $modelTSIB->setAttributes([
                                        'entity_id' => $item['entity_id'],
                                        'entity_type' => 1,
                                        'size_id' => $item['size_id'],
                                        'nastel_no' => $item['nastel_party_no'],
                                        'count' => $item['quantity'],
                                        'inventory' => $inventory,
                                        'doc_id' => $modelId,
                                        'doc_type' => 1,
                                        'department_id' => $toDept,
                                        'from_department' => $fromDept,
                                        'to_department' => $toDept,
                                        'boyoqhona_model_id' => $item['boyoqhona_model_id'],
                                        'musteri_id' => $musteriId,
                                    ]);
                                    if ($modelTSIB->save()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            }
                            $model->status = 3;
                            $model->party_no = $nastelNo;
                            if($model->save() && $saved){
                                $saved = true;
                            }else{
                                $saved = false;
                            }
                        }else{
                            Yii::info('Not changed status to 3 ' . $bgr_konveyer->getErrors(), 'save');
                        }
                        if ($saved) {
                            $transaction->commit();
                        } else {
                            $transaction->rollBack();
                        }
                    } catch (Exception $e) {
                        Yii::info('Not changed status to 3 ' . $e->getMessage(), 'save');
                        $transaction->rollBack();
                    }
                    break;
            }
        }
        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug, 't' => $model->type]);
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
        /*$this->findModel($id)->delete();*/

        return $this->redirect(['index', 'slug' => $this->slug]);
    }

    public function actionExportExcel()
    {
        header('Content-Type: application/vnd.ms-excel');
        $filename = "doc_" . date("d-m-Y-His") . ".xls";
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => TikuvDoc::find()->select([
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
     * Finds the TikuvDoc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TikuvDoc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TikuvDoc::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
