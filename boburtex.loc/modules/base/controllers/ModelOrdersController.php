<?php

namespace app\modules\base\controllers;

use app\models\Notifications;
use app\models\Size;
use app\modules\base\models\Attachments;
use app\modules\base\models\BaseModel;
use app\modules\base\models\ModelOrderItemsPrints;
use app\modules\base\models\ModelOrderItemsStone;
use app\modules\base\models\ModelOrdersAttachmentRelations;
use app\modules\base\models\ModelOrdersCommentVarRel;
use app\modules\base\models\ModelOrdersFs;
use app\modules\base\models\ModelOrdersItems;
use app\modules\base\models\ModelOrdersItemsAcs;
use app\modules\base\models\ModelOrdersItemsChanges;
use app\modules\base\models\ModelOrdersItemsMaterial;
use app\modules\base\models\ModelOrdersItemsMato;
use app\modules\base\models\ModelOrdersItemsPechat;
use app\modules\base\models\ModelOrdersItemsSearch;
use app\modules\base\models\ModelOrdersItemsSize;
use app\modules\base\models\ModelOrdersItemsToquvAcs;
use app\modules\base\models\ModelOrdersItemsVariations;
use app\modules\base\models\ModelOrdersNaqsh;
use app\modules\base\models\ModelOrdersPlanning;
use app\modules\base\models\ModelOrdersResponsible;
use app\modules\base\models\ModelOrdersVariations;
use app\modules\base\models\ModelsAcs;
use app\modules\base\models\ModelsAcsVariations;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsNaqsh;
use app\modules\base\models\ModelsPechat;
use app\modules\base\models\ModelsRawMaterials;
use app\modules\base\models\ModelsVariations;
use app\modules\base\models\ModelVarBaski;
use app\modules\base\models\ModelVarPrints;
use app\modules\base\models\ModelVarStone;
use app\modules\base\models\MoiRelDept;
use app\modules\base\models\SizeCollections;
use app\modules\bichuv\Bichuv;
use app\modules\bichuv\models\BichuvAcs;
use app\modules\bichuv\models\BichuvBeka;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocExpense;
use app\modules\bichuv\models\BichuvDocItems;
use app\modules\bichuv\models\BichuvNastelDetails;
use app\modules\bichuv\models\BichuvSliceItems;
use app\modules\bichuv\models\BichuvSubDocItems;
use app\modules\boyoq\models\ColorPantone;
use app\modules\hr\models\HrDepartments;
use app\modules\settings\models\CompanyCategories;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvOrders;
use app\modules\toquv\models\ToquvRawMaterialColor;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\toquv\models\ToquvRawMaterialType;
use app\modules\wms\models\WmsColor;
use app\modules\wms\models\WmsDocument;
use app\modules\wms\models\WmsDocumentItems;
use app\modules\wms\models\WmsItemBalanceSearch;
use app\modules\wms\models\WmsMatoInfo;
use app\widgets\helpers\Telegram;
use kcfinder\path;
use moonland\phpexcel\Excel;
use Throwable;
use Yii;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersSearch;
use yii\base\Model;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * ModelOrdersController implements the CRUD actions for ModelOrders model.
 *
 */
class ModelOrdersController extends BaseController
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
     * Lists all ModelOrders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModelOrdersSearch();
        $userId = Yii::$app->user->id;
        if($userId!=1) {
            $searchModel->created_by = Yii::$app->user->id;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ModelOrders model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $isSave = $model->getModelOrdersItems()->where(['status' => $model::STATUS_ACTIVE])->all()?true:false;
        /** statusni tekshirish*/
        $status = null;
        if($model->status == 4){
            $status = 4;
        }
        else{
            $status = 1;
        }
        /** Yangi variant yaratilsa shu variantni saqlash va tugatish qilish xolatini yoqish uchun tekshirish model_orders_variations da status = 1 */
        $isModel = \app\modules\base\models\ModelOrdersVariations::findOne(['status' => 1, 'model_orders_id' => $model->id]);
        $searchModel = new ModelOrdersItemsSearch();
        $dataProviderThread= $searchModel->searchThread(Yii::$app->request->queryParams,$id);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $dataProviderPlanned = $searchModel->searchPlanned(Yii::$app->request->queryParams,$id);
        $dataProviderPlan=$searchModel->searchPlan(Yii::$app->request->queryParams,$id,MoiRelDept::TYPE_MATO);//Mato plan
        $dataProviderAksessuar=$searchModel->searchAksessuar(Yii::$app->request->queryParams,$id);

        return $this->render('view', [
            'model' => $model,
            'moiSearchModel' => $searchModel,
            'moiDataProvider' => $dataProvider,
            'status' => $status,
            'isModel' => $isModel,
            'dataProviderAksessuar' => $dataProviderAksessuar,
            'dataProviderThread' => $dataProviderThread,
            'dataProviderPlanned' => $dataProviderPlanned,
            'dataProviderPlan' => $dataProviderPlan,
            'isSave' => $isSave

        ]);
    }

    public function actionToPlan($id)
    {
        $model = $this->findModel($id);
        $orderItems = $model->modelOrdersItems;

        $user_id = Yii::$app->user->id;
        if($model->created_by != $user_id && $user_id != 1){
            return $this->redirect('index');
        }

        if(!empty($model)){
            $notification = new Notifications();
            if($notification->saveNotice($model, 1)){
                return $this->redirect(Yii::$app->request->referrer);
            }
            else{
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }

    public function actionCancledOrders($id)
    {
        $model = $this->findModel($id);
        // oldingi variantlarini ko'rish uchun
        $old_options = ModelOrdersVariations::find()
            ->where(['model_orders_id' => $id])
            ->andWhere(['!=', 'status', 3])
            ->asArray()
            ->all();
//        if ($model->status != ModelOrders::STATUS_INACTIVE) {
//            return $this->redirect(Yii::$app->request->referrer);
//        }
        $moiSearchModel = new ModelOrdersItemsSearch();
        $moiDataProvider = $moiSearchModel->search(Yii::$app->request->queryParams,$id);
        return $this->render('cancled/_variant', [
            'model' => $model,
            'old_options' => $old_options,
            'moiSearchModel' => $moiSearchModel,
            'moiDataProvider' => $moiDataProvider,
        ]);
    }

    public function actionUpdateItemsAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response['status'] = false;
        $id = Yii::$app->request->get('id');
        $mId = Yii::$app->request->get('modelsListId');
        $vId = Yii::$app->request->get('modelsVarId');

        $model = ModelOrdersItems::findOne($id);
        if($model->models_list_id){
            $model->model_var_id = $vId;
            if($model->save()){
                $response['status'] = true;
            }
        }
        else{
            $model->models_list_id = $mId;
            $model->model_var_id = $vId;
            if($model->save()){
                $response['status'] = true;
            }
        }
        return $response;
    }

    public function actionModelsListSelect()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response['status'] = false;
        $app = Yii::$app;
        /** ModelOrdersItems Id sini olish */
        $modelOrdersItemsId = $app->request->get('list');
        $response['id'] = $modelOrdersItemsId;
        /** ModelsList id sini olish */
        $modelsListId = $app->request->get('modelsListId');
        $modelsName = ModelsList::find()->all();
        if($modelsListId){
                $sql = "SELECT ml.id as mlid, ml.name as mlname, wc.color_code, wc.color_name, cp.name as cpname, wbt.name as wbtname, cp.code, wc.color_pantone_id, mv.id as mvid FROM
                    models_list ml 
                    LEFT JOIN models_variations mv ON mv.model_list_id = ml.id
                    LEFT JOIN toquv_raw_materials trm ON trm.id = mv.toquv_raw_material_id
                    LEFT JOIN wms_desen wd ON wd.id = mv.wms_desen_id
                    LEFT JOIN wms_color wc ON wc.id = mv.wms_color_id
                    LEFT JOIN wms_baski_type wbt ON wbt.id = wd.wms_baski_type_id
                    LEFT JOIN color_pantone cp ON cp.id = wc.color_pantone_id
                    WHERE ml.id = {$modelsListId}";
                $modelsVar = Yii::$app->db->createCommand($sql)->queryAll();

            $response['modelVar'] = $modelsVar;
        }
        $result = ModelOrders::getModelsList($modelsListId,ModelOrders::MODELS_IMG);
        if($modelsName || $response['modelvar']){
            $response['status'] = true;
            $response['data'] = $result;
            $response['models'] = $modelsName;
        }
        else{
            $response['message'] = Yii::t('app', "Ma'lumotlar bo'sh");
        }
        return $response;

    }

    public function actionCheckOrder($id) {
        $model = $this->findModel($id);
        $noteId = Yii::$app->request->get('noteId');
        $request = Yii::$app->request;
        $items_id = $request->get('items');
        /** Modelni ro'yxati yaratilganligini tekshirish */
        $isModel = $model->isModelLists($model->id, ModelOrders::STATUS_SAVED);

        // zakazni bekor qilingan versiyasiyaga tekshirib beradi
        if($model->orders_status == ModelOrders::STATUS_INACTIVE){
            return $this->redirect(['cancled-orders', 'id' => $id]);
        }

        $modelItems = ModelOrdersItems::findOne(['model_orders_id' => $model->id, 'status' => ModelOrders::STATUS_ACTIVE]);
        $commentForm = new ModelOrdersCommentVarRel();
        $postData = $request->post();

        if ($request->isPost && $commentForm->load($postData)) {
            $updateNote = Notifications::findOne($noteId);
            if($updateNote){
                $updateNote->status = $model::STATUS_SAVED;
                $updateNote->save();
            }
            $comments = isset($postData['ModelOrdersCommentVarRel']['comments']) ? $postData['ModelOrdersCommentVarRel']['comments'] : '';
            $commentForm->comments = explode(',', $comments);

            if ($commentForm->saveAndChangeStatuses($model)) {
                Yii::$app->session->setFlash('success', 'OK');
                return $this->redirect(['index']);
            }
        }

        $commentForm->type = 2;
        $moiSearchModel = new ModelOrdersItemsSearch();
        // variant id sini olish
        $variant_id = ModelOrdersVariations::find()
            ->select('id')
            ->where(['model_orders_id' => $id])
            ->andWhere(['status' => BaseModel::STATUS_SAVED])
            ->one();
        $moiDataProvider = $moiSearchModel->search(Yii::$app->request->queryParams,$id,$variant_id['id']);

        /** Models yoki Models ni varianti mavjud mavjud emasligini tekshirib beradi*/
        $isModels = ModelOrdersItems::find()
            ->where('model_orders_items.models_list_id <=> null')
            ->orWhere('model_orders_items.model_var_id <=> null')
            ->andWhere(['model_orders_id' => $model->id])
            ->andWhere(['status' => ModelOrdersItems::STATUS_ACTIVE])
            ->count();

        return $this->render('check-order', [
            'model' => $model,
            'moiSearchModel' => $moiSearchModel,
            'moiDataProvider' => $moiDataProvider,
            'commentForm' => $commentForm,
            'variant_id' => $variant_id,
            'items_id' => $items_id,
            'modelItems' => $modelItems,
            'isModel' => $isModels,
            'noteId' => $noteId,
        ]);
    }

    /**
     * Creates a new ModelOrders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ModelOrders();
        $models = new ModelOrdersItems();
        $modelsSize = [new ModelOrdersItemsSize()];
        $modelsAcs = [new ModelOrdersItemsAcs()];
        $modelsToquvAcs = [new ModelOrdersItemsToquvAcs()];
        $modelsVar = [new ModelOrdersItemsVariations()];
        $modelsMaterial = [new ModelOrdersItemsMaterial()];
        $modelsPechat = [new ModelOrdersItemsPechat()];
        $modelsNaqsh = [new ModelOrdersNaqsh()];
        $model->reg_date = date('d.m.Y');
        $model->status = ModelOrders::STATUS_NOACTIVE;
        $request = Yii::$app->request;
        if($request->isPost){
            /** Pechat malumotlari */
            $data = Yii::$app->request->post('ModelOrdersItemsPechat', []);
            foreach (array_keys($data) as $index) {
                $modelsPechat[$index] = new ModelOrdersItemsPechat();
            }

            if($model->load($request->post())
                && $models->load($request->post())
                && Model::loadMultiple($modelsPechat, Yii::$app->request->post())){
                $postData = $request->post();
                // model orders save
                $res = $model->getSaveAll($postData, $modelsPechat);

                if($res){
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                    return $this->redirect(['view', 'id' => $res]);
                }
                else{
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                    return $this->redirect($request->referrer);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'models' => $models,
            'modelsSize' => $modelsSize,
            'modelsAcs' => $modelsAcs,
            'modelsToquvAcs' => $modelsToquvAcs,
            'modelsVar' => $modelsVar,
            'modelsMaterial' => $modelsMaterial,
            'modelsPechat' => $modelsPechat,
            'modelsNaqsh' => $modelsNaqsh,
        ]);
    }

    /**
     * @param integer $id
     * */
    public function actionChangeCopy($id)
    {
        $model = $this->findModel($id);
        // oldingi variantlarini ko'rish uchun
        $old_options = ModelOrdersVariations::find()
            ->where(['model_orders_id' => $id])
            ->andWhere(['status' => 2])
            ->asArray()
            ->all();
        if($model->status == ModelOrders::STATUS_INACTIVE){
            return $this->redirect(['cancled-orders', 'id' => $id]);
        }
        $model->responsible = ModelOrdersResponsible::find()
            ->select('users_id')
            ->where(['model_orders_id' => $model->id])
            ->asArray()
            ->column();

        $moiSearchModel = new ModelOrdersItemsSearch();
        $moiDataProvider = $moiSearchModel->search(Yii::$app->request->queryParams,$id);
        $new_variants = ModelOrdersVariations::find()
            ->where(['model_orders_id' => $id])
            ->andWhere(['status' => ModelOrders::STATUS_SAVED])
            ->one();
        return $this->render('variant', [
            'model' => $model,
            'old_options' => $old_options,
            'moiSearchModel' => $moiSearchModel,
            'moiDataProvider' => $moiDataProvider,
            'new_variations' => $new_variants,
        ]);
    }

    public function actionCreateNewItem()
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            $this->enableCsrfValidation = false;
            $name = trim(Yii::$app->request->post('name'));
            $type = trim(Yii::$app->request->post('type'));
            $getModel = trim(Yii::$app->request->post('model'));
            $model = null;

            if($getModel == 'toquv-raw-material-type') {
                $model = new ToquvRawMaterialType();
                $model->type = $type;
                $model->name = $name;
            }

            if($getModel == 'toquv-raw-material-color') {
                $model = new ToquvRawMaterialColor();
                $model->name = $name;
            }

            if($model->save()){
//                $response['success'] = true;
//                $response['selected_id'] = $model->id;
//                $response['title'] = $model->name;
                return $model->id;
            }else{
                return 'fiel';
            }
            return $response;
        }
        else{
            return $this->redirect($request->referrer);
        }

    }

    public function actionGetCopySave()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $var_id = $request->get('var_id');
        $model = $this->findModel($id);
        $model->responsible = ModelOrdersResponsible::find()
            ->select('users_id')
            ->where(['model_orders_id' => $model->id])
            ->asArray()
            ->column();

        $models = ModelOrdersItems::findOne([
            'model_orders_id' => $model->id,
            'model_orders_variations_id' => $var_id
        ]);

        $modelsSize = $models->modelOrdersItemsSizes ? $models->modelOrdersItemsSizes : [new ModelOrdersItemsSize()];
        $modelsAcs = $models->modelOrdersItemsAcs ? $models->modelOrdersItemsAcs : [new ModelOrdersItemsAcs()];
        $modelsToquvAcs = $models->modelOrdersItemsToquvAcs ? $models->modelOrdersItemsToquvAcs : [new ModelOrdersItemsToquvAcs()];
        $modelsMaterial = $models->modelOrdersItemsMaterial ? $models->modelOrdersItemsMaterial : [new ModelOrdersItemsMaterial()];
        $modelsPechat = $models->modelOrdersItemsPechat ? $models->modelOrdersItemsPechat : false;
        $modelsNaqsh = $models->modelOrdersNaqsh ? $models->modelOrdersNaqsh : false;

        if($modelsPechat){
            /** Pechat rasmlarini olish */
            $pechatImg = [];
            foreach ($modelsPechat as $item) {
                $pechatImg[0] = $item['attachment']['path'];
                $item['attachment_id'] = $pechatImg;
            }
        }
        else{
            $modelsPechat = [new ModelOrdersItemsPechat()];
        }

        if($modelsNaqsh){
            /** Naqsh rasmlarini olish */
            $naqshImg = [];
            foreach ($modelsNaqsh as $item) {
                $naqshImg[0] = $item['attachment']['path'];
                $item['attachment_id'] = $naqshImg;
            }
        }
        else{
            $modelsNaqsh = [new ModelOrdersNaqsh()];
        }
        
        /** ModelsVariations larini olish */
        $modelsVariations = ArrayHelper::map(ModelsVariations::find()->where(['model_list_id' => $models->models_list_id])->all(),'id', function($m){
            return $m->wmsColor->fullName;
        });

        //mato info dagi ma'lumotlarni olish
        if(!empty($modelsMaterial)){
            // mato info dagi ma'lumotlarni olish
            foreach ($modelsMaterial as $documentItem) {
                $matoInfo = WmsMatoInfo::find()
                    ->andWhere(['id' => $documentItem->mato_id])
                    ->with('wmsColor')
                    ->one();

                if ($matoInfo) {
                    $documentItem->toquv_raw_materials_id = $matoInfo->toquv_raw_materials_id;
                    $documentItem->pus_fine_id = $matoInfo->pus_fine_id;
                    $documentItem->en = $matoInfo->en;
                    $documentItem->gramaj = $matoInfo->gramaj;
                    $documentItem->wms_color_id = $matoInfo->wms_color_id;
                    $documentItem->wms_desen_id = $matoInfo->wms_desen_id;
                }
            }
        }

        if(!empty($modelsToquvAcs)){
            // mato info dagi ma'lumotlarni olish
            foreach ($modelsToquvAcs as $documentItem) {
                $matoInfo = WmsMatoInfo::find()
                    ->andWhere([
                        'id' => $documentItem->wms_mato_info_id,
                        'type' => ToquvRawMaterials::ACS
                    ])
                    ->one();
                if ($matoInfo) {
                    $documentItem->toquv_raw_materials_id = ToquvRawMaterials::findOne(['id' => $matoInfo->toquv_raw_materials_id]);
                    $documentItem->pus_fine_id = $matoInfo->pus_fine_id;
                    $documentItem->en = $matoInfo->en;
                    $documentItem->gramaj = $matoInfo->gramaj;
                    $documentItem->wms_color_id = $matoInfo->wms_color_id;
                    $documentItem->wms_desen_id = $matoInfo->wms_desen_id;
                }
            }
        }

        if($model->orders_status === ModelOrders::STATUS_INACTIVE){
            if($model->load($request->post()) && $model->save()){
                $postData = $request->post();
                // model orders save
                $res = $model->getSaveAllCopy($postData,$model->id);
                if($res){
                    Yii::$app->session->setFlash('success', Yii::t('app', "Saved Successfully"));
                    return $this->redirect(['view', 'id' => $res]);
                }
                else{
                    return $this->redirect($request->referrer);
                }
            }

            return $this->render('new_variant', [
                'model' => $model,
                'models' => $models,
                'modelsSize' => $modelsSize,
                'modelsAcs' => $modelsAcs,
                'modelsToquvAcs' => $modelsToquvAcs,
                'modelsMaterial' => $modelsMaterial,
                'modelsPechat' => $modelsPechat,
                'modelsVariations' => $modelsVariations,
                'modelsNaqsh' => $modelsNaqsh
            ]);
        }
        else{
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }
    /**
     * @param integer $id
     * Ajax Size table
     * */
    public function actionGetSizeAjax()
    {
        $modelOrders = new ModelOrders();
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response['status'] = 0;

            // query paramets
            $id = $request->get('q');
            $result = $modelOrders->getSize(Size::class, ['id', 'code text'], 'code', $id);
            if($result){
                $response['status'] = 1;
                $response['results'] = $result;
                return $response;
            }
            else{
                $response['status'] = 0;
                return $response;
            }
        }
        else{
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Updates an existing ModelOrders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        /** ModelOrders Id */
        $id = Yii::$app->request->get('id');
        /** ModelOrdersItems Id */
        $m_id = Yii::$app->request->get('m_id');
        $model = $this->findModel($id);
        /** Responsible malumotlarni olish */
        $model->responsible = ModelOrdersResponsible::find()
            ->select('users_id')
            ->where(['model_orders_id' => $model->id])
            ->asArray()
            ->column();
        /** ModelOrders ni statusini tekshirish*/
        if($model->orders_status == ModelOrders::STATUS_SAVED)
        {
            Yii::$app->session->setFlash('success', Yii::t('app', "Ma'lumotlar saqlangan! Yangilab bo'lmaydi"));
            return $this->redirect(Yii::$app->request->referrer);
        }

        if(!empty($m_id) && !empty($id)){
            $models = ModelOrdersItems::findOne(['model_orders_id' => $model->id, 'id' => $m_id]);
        }
        else{
            $models = ModelOrdersItems::findOne(['model_orders_id' => $id]);
        }

        /** Buyurtmalarini rasmlarini olish */
        $img = [];
        foreach ($models->modelOrdersAttachmentRelations as $modelOrdersAttachmentRelation) {
            $img[] = $modelOrdersAttachmentRelation['attachments']['path'];
        }
        $models->files = $img;
        /** $modelsSize Modellarni ro'yxatini sizeni olish */
        $modelsSize = $models->modelOrdersItemsSizes ? $models->modelOrdersItemsSizes : [new ModelOrdersItemsSize()];
        /** Buyurtmalarni Aksessuarlarini olish */
        $modelsAcs = $models->modelOrdersItemsAcs ? $models->modelOrdersItemsAcs : [new ModelOrdersItemsAcs()];
        /** Buyurtmalarni Toquv Aksessuarlarini olish */
        $modelsToquvAcs = $models->modelOrdersItemsToquvAcs ? $models->modelOrdersItemsToquvAcs : [new ModelOrdersItemsToquvAcs()];
        /** ModelOrdersItemsVariations olish */
        $modelsVar = $models->modelOrdersItemsVariations ? $models->modelOrdersItemsVariations : [new ModelOrdersItemsVariations()];
        /** Buyurtmalarni Materiallarini olish */
        $modelsMaterial = $models->modelOrdersItemsMaterial ? $models->modelOrdersItemsMaterial : [new ModelOrdersItemsMaterial()];
        /** Buyurtmalarni pechatlarini olish */
        $modelsPechat = $models->modelOrdersItemsPechat ? $models->modelOrdersItemsPechat : [new ModelOrdersItemsPechat()];
        /** Buyurtmalarni naqshlarini olish */
        $modelsNaqsh = $models->modelOrdersNaqsh ? $models->modelOrdersNaqsh : [new ModelOrdersNaqsh()];

        /** Pechat rasmlarini olish */
        $pechatImg = [];
        foreach ($modelsPechat as $item) {
            $pechatImg[0] = $item['attachment']['path'];
            $item['attachment_id'] = $pechatImg;
        }

        /** Naqsh rasmlarini olish */
        $naqshImg = [];
        foreach ($modelsNaqsh as $item) {
            $naqshImg[0] = $item['attachment']['path'];
            $item['attachment_id'] = $naqshImg;
        }

        /** ModelsVariations larini olish */
        $modelsVariations = ArrayHelper::map(ModelsVariations::find()->where(['model_list_id' => $models->models_list_id])->all(),'id', function($m){
            return $m->wmsColor->fullName;
        });

        /** Materiallarini olish */
        if(!empty($modelsMaterial)){
            // mato info dagi ma'lumotlarni olish
            foreach ($modelsMaterial as $documentItem) {
                $matoInfo = WmsMatoInfo::find()
                    ->andWhere(['id' => $documentItem->mato_id])
                    ->with('wmsColor')
                    ->one();

                if ($matoInfo) {
                    $documentItem->toquv_raw_materials_id = $matoInfo->toquv_raw_materials_id;
                    $documentItem->pus_fine_id = $matoInfo->pus_fine_id;
                    $documentItem->en = $matoInfo->en;
                    $documentItem->gramaj = $matoInfo->gramaj;
                    $documentItem->wms_color_id = $matoInfo->wms_color_id;
                    $documentItem->wms_desen_id = $matoInfo->wms_desen_id;
                }
            }
        }

        /** Model Toquv Aksessuarlarini olish */
        if(!empty($modelsToquvAcs)){
            // mato info dagi ma'lumotlarni olish
            foreach ($modelsToquvAcs as $documentItem) {
                $matoInfo = WmsMatoInfo::find()
                    ->andWhere([
                        'id' => $documentItem->wms_mato_info_id,
                        'type' => ToquvRawMaterials::ACS
                        ])
                    ->one();
                if ($matoInfo) {
                    $documentItem->toquv_raw_materials_id = ToquvRawMaterials::findOne(['id' => $matoInfo->toquv_raw_materials_id]);
                    $documentItem->pus_fine_id = $matoInfo->pus_fine_id;
                    $documentItem->en = $matoInfo->en;
                    $documentItem->gramaj = $matoInfo->gramaj;
                    $documentItem->wms_color_id = $matoInfo->wms_color_id;
                    $documentItem->wms_desen_id = $matoInfo->wms_desen_id;
                }
            }
        }

        if($model->orders_status != ModelOrders::STATUS_SAVED) {
            $request = Yii::$app->request;
            if($model->load($request->post()) && $model->save()){
                $postData = $request->post();
                // model orders save
                $res = $model->getUpdateAll($postData, $model->id, $models->id);
                if($res){
                    Yii::$app->session->setFlash('success', Yii::t('app', "Ma'lumotlar yangilandi!"));
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else{
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                    return $this->redirect($request->referrer);
                }
            }

            return $this->render('_uform', [
                'model' => $model,
                'models' => $models,
                'modelsSize' => $modelsSize,
                'modelsAcs' => $modelsAcs,
                'modelsToquvAcs' => $modelsToquvAcs,
                'modelsVar' => $modelsVar,
                'modelsMaterial' => $modelsMaterial,
                'modelsPechat' => $modelsPechat,
                'modelsNaqsh' => $modelsNaqsh,
                'modelsVariations' => $modelsVariations,
            ]);
        }
        else{
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }
    
    /** Modelxonaga tushgan arizalarni korish*/
    public function actionRoom($id)
    {
        $model = $this->findModel($id);
        $noteId = Yii::$app->request->get('noteId')?Yii::$app->request->get('noteId'):false;
        /** begin FitSimple yaratilganmi yo'qmi shuni tekshirib beradi */
        $modelIems = $model->getModelOrdersItems()->where(['status' => 1])->orWhere(['status' => 3])->all()?$model->getModelOrdersItems()->where(['status' => 1])->orWhere(['status' => 3])->all():false;
        $isFitSimple = false;
        if($modelIems){
            foreach ($modelIems as $modelItem) {
                $fitSimple = ModelOrdersFs::findOne([
                    'model_orders_id' => $model->id,
                    'model_orders_items_id' => $modelItem['id']
                ]);
                if(!$fitSimple){
                    $isFitSimple = true;
                    break;
                }
            }
        }
        /** end FitSimple yaratilganmi yo'qmi shuni tekshirib beradi */
        $modelMaterials = $model->getModelOrdersItemsMato()->where(['model_orders_items_material.status' => 1])->all()?$model->getModelOrdersItemsMato()->where(['model_orders_items_material.status' => 1])->all():[];
        $modelAcs = $model->getModelOrdersItemsAcs()->where(['model_orders_items_acs.status' => 1])->all()?$model->getModelOrdersItemsAcs()->where(['model_orders_items_acs.status' => 1])->all():[];
        $modelToquvAcs = $model->getModelOrdersItemsToquvAcs()->where(['model_orders_items_toquv_acs.status' => 1])->all()?$model->getModelOrdersItemsToquvAcs()->where(['model_orders_items_toquv_acs.status' => 1])->all():[];

        /** statusni tekshirish*/
        $status = null;
        if($model->orders_status == 4){
            $status = 4;
        }
        else{
            $status = 1;
        }
        $moiSearchModel = new ModelOrdersItemsSearch();
        // variant id sini olish
        $variant_id = ModelOrdersVariations::find()
            ->select('id')
            ->where(['model_orders_id' => $id])
            ->andWhere(['status' => BaseModel::STATUS_SAVED])
            ->one();
        $moiDataProvider = $moiSearchModel->search(Yii::$app->request->queryParams,$id,$variant_id['id']);

        return $this->render('_room', [
            'model' => $model,
            'moiSearchModel' => $moiSearchModel,
            'moiDataProvider' => $moiDataProvider,
            'status' => $status,
            'modelMaterials' => $modelMaterials,
            'modelAcs' => $modelAcs,
            'modelToquvAcs' => $modelToquvAcs,
            'isFitSimple' => $isFitSimple,
            'noteId' => $noteId,
        ]);
    }

    /** Marketinga qaytarish */
    public function actionRepetition()
    {
        $app = Yii::$app;
        $id = $app->request->get('id');
        $model = $this->findModel($id);

    }

    public function actionSaveItem($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $dataList = Yii::$app->request->post('ModelOrdersItems');
            $data = reset($dataList);
            $response = [];
            $response['status'] = 0;
            $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
            if($result = $model->saveOneOrders($data,$dataList['id'])){
                if($result['status']==1){
                    $response['message'] = Yii::t('app', 'Saved Successfully');
                    $response['model'] = $result['model'];
                    $response['status'] = 1;
                    $sql = "SELECT sum(mois.count) summa FROM model_orders_items_size mois
                            LEFT JOIN model_orders_items moi on mois.model_orders_items_id = moi.id
                            LEFT JOIN model_orders mo on moi.model_orders_id = mo.id
                            WHERE mo.id = %d";
                    $sql = sprintf($sql,$model->id);
                    $model->sum_item_qty = Yii::$app->db->createCommand($sql)->queryOne()['summa'];
                    $model->save(false);
                }else{
                    $response['errors'] = $result['errors'];
                }
            }
            return $response;
        }
    }

    public function actionDeleteItem()
    {
        $id = Yii::$app->request->post('id');
        $model = ModelOrdersItems::findOne($id);
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 0;
            $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $saved = false;
                if(!empty($model)){
                    ModelOrdersItemsAcs::deleteAll(['model_orders_items_id' => $model->id]);
                    ModelOrderItemsPrints::deleteAll(['model_orders_items_id' => $model->id]);
                    ModelOrderItemsStone::deleteAll(['model_orders_items_id' => $model->id]);
                    ModelOrdersItemsSize::deleteAll(['model_orders_items_id' => $model->id]);
                    if($model->delete()){
                        $saved = true;
                    }
                }
                if($saved){
                    $response['status'] = 1;
                    $response['message'] = Yii::t('app', 'Deleted Successfully');
                    $transaction->commit();
                }else{
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                Yii::info('Not saved model orders' . $e, 'save');
            }
            return $response;
        }
    }

    public function actionSearchBaski()
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $data = Yii::$app->request->post();
            $response['model'] = ModelOrders::searchBaski($data['query'],$data['list']);
            if(count($response['model'])==0){
                $response['status'] = 0;
                $response['message'] = Yii::t('app', "Ma'lumotlar topilmadi");
            }
            return $response;
        }
    }

    public function actionSearchPrints()
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $data = Yii::$app->request->post();
            $response['model'] = ModelOrders::searchPrint($data['query'],$data['list']);
            if(count($response['model'])==0){
                $response['status'] = 0;
                $response['message'] = Yii::t('app', "Ma'lumotlar topilmadi");
            }
            return $response;
        }
    }

    public function actionSearchStones()
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $data = Yii::$app->request->post();
            $response['model'] = ModelOrders::searchStone($data['query'],$data['list']);
            if(count($response['model'])==0){
                $response['status'] = 0;
                $response['message'] = Yii::t('app', "Ma'lumotlar topilmadi");
            }
            return $response;
        }
    }

    public function actionMoiRelDept($id)
    {
        $model = $this->findModel($id);
        $models = ($model->modelOrdersItems) ? $model->modelOrdersItems : [new ModelOrdersItems()];
        $all_prints = ModelVarPrints::find()->limit(10)->all();
        $all_stone = ModelVarStone::find()->limit(10)->all();
        $all_acs = BichuvAcs::find()->limit(100)->all();
        $all_baski = ModelVarBaski::find()->limit(10)->all();
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'models' => $models,
                'all_stone' => $all_stone,
                'all_prints' => $all_prints,
                'all_acs' => $all_acs,
                'all_baski' => $all_baski,
            ]);
        }
    }

    public function actionSearchAcs()
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $data = Yii::$app->request->post();
            $response['model'] = ModelOrders::searchAcs($data['query'],$data['list']);
            if(count($response['model'])==0){
                $response['status'] = 0;
                $response['message'] = Yii::t('app', "Ma'lumotlar topilmadi");
            }
            return $response;
        }
    }
    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $user_id = Yii::$app->user->id;
        if($model->created_by!=$user_id&&$user_id!=1){
            return $this->redirect('index');
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndFinish($id){
        $model = $this->findModel($id);
        $user_id = Yii::$app->user->id;
        /** Shu foydalanuvchi yaratganmi aynan shunisini tekshirib beradi xatolik bersa index ga yuboradi */
        if($model->created_by != $user_id && $user_id != 1){
            return $this->redirect('index');
        }
        /** ModelOrders Bosh emaslikka tekshirish */
        if(!empty($model)){
            $notification = new Notifications();
            /** ModelOrdersVariation dagi malumotlarga basePatterns idni qoshish uchun oladi */
            $items = ModelOrdersItems::find()->where([
                'status' => BaseModel::STATUS_ACTIVE,
                'model_orders_id' => $model->id,
            ])
                ->select('id')
                ->asArray()
                ->all();
            if(!empty(($items))){
                $modelVariations = ModelOrdersVariations::findOne([
                    'model_orders_id' => $model->id,
                    'status' => ModelOrders::STATUS_ACTIVE
                ]);
                if($modelVariations){
                    $modelVariations->orders_items = json_encode($items);
                    $modelVariations->status = ModelOrders::STATUS_SAVED;

                    $modelVariations->save();
                }

            }

            $modelsItems = ModelOrdersItems::find()->where(['status' => ModelOrders::STATUS_ACTIVE, 'model_orders_id' => $model->id])->all()?ModelOrdersItems::find()->where(['status' => ModelOrders::STATUS_ACTIVE, 'model_orders_id' => $model->id])->all():false;
            if($modelsItems){
                foreach ($modelsItems as $modelsItem) {
                    $modelsItem['status'] = ModelOrders::STATUS_SAVED;
                    $modelsItem->save();
                }
            }

            if($notification->saveNotice($model)){
                return $this->redirect(Yii::$app->request->referrer);
            }
            else{
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }
    
    public function actionSaveAndPlanned($id){
        $model = $this->findModel($id);
        if($model->status < ModelOrders::STATUS_PLANNED){
            $model->setAttributes([
                'status' => ModelOrders::STATUS_PLANNED,
                'planning_id' => Yii::$app->user->identity->id,
                'planning_date' => date('Y-m-d H:i:s'),
                'responsible' => 1
            ]);
            $model->save();
        }
        return $this->redirect(['view','id' => $id]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndPlannedToquv($id){
        $model = $this->findModel($id);
        //$user_id = Yii::$app->user->id;
        /*if($model->created_by!=$user_id&&$user_id!=1){
            return $this->redirect('index');
        }*/
        if($model->status === ModelOrders::STATUS_PLANNED && $model->status < ModelOrders::STATUS_PLANNED_TOQUV){
            if($model->saveToquvOrders()) {
                $model->setAttributes([
                    'status' => ModelOrders::STATUS_PLANNED_TOQUV,
                    'responsible' => Yii::$app->user->id
                ]);
                $model->save(false);
            }
        }
        return $this->redirect(['view','id' => $id]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndPlannedToquvAks($id){
        $model = $this->findModel($id);
        if($model->status < ModelOrders::STATUS_PLANNED_TOQUV_AKS){
            if($model->saveToquvAksOrders()) {
                $model->setAttributes([
                    'status' => ModelOrders::STATUS_PLANNED_TOQUV_AKS,
                    'responsible' => Yii::$app->user->id
                ]);
                $model->save(false);
            }
        }
        return $this->redirect(['view','id' => $id]);
    }

    public function actionSaveDepartment($id){
        $model = ModelOrdersItems::findOne($id);
        $user_id = Yii::$app->user->id;
        if($model->created_by!=$user_id&&$user_id!=1){
            return $this->redirect('index');
        }
        $models = MoiRelDept::findAll(['model_orders_items_id'=>$id,'is_own'=>1]);
        $models_musteri = MoiRelDept::findAll(['model_orders_items_id'=>$id,'is_own'=>2]);
        if(Yii::$app->request->isAjax) {
            if(Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                $response['status'] = 1;
                $response['message'] = Yii::t('app','Xatolik yuz berdi!');
                if(empty($data)){
                    $response['status'] = 0;
                    $response['message'] = Yii::t('app','Saved Successfully');
                    $response['dept_list'] = $model->deleteDepartments();
                }elseif ($model->saveDepartments($data)) {
                    $response['status'] = 0;
                    $response['message'] = Yii::t('app','Saved Successfully');
                    $response['dept_list'] = $model->deptVal;
                }
                return $response;
            }
            return $this->renderAjax('save-department', [
                'id' => $id,
                'models' => $models,
                'models_musteri' => $models_musteri,
            ]);
        }
    }

    public function actionFinishDept()
    {
        $response = [];
        $response['status'] = 1;
        $response['message'] = Yii::t('app', 'Xatolik yuz berdi!');
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                $model = ModelOrdersItems::findOne($data['model_orders_items_id']);
                Yii::$app->response->format = Response::FORMAT_JSON;
                if (empty($data)) {
                    $response['status'] = 0;
                    $response['message'] = Yii::t('app', 'Saved Successfully');
                    $response['dept_list'] = $model->deleteDepartments();
                    $model->finishDepartments();
                } elseif ($model->saveDepartments($data,3)) {
                    $response['status'] = 0;
                    $response['message'] = Yii::t('app', 'Saved Successfully');
                    $response['dept_list'] = $model->deptVal;
                    $model->finishDepartments();
                }
            }
        }
        return $response;
    }
    public function actionSaveDept()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 1;
        $response['message'] = Yii::t('app','Xatolik yuz berdi!');
        if(Yii::$app->request->isAjax){
            if($id = Yii::$app->request->post('id')){
                $dept = MoiRelDept::findOne($id);
                if($dept){
                    $dept->status = 3;
                    if($dept->save()){
                        $response['status'] = 0;
                        $response['message'] = Yii::t('app','Saved Successfully');
                    }
                }
            }
        }
        return $response;
    }
    public function actionAjax($id)
    {
        $model = new ModelOrders();
        $models = new ModelOrdersItems();
        $urlRemain = Url::to('ajax-request');
        if (Yii::$app->request->isAjax){
            return $this->renderAjax('ajax', [
                'model' => $model,
                'models' => $models,
                'i' => $id,
                'urlRemain' => $urlRemain,
            ]);
        }
         return $this->render('ajax', [
             'model' => $model,
             'models' => $models,
             'i' => $id,
             'urlRemain' => $urlRemain,
         ]);
    }
    public function actionSize($num)
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 0;
            $response['message'] = Yii::t('app', "O'lchamlar topilmadi");
            $size_collection = SizeCollections::findOne($num);
            if($size_collection){
                $sizeList = $size_collection->getSizeList(true);
                $res = [];
                foreach ($sizeList as $key => $size) {
                    $res[$key] = [
                        'id' => $size['size_id'],
                        'name' =>  $size['size']['name']
                    ];
                }
                $response['size'] = $res;
                $response['status'] = 1;
                $response['message'] = 'Success';
            }
            return $response;
        }
    }
    public function actionNewOrder($order_id)
    {
        $model = ModelOrders::findOne($order_id) ?? new ModelOrders();
        $models = new ModelOrdersItems();
        $urlRemain = Url::to('ajax-request');
        if (Yii::$app->request->isAjax){
            return $this->renderAjax('ajax', [
                'model' => $model,
                'models' => $models,
                'i' => $order_id,
                'urlRemain' => $urlRemain,
            ]);
        }
        return $this->render('ajax', [
            'model' => $model,
            'models' => $models,
            'i' => $order_id,
            'urlRemain' => $urlRemain,
        ]);
    }
    public function actionRegPlanning($id)
    {
        $model = $this->findModel($id);

        if($model->status >= $model::STATUS_PLANNED){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new ModelOrdersPlanning();
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            if($model->savePlanning($data)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('reg-planning', [
            'model' => $model,
            'models' => $models
        ]);
    }
    public function actionUpdatePlanning($id)
    {
        $model = $this->findModel($id);
        if($model->status >= $model::STATUS_PLANNED){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new ModelOrdersPlanning();
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            if($model->savePlanning($data)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('reg-planning', [
            'model' => $model,
            'models' => $models
        ]);
    }
    /**
     * @return array
     */
    public function actionSavePlanning()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 0;
        $response['message'] = Yii::t('app','Xatolik yuz berdi!');
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->post()){
                $data = Yii::$app->request->post('ModelOrdersPlanning');
                $order_item = ModelOrdersItems::findOne($data['model_orders_items_id']);
                if($order_item){
                    $response = $order_item->saveOnePlanning($data);
                }
            }
        }
        return $response;
    }

    public function actionToquvAksPlanning($id)
    {
        $model = $this->findModel($id);
        if($model->status >= $model::STATUS_PLANNED_TOQUV_AKS){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new ModelOrdersPlanning();
        $models->scenario = $models::SCENARIO_AKS;
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            if($model->savePlanningAks($data)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('toquv-aks-planning', [
            'model' => $model,
            'models' => $models
        ]);
    }
    public function actionRegToquv($id)
    {
        $model = $this->findModel($id);
        $user_id = Yii::$app->user->id;
        if($model->status > $model::STATUS_PLANNED){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new MoiRelDept();
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $model->saveDept($data);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $models->company_categories_id = (CompanyCategories::findOne(['token'=>"TOQUV"])) ? CompanyCategories::findOne(['token'=>"TOQUV"])->toArray()['id'] : null;
        $models->toquv_departments_id = (ToquvDepartments::findOne(['token'=>"TOQUV_MATO_SEH"])) ? ToquvDepartments::findOne(['token'=>"TOQUV_MATO_SEH"])->toArray()['id'] : null;
        return $this->render('reg-toquv', [
            'model' => $model,
            'models' => $models,
        ]);
    }
    public function actionUpdateToquv($id)
    {
        $model = $this->findModel($id);
        if($model->status > $model::STATUS_PLANNED){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new MoiRelDept();
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $model->saveDept($data);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $models->company_categories_id = (CompanyCategories::findOne(['token'=>"TOQUV"])) ? CompanyCategories::findOne(['token'=>"TOQUV"])->toArray()['id'] : null;
        $models->toquv_departments_id = (ToquvDepartments::findOne(['token'=>"TOQUV_MATO_SEH"])) ? ToquvDepartments::findOne(['token'=>"TOQUV_MATO_SEH"])->toArray()['id'] : null;
        return $this->render('update-toquv', [
            'model' => $model,
            'models' => $models,
        ]);
    }
    public function actionRegToquvAks($id)
    {
        $model = $this->findModel($id);
        if($model->status > $model::STATUS_PLANNED_TOQUV_AKS){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new MoiRelDept();
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $model->saveDept($data,$models::TYPE_MATO_AKS);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $models->company_categories_id = (CompanyCategories::findOne(['token'=>"TOQUV"])) ? CompanyCategories::findOne(['token'=>"TOQUV"])->toArray()['id'] : null;
        $models->toquv_departments_id = (ToquvDepartments::findOne(['token'=>"TOQUV_ACS_SEH"])) ? ToquvDepartments::findOne(['token'=>"TOQUV_ACS_SEH"])->toArray()['id'] : null;
        return $this->render('reg-toquv-aks', [
            'model' => $model,
            'models' => $models,
        ]);
    }
    public function actionPlanning($id)
    {
        $model = $this->findModel($id);
        $user_id = Yii::$app->user->id;
        if($model->created_by!=$user_id&&$user_id!=1){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new ModelOrdersPlanning();
        return $this->render('view/planning', [
            'model' => $model,
            'models' => $models
        ]);
    }
    public function actionChangeAks($id)
    {
        $model = $this->findModel($id);
        $all_acs = BichuvAcs::find()->limit(100)->all();
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $result = $model->saveAcsOrders($data['ModelOrdersItems']);
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $result;
            }
            if($result['status']==1) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('change-aks', [
            'model' => $model,
            'all_acs' => $all_acs,
        ]);
    }

    /**
     * @param $id
     * @return array
     */
    public function actionToquvAcs($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ml = ModelsList::findOne($id);
        $res = [];
        if($ml){
            $toquv_acs = $ml->modelToquvAcs;
            if(!empty($toquv_acs)){
                foreach ($toquv_acs as $item) {
                    $rm = $item->rm;
                    $res[$rm['id']] = [
                        'id' => $rm['id'],
                        'name' => $rm->name,
                        'code' => $rm->code,
                        'type' => $rm->rawMaterialType->name,
                        'list' => $item->listWithType
                    ];
                }
            }
        }
        return $res;
    }
    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionChangeQuantity($id)
    {
        $model = $this->findModel($id);
        $models = new ModelOrdersPlanning();
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $model->sum_item_qty = array_sum(
            array_map(
                function($v) {
                    return $v['items'] ? array_sum($v['items'])  : 0;
                },
                $data['ModelOrdersPlanning']
            ));
            $model->responsible = 1;
            $model->save();
            $model->saveChangeQuantity($data);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('change-quantity', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * @return Response
     */
    public function actionCanselItems()
    {
        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');
            $item = ModelOrdersItems::findOne($id);
            if ($item) {
                ModelOrdersPlanning::updateAll(['status'=>2],['model_orders_items_id'=>$id]);
                $item->status = 2;
                if($item->save()){
                    $order = $item->modelOrders;
                    $order->sum_item_qty = $order->count;
                    $order->save(false);
                }
            }
            if (!empty($order)) {
                return $this->redirect(['view', 'id' => $order->id]);
            }
        }
    }

    public function actionStatus($id)
    {
        $toquv_orders = ToquvOrders::findOne($id);
        return $this->render('view/status',[
            'toquv_orders' => $toquv_orders
        ]);
    }

    public function actionAddRow()
    {
        $id = Yii::$app->request->post('id');
        $key = Yii::$app->request->post('key');
        $is_own = Yii::$app->request->post('is_own');
        return $this->renderAjax('save-department/ajax',[
            'id' => $id,
            'key' => $key,
            'is_own' => $is_own,
            'disabled' => false,
        ]);
    }

    /**
     * @param $q
     * @return array
     * */
    public function actionAjaxModels()
    {
        $model = new ModelOrders();
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response['status'] = 0;
            $id = $request->get('q');
            $result = $model->getSize(ModelsList::class, ['id', 'name text', 'article'], 'article', $id);
            if($result){
                $response['status'] = 1;
                $response['results'] = $result;
                return $response;
            }
            else{
                $response['status'] = 0;
                return $response;
            }

        }
    }

    /**
     * @param $q
     * @return array
     */
    public function actionAjaxRequest($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $arr = [];
        $arr = [];
        $response['results'] = [];
        if (!empty($q)) {
            $searchModel = new ModelOrders();
            $res = $searchModel->getModelList($q);
            if (!empty($res)) {
                $arr['check'] = [];
                foreach ($res['list'] as $item) {
                    if($item['is_main']!='0') {
                        $name = ($item['path']) ?
                            "<img src='/web/" . $item['path'] . "' style='width:30px;height:30px;border:1px solid'> <b> " .
                            $item['mart'] . " </b> - " . $item['mname'] . " - " . $item['tname'] :
                            "<b> " .$item['mart'] . " </b> - " . $item['mname'] . " - " . $item['tname'];
                        array_push($response['results'],[
                            'id' => $item['id'],
                            'text' => $name,
                            'baski' => $item['baski'],
                            'prints' => $item['prints'],
                            'stone' => $item['stone'],
                            'brend_id' => $item['brend_id'],
                            'acs' => $res['acs'][$item['id']]
                        ]);
                        $arr['check'][$item['id']] = [$item['id']];
                    }else{
                        if(!array_key_exists($item['id'], $arr['check'])){
                            $name = ($item['path']) ?
                                "<img src='/web/" . $item['path'] . "' style='width:30px;height:30px;border:1px solid'> <b> " .
                                $item['mart'] . " </b> - " . $item['mname'] . " - " . $item['tname'] :
                                "<b> " .$item['mart'] . " </b> - " . $item['mname'] . " - " . $item['tname'];
                            array_push($response['results'],[
                                'id' => $item['id'],
                                'text' => $name,
                                'baski' => $item['baski'],
                                'prints' => $item['prints'],
                                'stone' => $item['stone'],
                                'brend_id' => $item['brend_id'],
                                'acs' => $res['acs'][$item['id']]
                            ]);
                        }
                    }
                }
            } else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                ];
            }
        }
        return $response;
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "model-orders_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        Excel::export([
            'models' => ModelOrders::find()->select([
                'id',
                'doc_number',
                'musteri_id',
                'reg_date',
                'add_info'
            ])->all(),
            'columns' => [
                'id',
                'doc_number',
                [
                    'attribute' => 'musteri_id',
                    'value' => function($model){
                        return $model->musteri->name;
                    }
                ],
                'reg_date',
                'add_info'
            ],
            /*'headers' => [
                'id' => 'Id',
            ],*/
            'autoSize' => true,
        ]);
    }

    public function actionModelVariation()
    {
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response['status'] = false;
            $tId = Yii::$app->request->get('toquvId');
            $cId = Yii::$app->request->get('colId');
            $dId = Yii::$app->request->get('desId');
            $sql = "SELECT
                        wmi.toquv_raw_materials_id, wc.color_pantone_id, wc.color_code, wc.color_name, wc.color_palitra_code,
                        wd.name as wdname, wd.code as wdcode, wbt.name as wbtname, cp.name as cpname, cp.code as cpcode
                        FROM
                        wms_mato_info wmi
                        INNER JOIN wms_color wc ON wmi.wms_color_id = wc.id
                        INNER JOIN wms_desen wd ON wmi.wms_desen_id = wd.id
                        INNER JOIN wms_baski_type wbt ON wd.wms_baski_type_id = wbt.id
                        INNER JOIN color_pantone cp ON wc.color_pantone_id = cp.id
                        WHERE wmi.wms_color_id = {$cId} AND wmi.wms_desen_id = {$dId} AND wmi.toquv_raw_materials_id = {$tId}";
            $query = Yii::$app->db->createCommand($sql)->queryAll();
            if($query){
                $response['data'] = $query;
                $response['status'] = true;
            }
            else{
                $response['status'] = false;
            }
            return $response;
        }
    }
    
    public function actionGetModelVariations($id){
        $this->layout = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $arr = [];
        $response['status'] = 0;
        $response['message'] = 'error';
        $color['results'] = [];
        $model = ModelsList::findOne($id);
        $response['baski'] = $model->baski;
        $response['prints'] = $model->prints;
        $response['stone'] = $model->stone;
        /*if(Yii::$app->request->isAjax){
            $sql = "SELECT mv.id, mv.name as mname, mv.code as mcode, r,g,b, mvc.is_main
                    FROM models_variations as mv
                    LEFT JOIN models_variation_colors as mvc ON mv.id = mvc.model_var_id
                    LEFT JOIN color_pantone cp on mvc.color_pantone_id = cp.id
                    WHERE mv.status = 1 AND mv.model_list_id =  {$id}
                    ORDER BY mvc.is_main
            ";
            $res = Yii::$app->db->createCommand($sql)->queryAll();
            if (!empty($res)) {
                $arr['check'] = [];
                foreach ($res as $item) {
                    if($item['is_main']!='0') {
                        $name = "<span style='background:rgb(".$item['r'].",".$item['g'].",".$item['b'].");
                        padding-left:7px;padding-right:7px;border:1px solid;border-radius: 20px'></span> &nbsp; <b> " . $item['mname'] . " </b> <small>". $item['mcode'] ."</small>";
                        array_push($color['results'],[
                            'id' => $item['id'],
                            'text' => $name,
                        ]);
                        $arr['check'][$item['id']] = [$item['id']];
                    }else{
                        if(!array_key_exists($item['id'], $arr['check'])){
                            $name = "<span style='background:rgb(".$item['r'].",".$item['g'].",".$item['b']."); width:80px;
                        padding-left:5px;padding-right:5px;border:1px solid'></span>  &nbsp; <b> " . $item['mname'] . " </b> <small>". $item['mcode'] ."</small>";
                            array_push($color['results'],[
                                'id' => $item['id'],
                                'text' => $name,
                            ]);
                        }
                    }
                }
                $response['status'] = 1;
                $response['message'] = 'success';
                $response['data'] = ArrayHelper::map($color['results'],'id','text');
            }

        }*/
        return $response;
    }

    /**
     * @params $model_list_id
     * ajax query
     * get ModelListId
     * */
    public function actionModelListAjax()
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response['status'] = 0;
            /** ajax bilan keladigan o'zgaruvchi*/
            $model_list_id = $request->get('model_list_id');
            if($model_list_id){
                $model = new ModelOrders();
                /** Modellarni variations larini topib beradi $model_list_id boyicha*/
                $result = $model->getModelVar($model_list_id);
                /** Materiallarni olib kelish */
                $materials = $model->getModelRawMaterials($model_list_id);
                /** Aksessuarlarni olib kelish */
                $bichuvAcs = $model->getBichuvAcc($model_list_id);
                /** Toquv aksessuarlarni olib kelish */
                $toquvAcs = $model->getToquvAcc($model_list_id);
                /** Pechatlarni olib kelish */

                /** Naqshlarni olib kelish */
                if($result)
                {
                    $response['status'] = 1;
                    $response['results'] = $result;
                    $response['materials'] = $materials;
                    $response['bichuvAcs'] = $bichuvAcs;
                    $response['toquvAcs'] = $toquvAcs;
                }
                else{
                    $response['status'] = 0;
                    $response['materials'] = $materials;
                    $response['toquvAcs'] = $toquvAcs;
                    $response['bichuvAcs'] = $bichuvAcs;
                }
            }
            else{
                $response['status'] = 0;
                $response['error'] = Yii::t('app', "Parametr mavjud emas!");
            }
            return $response;
        }
    }

    /**
     * @params $var_id
     * ajax query
     * */
    public function actionModelVarAjax()
    {
//        if(Yii::$app->request->isAjax){
            $modelVarId = Yii::$app->request->get('var_id');
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $model = new ModelOrders();
            /** Models Varianti va materiallarini olish */
            $result = $model->getModelsVariations($modelVarId);

            /** Models Variant acc larini olish */
            $acc = $model->getVariationsAcc($modelVarId);

            /** Models Variantlarini pechat va naqshlarini olib kelish */
            $pechat = $model->getModelsVariationsPechats($modelVarId);

            if($result){
                $response['status'] = true;
                $response['data'] = $result;
                $response['acc'] = $acc;
                $response['modelsPechatNaqsh'] = $pechat;
            }
            else{
                $response['status'] = false;
                $response['data'] = $result;
            }
            return $response;
//        }
    }

    /**
     * @return $id
     * Ajax query
     * */
    public function actionAjaxVariantAllData($id)
    {
        if(isset($id) && !empty($id)){
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response['status'] = false;
                $data = ModelOrders::getVariantAllData($id);
                if($data){
                    $response['status'] = true;
                    $response['data'] = $data;
                }
                else{
                    $response['status'] = false;
                }
                return $response;
            }
        }
    }

    public function actionNewVariant()
    {
        $request = Yii::$app->request;
        /** ModelOrders Id */
        $id = $request->get('id');
        /** ModelOrdersItems Id */
        $m_id = $request->get('m_id');
        /** ModelOrdersni tanlab olish id orqali */
        $model = $this->findModel($id);
        /** ModelOrders ni statusini tekshirish*/
        if($model->orders_status == ModelOrders::STATUS_SAVED)
        {
            Yii::$app->session->setFlash('success', Yii::t('app', "Ma'lumotlar saqlangan! Yangi variant yaratib bo'lmaydi"));
            return $this->redirect($request->referrer);
        }
        /** ModelOrdersItemsni ayni bir variantini olish */
        $models = ModelOrdersItems::findOne($m_id);
        $img = [];
        foreach ($models->modelOrdersAttachmentRelations as $modelOrdersAttachmentRelation) {
            $img[] = $modelOrdersAttachmentRelation['attachments']['path'];
        }
        $models->files = $img;
//        $models->load_date = Yii::$app->formatter->asDate($models->load_date, 'mm.dd.yyyy');
        /** Qolgan malumotlari */
        $modelsSize = $models->modelOrdersItemsSizes ? $models->modelOrdersItemsSizes : [new ModelOrdersItemsSize()];
        $modelsAcs = $models->modelOrdersItemsAcs ? $models->modelOrdersItemsAcs : [new ModelOrdersItemsAcs()];
        $modelsToquvAcs = $models->modelOrdersItemsToquvAcs ? $models->modelOrdersItemsToquvAcs : [new ModelOrdersItemsToquvAcs()];
        $modelsVar = $models->modelOrdersItemsVariations ? $models->modelOrdersItemsVariations : [new ModelOrdersItemsVariations()];
        $modelsMaterial = $models->modelOrdersItemsMaterial ? $models->modelOrdersItemsMaterial : [new ModelOrdersItemsMaterial()];
        $modelsPechat = $models->modelOrdersItemsPechat ? $models->modelOrdersItemsPechat : false;
        $modelsNaqsh = $models->modelOrdersNaqsh ? $models->modelOrdersNaqsh : false;

        if($modelsPechat){
            /** Pechat rasmlarini olish */
            $pechatImg = [];
            foreach ($modelsPechat as $item) {
                $pechatImg[0] = $item['attachment']['path'];
                $item['attachment_id'] = $pechatImg;
            }
        }
        else{
            $modelsPechat = [new ModelOrdersItemsPechat()];
        }

        if($modelsNaqsh){
            /** Naqsh rasmlarini olish */
            $naqshImg = [];
            foreach ($modelsNaqsh as $item) {
                $naqshImg[0] = $item['attachment']['path'];
                $item['attachment_id'] = $naqshImg;
            }
        }
        else{
            $modelsNaqsh = [new ModelOrdersNaqsh()];
        }

        /** ModelsVariations larini olish */
        $modelsVariations = ArrayHelper::map(ModelsVariations::find()->where(['model_list_id' => $models->models_list_id])->all(),'id', function($m){
            return $m->wmsColor->fullName;
        });

        if(!empty($modelsToquvAcs)){
            // mato info dagi ma'lumotlarni olish
            foreach ($modelsToquvAcs as $documentItem) {
                $matoInfo = WmsMatoInfo::find()
                    ->andWhere([
                        'id' => $documentItem->wms_mato_info_id,
                        'type' => ToquvRawMaterials::ACS
                    ])
                    ->one();
                if ($matoInfo) {
                    $documentItem->toquv_raw_materials_id = ToquvRawMaterials::findOne(['id' => $matoInfo->toquv_raw_materials_id]);
                    $documentItem->pus_fine_id = $matoInfo->pus_fine_id;
                    $documentItem->en = $matoInfo->en;
                    $documentItem->gramaj = $matoInfo->gramaj;
                    $documentItem->wms_color_id = $matoInfo->wms_color_id;
                    $documentItem->wms_desen_id = $matoInfo->wms_desen_id;
                }
            }
        }

        // mato info dagi ma'lumotlarni olish
        foreach ($modelsMaterial as $documentItem) {
            $matoInfo = WmsMatoInfo::find()
                ->andWhere([
                    'id' => $documentItem->mato_id,
                ])
                ->with('wmsColor')
                ->one();

            if ($matoInfo) {
                $documentItem->toquv_raw_materials_id = $matoInfo->toquv_raw_materials_id;
                $documentItem->pus_fine_id = $matoInfo->pus_fine_id;
                $documentItem->en = $matoInfo->en;
                $documentItem->gramaj = $matoInfo->gramaj;
                $documentItem->wms_color_id = $matoInfo->wms_color_id;
                $documentItem->wms_desen_id = $matoInfo->wms_desen_id;
            }
        }

        if($request->isPost && $models->load($request->post())){
            $result = $model->getSaveVariations($request->post(), $model);
            if($result){
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else{
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                return $this->redirect($request->referrer);
            }
        }

            return $this->render('ajax_form', [
            'model' => $model,
            'models' => $models,
            'modelsSize' => $modelsSize,
            'modelsAcs' => $modelsAcs,
            'modelsToquvAcs' => $modelsToquvAcs,
            'modelsVar' => $modelsVar,
            'modelsMaterial' => $modelsMaterial,
            'modelsPechat' => $modelsPechat,
            'modelsNaqsh' => $modelsNaqsh,
            'modelsVariations' => $modelsVariations,
        ]);

    }

    /**
     * contructorga o'tkazish
     * */
    public function actionConstructor()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $noteId = $request->get('noteId');
        $updateNote = Notifications::findOne($noteId);
        if($updateNote){
            $updateNote->status = BaseModel::STATUS_SAVED;
            $updateNote->save();
        }
        /** ModelOrders ni olish*/
        $model = $this->findModel($id);
        $model->status = ModelOrders::STATUS_SAVED;
        $model->orders_status = ModelOrders::STATUS_SAVED;
        $items = $model->getModelOrdersItems()->where(['status' => ModelOrdersItems::STATUS_ACTIVE])->all();
        /** modelOrdersVariations ni olish */
        $modelOrdersVariations = ModelOrdersVariations::findOne([
            'model_orders_id' => $id,
            'status' => ModelOrders::STATUS_SAVED
            ]);
        /** Notification ni olish */
        $notifications = Notifications::findOne([
            'doc_id' => $model->id,
            'type' => 1
        ]);
        /** Transaction bilan ishlaymiz*/
        $transaction = Yii::$app->db->beginTransaction();
        $saved = true;
        try{
            /** Modellarni statuslarni constructor uchun moslab olamiz*/
            $model->status = ModelOrders::STATUS_SAVED;
            $model->orders_status = ModelOrders::STATUS_PLANNED;
            foreach ($items as $item){
                $item['status'] = ModelOrders::STATUS_SAVED;
                if($item->save()){
                    $saved = true;
                }
                else{
                    $saved = false;
                    break;
                }
            }
            $modelOrdersVariations->status = ModelOrdersItemsVariations::STATUS_PLANNED;
            $notifications->type = 4;
            /** Modellarni malumotlarini saqlaymiz! */
            if($model->save() && $modelOrdersVariations->save() && $notifications->save()){
                /** barchasi saqlansa commit*/
                $transaction->commit();
                Yii::$app->session->setFlash('success', Yii::t('app', $model->doc_number.' - raqamli buyurtma tasdiqlandi!'));
            }
            else{
                /** Saqlanmasi xatolikni qaytaramiz*/
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', Yii::t('app', 'Konstruktorga yuborishda xatolik mavjud!'));
                return $this->redirect($request->referrer);
            }
        }
        catch(\Exception $e){
            /** Exception yozib ketamiz */
            Yii::info('error message '.$e->getMessage(),'save');
        }

    }

    /** Buyurtmani qolipi tayyorlab bo'lingandan keyin uni modelXonaga yuborish status 8*/
    public function actionModelRoom()
    {
        $app = Yii::$app;
        $id = $app->request->get('id');
        $noteId = $app->request->get('noteId');
        if($noteId){
            Notifications::updateAll(
                ['status' => BaseModel::STATUS_SAVED],
                ['id' => $noteId]
            );
        }
        if(empty($id))
            return $this->redirect($app->request->referrer);

        /** Transaction bilan ishlash */
        $transaction = Yii::$app->db->beginTransaction();
        try{
            /** ModelOrders ni statusini 8 ga ozgartirish bilan modelxonaga yuborish qismi tayyorlanadi */
            $model = ModelOrders::findOne($id);
            $model->orders_status = ModelOrders::STATUS_MODEL_ROOM;
            /** Notificationsga yozish */
            $notification = new Notifications();
            $dep_from = HrDepartments::findOne(['token' => ModelOrders::TOKEN_KONSTRUKT]);
            $dep_to = HrDepartments::findOne(['token' => ModelOrders::TOKEN_MODELXONA]);
            $notification->setAttributes([
                'doc_id' => $model->id,
                'dept_from' => $dep_from->id,
                'dept_to' => $dep_to->id,
                'type' => ModelOrders::TYPE_MODELXONA,
                'body' => $model->doc_number.' - Hujjat Model Xonaga yuborildi!',
                'status' => ModelOrders::STATUS_ACTIVE,
                'reg_date' => date('Y-m-d H:i:s'),
                'module' => Yii::$app->controller->module->id,
                'controllers' => Yii::$app->controller->id,
                'actions' => Yii::$app->controller->action->id,
                'pharams' => json_encode(["id" => $model->id]),
                'to' => Yii::$app->user->id
            ]);
            if($model->save() && $notification->save()){
                $transaction->commit();
                $app->session->setFlash('success', Yii::t('app', 'ModelXonaga yuborildi!'));
                return $this->redirect(['model-orders/index']);
            }
            else{
                $transaction->rollBack();
                $app->session->setFlash('success', Yii::t('app', 'Model Xonaga yuborilmadi!'));
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        catch(\Exception $e){
            Yii::info('Error message '.$e->getMessage(), 'save');
        }
    }
    
    /** Ozidan query oladi*/
    public function actionThisQueryBuild($id)
    {
        $app = Yii::$app;
        $model = $this->findModel($id);

        $searchModel = new WmsItemBalanceSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);

        return $this->render('this-query-build', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Ajax Query
     * */
    public function actionDataAjax()
    {
        $app = Yii::$app;
        $app->response->format = Response::FORMAT_JSON;
        $orderId = $app->request->get('id');
        $orderItemsId = $app->request->get('mId');
        $response['status'] = false;
        if($orderId && $orderItemsId){
            /**
             * Aksessuarlarni olish
             * */
            $acs = ModelOrders::getItemsAcc($orderId,$orderItemsId);
            /**
             * Toquv Aksessuarlarni olish
             * */
            $toquvAcs = ModelOrders::getItemsToquvAcc($orderId,$orderItemsId);
            /**
             * Materiallarni olish
             * */
            $materials = ModelOrders::getItemsMaterials($orderId,$orderItemsId);
            /**
             * Model Images larni olish
             * */
            $modelImages = ModelOrders::getImages('model', $orderId, $orderItemsId);
            /**
             * Pechat Images larni olish
             * */
            $pechatImages = ModelOrders::getImages('pechat', $orderId, $orderItemsId);
            /**
             * Naqsh Images larni olish
             * */
            $naqshImages = ModelOrders::getImages('naqsh', $orderId, $orderItemsId);
            /**
             * Qoliplarni aniqlash
             * */
            $basePattern = ModelOrders::getBasePatterns($orderId, $orderItemsId);
            /**
             * Qoliplarni rasmlarini aniqlash
             * */
            $basePatternImg = ModelOrders::getBasePatternsImages($orderId, $orderItemsId);
            /**
             * Qoliplarni mini postal aniqlash
             * */
            $basePatternsMiniPostal = ModelOrders::getBasePatternsMiniPostal($orderId, $orderItemsId);
            /**
             * Fit Simple aniqlash
             * */
            $fitSimple = ModelOrders::getFitSImples($orderId, $orderItemsId);

            $response['status'] = true;
            $response['acc'] = $acs;
            $response['materials'] = $materials;
            $response['toquvAcs'] = $toquvAcs;
            $response['ModelImages'] = $modelImages;
            $response['pechatImages'] = $pechatImages;
            $response['naqshImages'] = $naqshImages;
            $response['basePatterns'] = $basePattern;
            $response['basePatternsImages'] = $basePatternImg;
            $response['basePatternsMiniPostal'] = $basePatternsMiniPostal;
            $response['fitSimple'] = $fitSimple;
        }
        else{
            $response['status'] = false;
        }
        return $response;
    }

    /** Mato omboriga Omborlarga query yuboradi */
    public function actionMaterialQueryBuild()
    {
        $app = Yii::$app;
        $id = $app->request->get('id');
        /** Malumotlarini olish */
        $model = ModelOrders::findOne($id);
        $modelMaterials = $model->getModelOrdersItemsMato()->where(['status' => 1])->all()?$model->getModelOrdersItemsMato()->where(['status' => 1])->all():[new ModelOrdersItemsMaterial()];
        /** Wms Document*/
        $document = new WmsDocument();
        $lastId = WmsDocument::getLastId();
        $document->model_orders_id = $model->id;
        $document->doc_number = "WD" . $lastId . "/" . date("Y");
        $document->reg_date = date('d.m.Y');
        $document->musteri_id = $model->musteri_id;
        /** Wms Document Items*/
        $documentItems = [new WmsDocumentItems()];

        if(!empty($modelMaterials)){
            // mato info dagi ma'lumotlarni olish
            foreach ($modelMaterials as $documentItem) {
                $matoInfo = WmsMatoInfo::find()
                    ->andWhere(['id' => $documentItem->mato_id])
                    ->with('wmsColor')
                    ->one();

                if ($matoInfo) {
                    $documentItem->toquv_raw_materials_id = $matoInfo->toquv_raw_materials_id;
                    $documentItem->pus_fine_id = $matoInfo->pus_fine_id;
                    $documentItem->en = $matoInfo->en;
                    $documentItem->gramaj = $matoInfo->gramaj;
                    $documentItem->wms_color_id = $matoInfo->wms_color_id;
                    $documentItem->wms_desen_id = $matoInfo->wms_desen_id;
                }
            }
        }
        $data = $app->request->post();
        /** Malumotlar yuklansa*/
        if($app->request->post()){
            $document->document_type = WmsDocument::DOCUMENT_TYPE_MOVING;
            $document->status = WmsDocument::STATUS_ACTIVE;
            $transaction = $app->db->beginTransaction();
            $saved = false;
            try{
                if($document->load($app->request->post()) && $document->save(false)){
                    $saved = true;
                    $result = $document->getSaveDocument($data, $document->id);
                    if($result && $saved){
                        $saved = false;
                        foreach ($modelMaterials as $modelMaterial) {
                            $modelMaterial['status'] = 3;
                            if($modelMaterial->save(false)){
                                $saved = true;
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }
                        if($saved){
                            $transaction->commit();
                            $app->session->setFlash('success', Yii::t('app', 'Material omboriga yuborildi!'));
                            return $this->redirect(['model-orders/room', 'id' => $id]);
                        }
                        else{
                            $transaction->rollBack();
                            $app->session->setFlash('error', Yii::t('app', 'Status o\'zgartirilmadi'));
                            return $this->redirect(['model-orders/room', 'id' => $id]);
                        }
                    }
                    else{
                        $transaction->rollBack();
                        $app->session->setFlash('error', Yii::t('app', 'Error'));
                        return $this->redirect(['model-orders/room', 'id' => $id]);
                    }
                }
            }
            catch(\Exception $e){
                Yii::info('error message '.$e->getMessage(),'save');
            }
        }

        return $this->render('wmsDocument', [
            'model' => $model,
            'modelMaterials' => $modelMaterials,
            'document' => $document,
            'documentItems' => $documentItems
        ]);
    }

    /** Toquv Accessory omboriga query yuboradi */
    public function actionToquvAcsQueryBuild()
    {
        $app = Yii::$app;
        $id = $app->request->get('id');
        /** Malumotlarini olish */
        $model = ModelOrders::findOne($id);
        $modelsAcs = $model->getModelOrdersItemsToquvAcs()->where(['status' => 1])->groupBy(['wms_mato_info_id'])->all()?$model->getModelOrdersItemsToquvAcs()->where(['status' => 1])->groupBy(['wms_mato_info_id'])->all():false;
        if(!empty($modelsAcs)){
            // mato info dagi ma'lumotlarni olish
            foreach ($modelsAcs as $documentItem) {
                $matoInfo = WmsMatoInfo::find()
                    ->andWhere([
                        'id' => $documentItem->wms_mato_info_id,
                    ])
                    ->one();
                if ($matoInfo) {
                    $documentItem->toquv_raw_materials_id = ToquvRawMaterials::findOne(['id' => $matoInfo->toquv_raw_materials_id]);
                    $documentItem->pus_fine_id = $matoInfo->pus_fine_id;
                    $documentItem->en = $matoInfo->en;
                    $documentItem->gramaj = $matoInfo->gramaj;
                    $documentItem->wms_color_id = $matoInfo->wms_color_id;
                    $documentItem->wms_desen_id = $matoInfo->wms_desen_id;
                }
            }
        }
        else{
            $modelsAcs = [new ModelOrdersItemsToquvAcs()];
        }

        /** Wms Document*/
        $document = new WmsDocument();
        $lastId = WmsDocument::getLastId();
        $document->doc_number = "WD" . $lastId . "/" . date("Y");
        $document->reg_date = date('d.m.Y');
        $document->musteri_id = $model->musteri_id;
        /** Wms Document Items*/
        $documentItems = [new WmsDocumentItems()];

        $data = $app->request->post();
        /** Malumotlar yuklansa*/
        if($app->request->post()){
            $document->document_type = WmsDocument::DOCUMENT_TYPE_MOVING;
            $document->status = WmsDocument::STATUS_ACTIVE;
            $document->model_orders_id = $model->id;
            $transaction = $app->db->beginTransaction();
            $saved = false;
            try{
                if($document->load($app->request->post()) && $document->save()){
                    $saved = true;
                    $result = $document->getAcsSaveDocument($data, $document->id);

                    if($result && $saved){
                        $modelsAcs = $model->getModelOrdersItemsToquvAcs()->where(['status' => 1])->all()?$model->getModelOrdersItemsToquvAcs()->where(['status' => 1])->all():[new ModelOrdersItemsToquvAcs()];
                        foreach ($modelsAcs as $modelAcs){
                            $modelAcs['status'] = 3;
                            if($modelAcs->save()){
                                $saved = true;
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }
                        if($saved){
                            $transaction->commit();
                            $app->session->setFlash('success', Yii::t('app', 'Toquv Aksessuar mato omboriga yuborildi!'));
                            return $this->redirect(['model-orders/room', 'id' => $id]);
                        }
                        else{
                            $transaction->rollBack();
                            $app->session->setFlash('error', Yii::t('app', 'Status o\'zgartirilmadi'));
                            return $this->redirect(['model-orders/room', 'id' => $id]);
                        }
                    }
                    else{
                        $transaction->rollBack();
                        $app->session->setFlash('error', Yii::t('app', 'Error'));
                        return $this->redirect(['model-orders/room', 'id' => $id]);
                    }
                }
            }
            catch(\Exception $e){
                Yii::info('error message '.$e->getMessage(),'save');
            }
        }

        return $this->render('toquv_acc_wmsDocument', [
            'model' => $model,
            'modelsAcs' => $modelsAcs,
            'document' => $document,
            'documentItems' => $documentItems
        ]);
    }

    /** Accessory omboriga query yuborish
     * @param $id
     * @return string|Response
     */
    public function actionAccQueryBuild($id){
        $model = new BichuvDoc();
        $model->reg_date = date('d.m.Y');
        $model->document_type = BichuvDoc::DOC_TYPE_MOVING;
        $lastId = $model::find()->select('id')->orderBy(['id' => SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $model->doc_number = "B" . $lastId . "/" . date('Y');
        $modelOrders = ModelOrders::findOne($id);
        $modelOrdersToquvAcs = $modelOrders->getModelOrdersItemsAcs()->where(['status' => 1])->groupBy(['bichuv_acs_id'])->all()?$modelOrders->getModelOrdersItemsAcs()->where(['status' => 1])->groupBy(['bichuv_acs_id'])->all():[new ModelOrdersItemsAcs()];

        if($model->load(Yii::$app->request->post())){
            $transaction = Yii::$app->db->beginTransaction();
            $data = Yii::$app->request->post();
            $bichuvAcs = $data['ModelOrdersItemsAcs'];
            $saved = true;
            try{
                if($model->save() && $saved){
                    if($bichuvAcs){
                        foreach($bichuvAcs as $item){
                            $bichuvItems = new BichuvDocItems();
                            $bichuvItems->setAttributes([
                                'bichuv_doc_id' => $model->id,
                                'entity_id' => $item['bichuv_acs_id'],
                                'entity_type' => 1,
                                'quantity' => $item['quantity'],
                                'document_quantity' => $item['quantity'],
                                'add_info' => $item['add_info']
                            ]);
                            if($bichuvItems->save(false) && $saved){
                                $saved = true;
                                unset($bichuvItems);
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                    $modelOrdersToquvAcs = $modelOrders->getModelOrdersItemsAcs()->where(['status' => 1])->all()?$modelOrders->getModelOrdersItemsAcs()->where(['status' => 1])->all():[new ModelOrdersItemsAcs()];
                    if($modelOrdersToquvAcs){
                        foreach($modelOrdersToquvAcs as $item){
                            $item['status'] = 3;
                            if($item->save() && $saved){
                                $saved = true;
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }
                    }

                    if($saved){
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app','Aksessuar omboriga yuborildi'));
                        return $this->redirect(['model-orders/room', 'id' => $id]);
                    }
                    else{
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Aksessuar omboriga yuborilmadi Xatolik!'));
                        return $this->redirect(['acc-query-build', 'id' => $id]);
                    }
                }
            }
            catch(\Exception $e){
                Yii::info('error message '.$e->getMessage(),'save');
            }
        }

        return $this->render('acs_Document', [
            'model' => $model,
            'models' => $modelOrdersToquvAcs,
        ]);
    }

    public function actionMarketing($id)
    {
        $model = $this->findModel($id);
        $model->orders_status = ModelOrders::STATUS_MARKETING;
        
        if($model->save()){
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                /** Notificationni birinchisini statusi ozgaradi*/
                $updateNotification = Notifications::findOne([
                    'doc_id' => $model->id,
                    'type' => ModelOrders::TYPE_MODELXONA,
                    'status' => 1
                ]);
                if($updateNotification){
                    $updateNotification->status = ModelOrders::STATUS_SAVED;
                    $saved = $updateNotification->save();
                }

                /** Notificationsga yozish */
                $notification = new Notifications();
                $dep_from = HrDepartments::findOne(['token' => ModelOrders::TOKEN_MODELXONA]);
                $dep_to = HrDepartments::findOne(['token' => ModelOrders::TOKEN_MARKETING]);
                $notification->setAttributes([
                    'doc_id' => $model->id,
                    'dept_from' => $dep_from->id,
                    'dept_to' => $dep_to->id,
                    'type' => ModelOrders::TYPE_MARKETING,
                    'body' => $model->doc_number.' - Hujjat Marketing bo\'limiga qayta yuborildi!',
                    'status' => ModelOrders::STATUS_ACTIVE,
                    'reg_date' => date('Y-m-d H:i:s'),
                    'module' => Yii::$app->controller->module->id,
                    'controllers' => Yii::$app->controller->id,
                    'actions' => Yii::$app->controller->action->id,
                    'pharams' => json_encode(["id" => $model->id]),
                    'to' => Yii::$app->user->id
                ]);
                   Yii::$app->session->setFlash('success', Yii::t('app', 'Save'));
                $saved = $saved && $notification->save();
                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', "Ma'lumotlar marketinga yuborildi!"));
                    return $this->redirect(['model-orders/view', 'id' => $model->id]);
                }
                else{
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', Yii::t('app', "Ma'lumotlar marketinga yuborilmadi!"));
                    return $this->redirect(['room', 'id' => $model->id]);
                }
            }
            catch (\Exception $e){
                Yii::info('Error Message '.$e->getMessage(),'save');;
            }
        }
    }

    public function actionItemsUpdate(){
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->get('id');
            $mId = Yii::$app->request->get('mId');
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response['status'] = false;
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                $modelOrdersItems = ModelOrdersItems::findOne($mId);
                if($modelOrdersItems){
                    $modelOrdersId = $modelOrdersItems->model_orders_id;
                    if($modelOrdersItems){
                        $modelOrdersItems->model_var_id = $id;
                        if($modelOrdersItems->save()){
                            $saved = true;
                        }
                        else{
                            $saved = false;
                        }
                    }

                    if($saved){
                        $materials = ModelOrdersItemsMaterial::find()->where(['status' => 1,'model_orders_items_id' =>$modelOrdersItems->id])->all()?ModelOrdersItemsMaterial::find()->where(['status' => 1,'model_orders_items_id' =>$modelOrdersItems->id])->all():false;
                        $modelsMaterials = ModelsRawMaterials::find()->where(['model_list_id' => $modelOrdersItems->models_list_id])->all()?ModelsRawMaterials::find()->where(['model_list_id' => $modelOrdersItems->models_list_id])->all():false;
                        $modelsAcs = ModelsAcsVariations::find()->where(['model_var_id' => $id, 'models_list_id' => $modelOrdersItems->models_list_id])->all()?ModelsAcsVariations::find()->where(['model_var_id' => $id, 'models_list_id' => $modelOrdersItems->models_list_id])->all():false;
                        $modelOrdersAcs = ModelOrdersItemsAcs::find()->where(['model_orders_items_id' => $modelOrdersItems->id, 'status' => 1])->all()?ModelOrdersItemsAcs::find()->where(['model_orders_items_id' => $modelOrdersItems->id, 'status' => 1])->all():false;
                        $modelvariation = ModelsVariations::findOne($id);
                        $unitId = null;
                        /** Materiallarni ochirish */
                        if($materials){
                            foreach ($materials as $material){
                                $material->delete();
                            }
                        }
                        /** Acs larni o'chirish */
                        if($modelOrdersAcs){
                            foreach ($modelOrdersAcs as $modelOrdersAc) {
                                $unitId = $modelOrdersAc->unit_id;
                                $modelOrdersAc->delete();
                            }
                        }

                        /** Materiallar bilan ishlash */
                        if($modelsMaterials){
                            foreach ($modelsMaterials as $modelsMaterial) {
                                $materialAttributes = [
                                    'toquv_raw_materials_id' => $modelsMaterial['rm_id'],
                                    'wms_desen_id' => $modelvariation->wms_desen_id==null?'':$modelvariation->wms_desen_id,
                                    'wms_color_id' => $modelvariation->wms_color_id==null?'':$modelvariation->wms_color_id,
                                    'type' => ToquvRawMaterials::MATO,
                                ];
                                $wmsMatoInfoId = WmsMatoInfo::saveAndGetId($materialAttributes, WmsMatoInfo::SCENARIO_DEFAULT_U);
                                $saved = $saved && $wmsMatoInfoId;
                                if (!$saved){
                                    new Telegram([
                                        'text' => '#WBM #model_orders_mato_info_errors ' . json_encode($materialAttributes),
                                        'module' => 'Base',
                                        'controlller' => 'ModelOrders',
                                    ]);
                                    Yii::debug('mato info saqlanmadi');
                                    break;
                                }
                                else{
                                    $modelOrdersMaterial = new ModelOrdersItemsMaterial();
                                    $modelOrdersMaterial->setAttributes([
                                        'model_orders_id' => $modelOrdersId,
                                        'model_orders_items_id' => $mId,
                                        'mato_id' => $wmsMatoInfoId,
                                        'status' => ModelOrders::STATUS_ACTIVE,
                                    ]);
                                    if($modelOrdersMaterial->save(false)){
                                        $saved = true;
                                        unset($modelOrdersMaterial);
                                    }
                                    else{
                                        $saved = false;
                                        break;
                                    }
                                }
                            }
                        }
                        /** Acslarni kiritish */
                        if($modelsAcs){
                            foreach ($modelsAcs as $modelsAc) {
                                $modelOrdersItemsAcs = new ModelOrdersItemsAcs();
                                $modelOrdersItemsAcs->setAttributes([
                                    'models_orders_id' => $modelOrdersId,
                                    'model_orders_items_id' => $mId,
                                    'bichuv_acs_id' => $modelsAc['bichuv_acs_id'],
                                    'status' => ModelOrders::STATUS_ACTIVE,
                                    'unit_id' => $unitId,
                                ]);
                                if($modelOrdersItemsAcs->save(false)){
                                    $saved = true;
                                    unset($modelOrdersItemsAcs);
                                }
                                else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                    }

                    if($saved){
                        $transaction->commit();
                        $response['status'] = true;
                    }
                    else{
                        $transaction->rollBack();
                        $response['status'] = false;
                    }
                    return $response;
                }
                else{
                    $transaction->rollBack();
                    return $response;
                }
            }
            catch(\Exception $e){
                $response['status'] = false;
                Yii::info('error message '.$e->getMessage(),'save');
                return  $response;
            }
        }
    }

    public function actionCreatePechat()
    {
        $app = Yii::$app;
        if($app->request->isAjax){
            $app->response->format = Response::FORMAT_JSON;
            $response['width'] = Yii::$app->request->get('title');
            $response['height'] = Yii::$app->request->get('content');
            $response['base_details'] = Yii::$app->request->get('base_details');
            $response['attachment'] = Yii::$app->request->get('attachments');
            $response['id'] = Yii::$app->request->get('id');
            $response['name'] = Yii::$app->request->get('name');
            $transaction = $app->db->beginTransaction();
            $saved = true;
            try{
                if($saved){
                    $model = new  ModelVarPrints();
                    $model->setAttributes([
                        'models_list_id' => $response['id'],
                        'image' => $response['attachment'],
                        'name' => $response['name'],
                        'width' => $response['width'],
                        'height' => $response['height'],
                        'base_details_list_id' => $response['base_details'],
                        'status' => ModelsList::STATUS_ACTIVE
                    ]);
                    $model->setScenario(ModelVarPrints::MODELSLIST_CODE);
                    if($model->save() && $saved){
                        $saved = true;
                    }
                    else{
                        $saved = false;
                    }
                }
                if($saved){
                    $transaction->commit();
                    $response['status'] = 1;
                    $response['data'] = ModelVarPrints::findOne($model->id);
                    return $response;
                }
                else{
                    $transaction->rollBack();
                    $response['status'] = 0;
                    return $response;
                }
            }
            catch(\Exception $e){
                Yii::info('Error Message '.$e->getMessage(), 'save');
                $response['error'] = 'Date Not save!';
                $response['status'] = false;
                return $response;
            }
        }
    }

    public function actionCreateNaqsh()
    {
        $app = Yii::$app;
        if($app->request->isAjax){
            $app->response->format = Response::FORMAT_JSON;
            $response['width'] = Yii::$app->request->get('title');
            $response['name'] = Yii::$app->request->get('name');
            $response['height'] = Yii::$app->request->get('content');
            $response['base_details'] = Yii::$app->request->get('base_details');
            $response['attachment'] = Yii::$app->request->get('attachments');
            $response['id'] = Yii::$app->request->get('id');
            $transaction = $app->db->beginTransaction();
            $saved = true;
            try{

                if($saved){
                    $model = new  ModelVarStone();
                    $model->setAttributes([
                        'models_list_id' => $response['id'],
                        'image' => $response['attachment'],
                        'width' => $response['width'],
                        'height' => $response['height'],
                        'name' => $response['name'],
                        'base_details_list_id' => $response['base_details'],
                        'status' => ModelVarStone::STATUS_ACTIVE
                    ]);
                    if($model->save() && $saved){
                        $saved = true;
                    }
                    else{
                        $saved = false;
                    }
                }

                if($saved){
                    $transaction->commit();
                    $response['status'] = 1;
                    $response['data'] = ModelVarStone::findOne($model->id);
                    return $response;
                }
                else{
                    $transaction->rollBack();
                    $response['status'] = 0;
                    return $response;
                }
            }
            catch(\Exception $e){
                Yii::info('Error Message '.$e->getMessage(), 'save');
            }
        }
    }

    /**
     * Finds the ModelOrders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModelOrders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ModelOrders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
