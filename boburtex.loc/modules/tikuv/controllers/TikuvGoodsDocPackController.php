<?php

namespace app\modules\tikuv\controllers;

use app\models\Constants;
use app\modules\admin\models\ToquvUserDepartment;
use app\modules\base\models\Brend;
use app\modules\base\models\Goods;
use app\modules\base\models\ModelOrdersItems;
use app\modules\tikuv\models\ClearNastelForm;
use app\modules\tikuv\models\CombineNatelForm;
use app\modules\tikuv\models\ModelRelDoc;
use app\modules\tikuv\models\NastelCombineList;
use app\modules\tikuv\models\TikuvDiffFromProduction;
use app\modules\tikuv\models\TikuvDoc;
use app\modules\tikuv\models\TikuvDocItems;
use app\modules\tikuv\models\TikuvGoodsDoc;
use app\modules\tikuv\models\TikuvGoodsDocAccepted;
use app\modules\tikuv\models\TikuvGoodsDocMoving;
use app\modules\tikuv\models\TikuvOutcomeProducts;
use app\modules\tikuv\models\TikuvOutcomeProductsPack;
use app\modules\tikuv\models\TikuvPackageItemBalance;
use app\modules\tikuv\models\TikuvSliceItemBalance;
use app\modules\toquv\models\ToquvDepartments;
use app\widgets\helpers\Telegram;
use Yii;
use app\modules\tikuv\models\TikuvGoodsDocPack;
use app\modules\tikuv\models\TikuvGoodsDocPackSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use app\modules\tikuv\models\TikuvGoodsDocAcceptedByTmo;

/**
 * TikuvGoodsDocPackController implements the CRUD actions for TikuvGoodsDocPack model.
 */
class TikuvGoodsDocPackController extends BaseController
{
    /**
     * Lists all TikuvGoodsDocPack models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TikuvGoodsDocPackSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionClear($type = 'all')
    {

        if (Yii::$app->request->isPost) {
            $nastel = Yii::$app->request->post();
            if (!empty($nastel['ClearNastelForm']['nastel_no'])) {
                $nst = $nastel['ClearNastelForm']['nastel_no'];
                switch ($type) {
                    case 'all':
                        TikuvSliceItemBalance::deleteAll(['doc_type' => 2, 'nastel_no' => $nst]);
                        TikuvPackageItemBalance::deleteAll(['nastel_no' => $nst]);
                        TikuvOutcomeProducts::deleteAll(['nastel_no' => $nst]);
                        TikuvOutcomeProductsPack::deleteAll(['nastel_no' => $nst]);
                        TikuvGoodsDocPack::deleteAll(['nastel_no' => $nst]);
                        $model = ModelRelDoc::findOne(['nastel_no' => $nst]);
                        if ($model !== null) {
                            $model->status = 1;
                            $model->save();
                        }
                        Yii::$app->session->setFlash('success', 'Hammasi ochirilda qaytadan tayyor maxsulotdan qabul qilib oling!');
                        return $this->redirect('/tikuv/tikuv-outcome-products-pack/index');
                        break;
                }
            }
        }
        $nastel = TikuvDocItems::find()->groupBy(['nastel_party_no'])->asArray()->all();
        $data = ArrayHelper::map($nastel, 'nastel_party_no', 'nastel_party_no');
        $model = new ClearNastelForm();
        return $this->render('clear', ['data' => $data, 'model' => $model, 'action' => 'clear']);
    }

    public function actionClearModel()
    {
        if (Yii::$app->request->isPost) {
            $nastel = Yii::$app->request->post();
            if (!empty($nastel['ClearNastelForm']['nastel_no'])) {
                $nst = $nastel['ClearNastelForm']['nastel_no'];
                ModelRelDoc::deleteAll(['nastel_no' => $nst]);
                TikuvDoc::updateAll(['is_change_model' => 1], ['party_no' => $nst]);
                Yii::$app->session->setFlash('success', 'Tasdiqlangan model ochirildi boshqatdan tasdiqlang!');
                return $this->redirect('/tikuv/tikuv-outcome-products-pack/change-models');
            }
        }
        $nastel = TikuvDocItems::find()->groupBy(['nastel_party_no'])->asArray()->all();
        $data = ArrayHelper::map($nastel, 'nastel_party_no', 'nastel_party_no');
        $model = new ClearNastelForm();
        return $this->render('clear', ['data' => $data, 'model' => $model, 'action' => 'clear-model']);
    }

    public function actionDeleteDoc($type = 'ready')
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            if (!empty($data['ClearNastelForm']['topp_id'])) {
                $id = $data['ClearNastelForm']['topp_id'];
                switch ($type) {
                    case 'ready':
                        $model = TikuvOutcomeProductsPack::findOne($id);
                        if($model==null){
                            Yii::$app->session->setFlash('error',Yii::t('app', 'Model topilmadi'));
                            return $this->render('delete-doc', ['model' => $model]);
                        }
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            $saved = false;
                            $items = $model->getTikuvOutcomeProducts()->asArray()->all();
                            $nastelNo = $model->nastel_no;
                            $musId = $model->musteri_id;
                            $dept = $model->department_id;
                            $toDept = $model->to_department;
                            $fromDept = $model->department_id;
                            $modelListId = $model->model_list_id;
                            $modelVarId = $model->model_var_id;
                            $orderId = $model->order_id;
                            $orderItemId = $model->order_item_id;
                            $barcodeCustomerId = $model->barcode_customer_id;
                            $modelId = $model->id;
                            foreach ($items as $item) {
                                $lastRecord = TikuvSliceItemBalance::find()->with(['size'])
                                    ->where(['nastel_no' => $nastelNo,
                                            'size_id' => $item['size_id']
                                ])->andFilterWhere(['>','inventory', 0])->orderBy(['id' => SORT_DESC])->asArray()->one();
                                $inventory = $item['quantity'];
                                if (!empty($lastRecord)) {
                                    $inventory = $lastRecord['inventory'] + $inventory;
                                    $fromDept = $lastRecord['from_department'];
                                }
                                $modelTSIB = new TikuvSliceItemBalance();
                                $modelTSIB->setAttributes([
                                    'entity_id' => 1,
                                    'entity_type' => 1,
                                    'size_id' => $item['size_id'],
                                    'nastel_no' => $nastelNo,
                                    'count' => $item['quantity'],
                                    'inventory' => $inventory,
                                    'doc_id' => $modelId,
                                    'doc_type' => 1,
                                    'department_id' => $dept,
                                    'from_department' => $fromDept,
                                    'to_department' => $toDept,
                                    'musteri_id' => $musId,
                                ]);
                                $lastRecordPackage = TikuvPackageItemBalance::find()->where([
                                    'goods_id' => $item['goods_id'],
                                    'model_var_id' => $modelVarId,
                                    'model_list_id' => $modelListId,
                                    'order_id' => $orderId,
                                    'order_item_id' => $orderItemId,
                                    'nastel_no' => $nastelNo,
                                    'sort_type_id' => $item['sort_type_id'],
                                    'department_id' => $toDept,
                                    'dept_type' => 'P',
                                    'barcode_customer_id' => $barcodeCustomerId
                                ])->orderBy(['id' => SORT_DESC])->asArray()->one();

                                $inventoryPackage = $item['quantity'];
                                if (!empty($lastRecordPackage)) {
                                    $inventoryPackage = $lastRecordPackage['inventory'] - $item['quantity'];
                                    if($inventoryPackage < 0){
                                        $inventoryPackage = 0;
                                    }
                                }
                                $modelTPIB = new TikuvPackageItemBalance();
                                $modelTPIB->setAttributes([
                                    'goods_id' => $item['goods_id'],
                                    'count' => (-1) * $item['quantity'],
                                    'inventory' => (int)$inventoryPackage,
                                    'nastel_no' => $nastelNo,
                                    'barcode_customer_id' => $barcodeCustomerId,
                                    'is_main_barcode' => $item['is_main_barcode'],
                                    'brand_type' => $item['type'],
                                    'doc_type' => 1,
                                    'dept_type' => 'P',
                                    'to_musteri' => $musId,
                                    'department_id' => $toDept,
                                    'from_department' => $dept,
                                    'model_list_id' => $modelListId,
                                    'model_var_id' => $modelVarId,
                                    'sort_type_id' => $item['sort_type_id'],
                                    'order_id' => $orderId,
                                    'order_item_id' => $orderItemId
                                ]);
                                if ($modelTSIB->save() && $modelTPIB->save()) {
                                    $saved = true;
                                    TikuvDiffFromProduction::deleteAll(['tikuv_op_id' => $item['id']]);
                                } else {
                                    $saved = false;
                                    break;
                                }
                            }
                            if ($saved) {
                                $saved = false;
                                if ($model->delete()) {
                                    $saved = true;
                                }
                            }
                            if ($saved) {
                                $transaction->commit();
                                Yii::$app->session->setFlash('success',"Hujjat o'chirildi");
                                return $this->redirect(['/tikuv/tikuv-outcome-products-pack/index']);
                            }else{
                                $transaction->rollBack();
                            }
                        } catch (\Exception $e) {
                            $transaction->rollBack();
                            Yii::info("Not saved {$e->getMessage()}",'save');
                        }
                        break;
                }
            }

        }
        $model = new ClearNastelForm();
        return $this->render('delete-doc', ['model' => $model]);

    }

    /**
     * @return string
     */
    public function actionCombine()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $data = Yii::$app->request->post();
            return $data;
            TikuvPackageItemBalance::find()->where('nastel_no IN ()')->asArray()->all();

        }
        $nastels = TikuvPackageItemBalance::find()->asArray()->groupBy(['nastel_no'])->all();
        $data = ArrayHelper::map($nastels, 'nastel_no', 'nastel_no');
        $model = new CombineNatelForm();
        return $this->render('combine', [
            'nastels' => $data,
            'model' => $model,
            'nastel' => null,
        ]);
    }

    public function actionUslugaSearchNastel()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('nastelNo');
            $sql = "SELECT SUM(tpib.inventory) remain,
                               tpib.nastel_no,
                               ml.article,
                               m.name              customer
                        FROM tikuv_package_item_balance tpib
                                 LEFT JOIN musteri m on tpib.to_musteri = m.id
                                 LEFT JOIN models_list ml on tpib.model_list_id = ml.id
                                 LEFT JOIN goods g ON tpib.goods_id = g.id
                        WHERE tpib.id IN (SELECT max(tpib.id)
                                          FROM tikuv_package_item_balance tpib
                                                   LEFT JOIN goods g ON tpib.goods_id = g.id
                                                   JOIN (SELECT nastel_no,
                                                                tpib2.barcode_customer_id,
                                                                model_list_id,
                                                                model_var_id,
                                                                order_id,
                                                                order_item_id,
                                                                goods_id,
                                                                g2.color,
                                                                g2.model_id
                                                         FROM tikuv_package_item_balance tpib2
                                                                  LEFT JOIN goods g2 ON tpib2.goods_id = g2.id
                                                         WHERE dept_type = 'P' AND tpib2.package_type = 1
                                                         GROUP BY nastel_no, model_list_id, model_var_id, order_id, order_item_id,
                                                                  tpib2.barcode_customer_id, tpib2.goods_id
                                          ) tpib2 ON tpib2.model_list_id = tpib.model_list_id AND
                                                     tpib.barcode_customer_id = tpib2.barcode_customer_id
                                          WHERE tpib2.nastel_no = '%s'
                                            AND tpib.department_id = 16
                                            AND tpib.package_type = 1
                                            AND tpib.dept_type = 'P'
                                            AND (tpib.goods_id = tpib2.goods_id OR (tpib.order_item_id = tpib2.order_item_id
                                              AND tpib.model_var_id = tpib2.model_var_id) OR
                                                 (g.model_id = tpib2.model_id AND g.color = tpib2.color)
                                              )
                                          GROUP BY tpib.nastel_no,
                                                   tpib.model_list_id,
                                                   tpib.model_var_id,
                                                   tpib.order_id,
                                                   tpib.order_item_id,
                                                   g.id,
                                                   tpib.sort_type_id,
                                                   tpib2.barcode_customer_id
                        )
                          AND tpib.inventory > 0
                          AND tpib.department_id = 16
                          AND tpib.package_type = 1
                          AND tpib.dept_type = 'P'
                        GROUP BY tpib.nastel_no,
                                 tpib.model_list_id,
                                 tpib.model_var_id,
                                 m.id
            ";
            $sql = sprintf($sql, $data);
            $res = Yii::$app->db->createCommand($sql)->queryAll();
            return $this->render('combine', [
                'nastel' => $res
            ]);
        }
    }

    public function actionSetUslugaNastel()
    {
        $response = [];
        $response['message'] = Yii::t('app', "Ma'lumotlar topilmadi!");
        $response['status'] = 0;
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $nastel = $data['nastel'];
            $nastel_list = join(',', $data['nastel_list']);
            $sql1 = "SELECT tpib.inventory remain,
                           tpib.nastel_no,
                           tpib.model_list_id,
                           tpib.model_var_id,
                           tpib.order_id,
                           tpib.order_item_id,tpib.nastel_no,
                           ml.article model,
                           m.name musteri,
                           s.id size_id,
                           tpib.goods_id,
                           tpib.sort_type_id,
                           sn.name sort_name,
                           s.name size
                    FROM tikuv_package_item_balance tpib
                        LEFT JOIN goods g on tpib.goods_id = g.id
                        LEFT JOIN size s on g.size = s.id
                        LEFT JOIN musteri m on tpib.to_musteri = m.id
                        LEFT JOIN models_list ml on tpib.model_list_id = ml.id
                        LEFT JOIN sort_name sn on tpib.sort_type_id = sn.id
                    WHERE tpib.nastel_no = '%s'
                      AND tpib.inventory > 0
                      AND tpib.department_id = 16
                      AND tpib.package_type = 1
                      AND tpib.dept_type = 'P'
                      AND tpib.id IN (SELECT max(id) id
                                       FROM tikuv_package_item_balance tpib2
                                       WHERE dept_type = 'P'
                                       GROUP BY nastel_no,goods_id,tpib2.sort_type_id)
                    GROUP BY tpib.nastel_no,
                             tpib.goods_id,
                             tpib.model_list_id,
                             tpib.model_var_id,
                             tpib.order_id,                         
                             tpib.order_item_id,
                             tpib.goods_id,
                             tpib.id,
                             tpib.sort_type_id
            ";
            $sql1 = sprintf($sql1, $nastel);
            $res1 = Yii::$app->db->createCommand($sql1)->queryAll();
            $sql2 = "SELECT tpib.inventory remain,
                           tpib.nastel_no,
                           tpib.model_list_id,
                           tpib.model_var_id,
                           tpib.order_id,
                           tpib.order_item_id,tpib.nastel_no,
                           ml.article model,
                           m.name musteri,
                           s.id size_id,
                           tpib.goods_id, 
                           tpib.sort_type_id, 
                            sn.name sort_name,
                           s.name size
                    FROM tikuv_package_item_balance tpib
                        LEFT JOIN goods g on tpib.goods_id = g.id
                        LEFT JOIN size s on g.size = s.id
                        LEFT JOIN musteri m on tpib.to_musteri = m.id
                        LEFT JOIN models_list ml on tpib.model_list_id = ml.id                        
                        LEFT JOIN sort_name sn on tpib.sort_type_id = sn.id
                    WHERE tpib.nastel_no in (%s)
                      AND tpib.inventory > 0
                      AND tpib.department_id = 16
                      AND tpib.package_type = 1
                      AND tpib.dept_type = 'P'
                      AND tpib.id IN (SELECT max(id) id
                                       FROM tikuv_package_item_balance tpib2
                                       WHERE dept_type = 'P'
                                       GROUP BY nastel_no,goods_id,tpib2.sort_type_id)
                    GROUP BY tpib.nastel_no,
                             tpib.goods_id,
                             tpib.model_list_id,
                             tpib.model_var_id,
                             tpib.order_id,
                             tpib.order_item_id,
                             tpib.goods_id,
                             tpib.id,                             
                             tpib.sort_type_id
            ";
            $sql2 = sprintf($sql2, $nastel_list);
            $res2 = Yii::$app->db->createCommand($sql2)->queryAll();
            $list = [];
            $remove = [];
            $response['list'] = [];
            if (!empty($res1)) {
                $response['status'] = 1;
                $response['message'] = Yii::t('app', 'Success');
                $response['title'] = $res1[0]['model'] . ' - ' . $nastel . ' - ' . $res1[0]['musteri'];
                foreach ($res1 as $item) {
                    $list[$item['goods_id']][$item['sort_type_id']] = [
                        'nastel' => $nastel,
                        'model' => $item['model'],
                        'musteri' => $item['musteri'],
                        'remain' => $item['remain'],
                        'size' => $item['size'],
                        'sort_name' => $item['sort_name'],
                        'sort_type_id' => $item['sort_type_id'],
                    ];
                    $response['model_id'] = $item['model_list_id'];
                    $response['model_var'] = $item['model_var_id'];
                    $response['order_id'] = $item['order_id'];
                    $response['order_item_id'] = $item['order_item_id'];
                }
                if (!empty($res2)) {
                    foreach ($res2 as $item2) {
                        $list[$item2['goods_id']][$item2['sort_type_id']] = [
                            'nastel' => $item2['nastel_no'],
                            'model' => $list[$item2['goods_id']]['model'] ?? $item2['model'],
                            'musteri' => $list[$item2['goods_id']]['musteri'] ?? $item2['musteri'],
                            'remain' => $item2['remain'] + $list[$item2['goods_id']][$item2['sort_type_id']]['remain'],
                            'size' => $item2['size'],
                            'sort_name' => $item2['sort_name'],
                            'sort_type_id' => $item2['sort_type_id'],
                        ];
                        $remove[$item2['nastel_no']][$item2['goods_id']][$item2['sort_type_id']] = [
                            'nastel_no' => $item2['nastel_no'],
                            'goods_id' => $item2['goods_id'],
                            'quantity' => $item2['remain'],
                            'sort_type_id' => $item2['sort_type_id'],
                        ];
                    }
                }
                $response['list'] = $list;
                $response['remove'] = $remove;
                $response['nastel_list'] = $nastel_list;
                $response['nastel'] = $nastel;
            }
        }
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }
    }

    public function actionUslugaNastelCombine()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            if ($data['nastel']) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if (!empty($data['data'])) {
                        foreach ($data['data'] as $key => $item) {
                            foreach ($item as $k => $value) {
                                $lastRec = TikuvPackageItemBalance::find()->where([
                                    'nastel_no' => $data['nastel'],
                                    'department_id' => 16,
                                    'package_type' => 1,
                                    'dept_type' => 'P',
                                    'goods_id' => $key,
                                    'sort_type_id' => $k
                                ])->orderBy(['id' => SORT_DESC])->asArray()->one();
                                if ($lastRec) {
                                    $remain = $value['quantity'] - $lastRec['inventory'];
                                    $modelTPIB = new TikuvPackageItemBalance([
                                        'goods_id' => $key,
                                        'dept_type' => 'P',
                                        'package_type' => 1,
                                        'department_id' => $lastRec['department_id'],
                                        'nastel_no' => $data['nastel'],
                                        'count' => $remain,
                                        'inventory' => $value['quantity'],
                                        'doc_type' => $lastRec['doc_type'],
                                        'model_list_id' => $data['model_id'],
                                        'model_var_id' => $data['model_var'],
                                        'sort_type_id' => $lastRec['sort_type_id'],
                                        'from_department' => $lastRec['from_department'],
                                        'order_id' => $data['order_id'],
                                        'order_item_id' => $data['order_item_id'],
                                        'is_main_barcode' => $lastRec['is_main_barcode'],
                                        'brand_type' => $lastRec['brand_type'],
                                        'from_musteri' => $lastRec['from_musteri'],
                                        'to_musteri' => $lastRec['to_musteri'],
                                        'barcode_customer_id' => $lastRec['barcode_customer_id'],
                                    ]);
                                    if ($modelTPIB->save()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        $res = [
                                            'message' => 'LastRec saqlanmadi',
                                            'item' => $value,
                                            'key' => $key,
                                            'error' => $modelTPIB->getErrors()
                                        ];
                                        Yii::info($res, 'save');
                                        break;
                                    }
                                } else {
                                    $lastNastel = TikuvPackageItemBalance::find()->where([
                                        'nastel_no' => $value['nastel_no'],
                                        'department_id' => 16,
                                        'package_type' => 1,
                                        'dept_type' => 'P',
                                        'goods_id' => $key,
                                        'sort_type_id' => $k
                                    ])->orderBy(['id' => SORT_DESC])->asArray()->one();
                                    if ($lastNastel) {
                                        $remain = $value['quantity'] - $lastNastel['inventory'];
                                        $modelTPIB2 = new TikuvPackageItemBalance([
                                            'goods_id' => $key,
                                            'dept_type' => 'P',
                                            'package_type' => 1,
                                            'department_id' => $lastNastel['department_id'],
                                            'nastel_no' => $data['nastel'],
                                            'count' => $remain,
                                            'inventory' => $value['quantity'],
                                            'doc_type' => $lastNastel['doc_type'],
                                            'model_list_id' => $data['model_id'],
                                            'model_var_id' => $data['model_var'],
                                            'sort_type_id' => $lastNastel['sort_type_id'],
                                            'from_department' => $lastNastel['from_department'],
                                            'order_id' => $data['order_id'],
                                            'order_item_id' => $data['order_item_id'],
                                            'is_main_barcode' => $lastNastel['is_main_barcode'],
                                            'brand_type' => $lastNastel['brand_type'],
                                            'from_musteri' => $lastNastel['from_musteri'],
                                            'to_musteri' => $lastNastel['to_musteri'],
                                            'barcode_customer_id' => $lastNastel['barcode_customer_id'],
                                        ]);
                                        if ($modelTPIB2->save()) {
                                            $saved = true;
                                        } else {
                                            $saved = false;
                                            $res = [
                                                'message' => 'LastNastel saqlanmadi',
                                                'item' => $value,
                                                'key' => $key,
                                                'error' => $modelTPIB2->getErrors()
                                            ];
                                            Yii::info($res, 'save');
                                            break;
                                        }
                                    } else {
                                        $saved = false;
                                        $res = [
                                            'message' => 'LastNastel topilmadi',
                                            'item' => $value,
                                            'key' => $key
                                        ];
                                        Yii::info($res, 'save');
                                        break;
                                    }
                                }
                            }
                        }
                        if ($saved && !empty($data['remove'])) {
                            foreach ($data['remove'] as $remove_list) {
                                foreach ($remove_list as $key => $remove) {
                                    foreach ($remove as $r => $n) {
                                        $lastRemove = TikuvPackageItemBalance::find()->where([
                                            'nastel_no' => $n['nastel_no'],
                                            'department_id' => 16,
                                            'package_type' => 1,
                                            'dept_type' => 'P',
                                            'goods_id' => $key,
                                            'sort_type_id' => $r,
                                        ])->orderBy(['id' => SORT_DESC])->asArray()->one();
                                        if ($lastRemove) {
                                            /*$inv = $lastRemove['inventory'] - $n['quantity'];
                                            if($inv < 0){
                                                $inv = 0;
                                            }*/
                                            $modelTPIB2 = new TikuvPackageItemBalance([
                                                'goods_id' => $key,
                                                'dept_type' => 'P',
                                                'package_type' => 1,
                                                'department_id' => $lastRemove['department_id'],
                                                'nastel_no' => $n['nastel_no'],
                                                'count' => -1 * $n['quantity'],
                                                'inventory' => 0,
                                                'doc_type' => $lastRemove['doc_type'],
                                                'model_list_id' => $lastRemove['model_list_id'],
                                                'model_var_id' => $lastRemove['model_var_id'],
                                                'sort_type_id' => $lastRemove['sort_type_id'],
                                                'from_department' => $lastRemove['from_department'],
                                                'order_id' => $lastRemove['order_id'],
                                                'order_item_id' => $lastRemove['order_item_id'],
                                                'is_main_barcode' => $lastRemove['is_main_barcode'],
                                                'brand_type' => $lastRemove['brand_type'],
                                                'from_musteri' => $lastRemove['from_musteri'],
                                                'to_musteri' => $lastRemove['to_musteri'],
                                                'barcode_customer_id' => $lastRemove['barcode_customer_id'],
                                            ]);
                                            if ($modelTPIB2->save()) {
                                                $nastelCombineList = new NastelCombineList([
                                                    'parent' => $data['nastel'],
                                                    'child' => $n['nastel_no'],
                                                    'add_info' => $data['nastel_list'],
                                                    'quantity' => $n['quantity'],
                                                    'remain' => $lastRemove['inventory']
                                                ]);
                                                $nastelCombineList->save(false);
                                                $saved = true;
                                            } else {
                                                $saved = false;
                                                $res = [
                                                    'message' => 'LastRemove saqlanmadi',
                                                    'item' => $n,
                                                    'key' => $key,
                                                    'error' => $modelTPIB2->getErrors()
                                                ];
                                                Yii::info($res, 'save');
                                                break;
                                            }
                                        } else {
                                            $saved = false;
                                            $res = [
                                                'message' => 'LastRemove topilmadi',
                                                'item' => $n,
                                                'key' => $key
                                            ];
                                            Yii::info($res, 'save');
                                            break 2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($saved) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                    } else {
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    Yii::info('Not saved' . $e, 'save');
                    $transaction->rollBack();
                }
            }
            return $this->render('combine', [
                'nastel' => []
            ]);
        }
    }

    /**
     * Displays a single TikuvGoodsDocPack model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new TikuvGoodsDocPackSearch();
        $dataProvider = $searchModel->searchView($id);


        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new TikuvGoodsDocPack model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $i = Yii::$app->request->get('i', 1);
        $floor = Yii::$app->request->get('floor', 2);
        $data = Yii::$app->request->post();
            
        if (Yii::$app->request->isPost && empty($data['TikuvGoodsDoc'])) {
            return $this->redirect(['index', 'i' => $i, 'floor' => $floor]);
        }
        $model = new TikuvGoodsDocPack();
        $model->reg_date = date('d.m.Y');
        $docNumber = TikuvGoodsDocPack::find()->asArray()->orderBy(['id' => SORT_DESC])->one();
        $currentDate = date('Y');
        $model->doc_number = "TK1/{$currentDate}";

        if (!empty($docNumber)) {
            $index = $docNumber['id'] + 1;
            $model->doc_number = "TK{$index}/{$currentDate}";
        }
        $models = [new TikuvGoodsDoc()];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $saved = false;
            if ($model->load($data) && $model->save()) {
                if (!empty($data['TikuvGoodsDoc'])) {
                    $modelId = $model->id;
                    foreach ($data['TikuvGoodsDoc'] as $key => $item) {
                        $mTGD = new TikuvGoodsDoc();
                        if ($item['quantity'] > 0) {
                            $mTGD->setAttributes([
                                'goods_id' => $item['goods_id'],
                                'quantity' => $item['quantity'],
                                'tgdp_id' => $modelId,
                                'weight' => $item['weight'],
                                'unit_id' => $item['unit_id'],
                                'sort_type_id' => $item['sort_type_id'],
                                'package_type' => $item['package_type'],
                                'barcode' => $item['barcode'],
                                'barcode_customer_id' => $item['barcode_customer_id'],
                            ]);
                            if ($mTGD->save()) {
                                $saved = true;
                            } else {
                                $saved = false;
                                break;
                            }
                        }
                    }
                }
            }
            if ($saved) {
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id, 'i' => $i, 'floor' => $floor]);
            } else {
                $transaction->rollBack();
            }
        } catch (\Exception $e) {
            Yii::info("Not saved {$e->getMessage()}", 'save');
        }
        return $this->render('create', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id)
    {
        $i = Yii::$app->request->get('i', 1);
        $floor = Yii::$app->request->get('floor', 2);
        $model = $this->findModel($id);

        if (!empty($model->tikuvGoodsDocs)) {
            $models = $model->tikuvGoodsDocs;
        } else {
            $models = [new TikuvGoodsDoc()];
        }
        $data = Yii::$app->request->post();
        if (Yii::$app->request->isPost && empty($data['TikuvGoodsDoc'])) {
            return $this->redirect(['index', 'i' => $i, 'floor' => $floor]);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $saved = false;

            if ($model->load($data) && $model->save()) {
                if (!empty($model->tikuvGoodsDocs)) {
                    foreach ($model->tikuvGoodsDocs as $item) {
                        if ($item->delete()) {
                            $saved = true;
                        } else {
                            $saved = false;
                            break;
                        }
                    }
                }
                if (!empty($data['TikuvGoodsDoc']) && $saved) {
                    $modelId = $model->id;
                    foreach ($data['TikuvGoodsDoc'] as $key => $item) {
                        $mTGD = new TikuvGoodsDoc();
                        if ($item['quantity'] > 0) {
                            $mTGD->setAttributes([
                                'goods_id' => $item['goods_id'],
                                'quantity' => $item['quantity'],
                                'tgdp_id' => $modelId,
                                'weight' => $item['weight'],
                                'unit_id' => $item['unit_id'],
                                'sort_type_id' => $item['sort_type_id'],
                                'package_type' => $item['package_type'],
                                'barcode' => $item['barcode'],
                                'barcode_customer_id' => $item['barcode_customer_id'],
                            ]);
                            if ($mTGD->save()) {
                                $saved = true;
                            } else {
                                $saved = false;
                                break;
                            }
                        }
                    }
                }
            }
            if ($saved) {
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id, 'i' => $i, 'floor' => $floor]);
            } else {
                $transaction->rollBack();
            }
        } catch (\Exception $e) {
            Yii::info("Not saved {$e->getMessage()}", 'save');
            $transaction->rollBack();
        }
        return $this->render('update', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionSaveAndFinish($id)
    {
        $i = Yii::$app->request->get('i', 1); //TODO: $i nimani bildiradi
        $floor = Yii::$app->request->get('floor', 2);
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $saved = false;
            $items = $model->getTikuvGoodsDocs()->asArray()->all();
            $modelVarId = $model->model_var_id;
            $modelListId = $model->model_list_id;
            $nastelNo = $model->nastel_no;
            $fromDepartment = $model->from_department;
            $orderId = $model->order_id;
            $orderItemId = $model->order_item_id;
            $brandType = $model->brand_type;
            $currentDept = $model->department_id;
            $fromMusteri = $model->from_musteri;
            $barcodeCustomerId = $model->barcode_customer_id;
            if (!empty($items) && $i == 2) {
                $toDepartment = $model->to_department;
                $deptWarehouse = ToquvDepartments::find()->select(['id'])->where(['token' => $toDepartment])->asArray()->one();
                foreach ($items as $key => $modelItem) {
                    $lastRecord = TikuvPackageItemBalance::find()->where([
                        'model_list_id' => $modelListId,
                        'model_var_id' => $modelVarId,
                        'nastel_no' => $nastelNo,
                        'goods_id' => $modelItem['goods_id'],
                        'dept_type' => 'TW',
                        'order_id' => $orderId,
                        'order_item_id' => $orderItemId,
                        'barcode_customer_id' => $modelItem['barcode_customer_id'],
                        'sort_type_id' => $modelItem['sort_type_id'],
                    ])->select(['inventory'])->asArray()->orderBy(['id' => SORT_DESC])->one();
                    if (!empty($lastRecord)) {
                        if ($modelItem['quantity'] > $lastRecord['inventory']) {
                            Yii::$app->session->setFlash('error', Yii::t('app', 'Yetarli qoldiq mavjud emas!'));
                            return $this->redirect(['view', 'id' => $id, 'i' => $i, 'floor' => $floor]);
                        }
                    } else {
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Yetarli qoldiq mavjud emas!'));
                        return $this->redirect(['view', 'id' => $id, 'i' => $i, 'floor' => $floor]);
                    }
                    $modelOutcomePIB = new TikuvPackageItemBalance();
                    $inventoryPackageP = $lastRecord['inventory'] - $modelItem['quantity'];
                    $modelOutcomePIB->setAttributes([
                        'goods_id' => $modelItem['goods_id'],
                        'count' => (-1) * (int)$modelItem['quantity'],
                        'inventory' => (int)$inventoryPackageP,
                        'from_department' => $fromDepartment,
                        'from_musteri' => $fromMusteri,
                        'nastel_no' => $nastelNo,
                        'doc_type' => 2,
                        'dept_type' => 'TW',
                        'department_id' => $currentDept,
                        'to_department' => $toDepartment,
                        'model_list_id' => $modelListId,
                        'model_var_id' => $modelVarId,
                        'sort_type_id' => $modelItem['sort_type_id'],
                        'package_type' => $modelItem['package_type'],
                        'order_id' => $orderId,
                        'order_item_id' => $orderItemId,
                        'barcode_customer_id' => $modelItem['barcode_customer_id'],
                        'is_main_barcode' => $modelItem['barcode']
                    ]);

                    $lastRecordAPI = TikuvPackageItemBalance::find()->where([
                        'model_list_id' => $modelListId,
                        'model_var_id' => $modelVarId,
                        'nastel_no' => $nastelNo,
                        'goods_id' => $modelItem['goods_id'],
                        'dept_type' => 'API',
                        'department_id' => $deptWarehouse['id'],
                        'order_id' => $orderId,
                        'order_item_id' => $orderItemId,
                        'barcode_customer_id' => $modelItem['barcode_customer_id'],
                        'sort_type_id' => $modelItem['sort_type_id'],
                    ])->select(['inventory'])->asArray()->orderBy(['id' => SORT_DESC])->one();
                    $inventoryPackageAPI = $modelItem['quantity'];
                    if (!empty($lastRecordAPI)) {
                        $inventoryPackageAPI = $lastRecordAPI['inventory'] + $modelItem['quantity'];
                    }
                    $modelAPI = new TikuvPackageItemBalance();
                    $modelAPI->setAttributes([
                        'goods_id' => $modelItem['goods_id'],
                        'count' => (int)$modelItem['quantity'],
                        'inventory' => (int)$inventoryPackageAPI,
                        'from_department' => $fromDepartment,
                        'from_musteri' => $fromMusteri,
                        'nastel_no' => $nastelNo,
                        'doc_type' => 1,
                        'dept_type' => 'AP',
                        'department_id' => $deptWarehouse['id'],
                        'model_list_id' => $modelListId,
                        'model_var_id' => $modelVarId,
                        'sort_type_id' => $modelItem['sort_type_id'],
                        'package_type' => $modelItem['package_type'],
                        'order_id' => $orderId,
                        'order_item_id' => $orderItemId,
                        'barcode_customer_id' => $modelItem['barcode_customer_id'],
                        'is_main_barcode' => $modelItem['barcode']
                    ]);
                    if ($modelOutcomePIB->save() && $modelAPI->save()) {
                        $saved = true;
                    } else {
                        $saved = false;
                        break;
                    }
                }
            } else {
                $saved = true;
                if (!empty($items)) {
                    $goodsId = [];
                    foreach ($items as $modelItem) {
                        $goodsId[] = $modelItem['goods_id'];
                        $lastRecordPackageTW = TikuvPackageItemBalance::find()->where([
                            'goods_id' => $modelItem['goods_id'],
                            'model_var_id' => $modelVarId,
                            'model_list_id' => $modelListId,
                            'nastel_no' => $nastelNo,
                            'sort_type_id' => $modelItem['sort_type_id'],
                            'department_id' => $currentDept,
                            /*'from_department' => $fromDepartment,*/
                            'dept_type' => 'TW',
                            'package_type' => $modelItem['package_type'],
                            'order_id' => $orderId,
                            'order_item_id' => $orderItemId,
                            'barcode_customer_id' => $modelItem['barcode_customer_id']
                        ])->orderBy(['id' => SORT_DESC])->asArray()->one();
                        //Add if exist else create new raw
                        $inventoryPackageTW = $modelItem['quantity'];
                        if (!empty($lastRecordPackageTW)) {
                            $inventoryPackageTW += $lastRecordPackageTW['inventory'];
                        }
                        $modelTPIB_TW = new TikuvPackageItemBalance();
                        $modelTPIB_TW->setAttributes([
                            'goods_id' => $modelItem['goods_id'],
                            'count' => (int)$modelItem['quantity'],
                            'inventory' => (int)$inventoryPackageTW,
                            'from_department' => $fromDepartment,
                            'from_musteri' => $fromMusteri,
                            'nastel_no' => $nastelNo,
                            'doc_type' => 1,
                            'dept_type' => 'TW',
                            'department_id' => $currentDept,
                            'model_list_id' => $modelListId,
                            'model_var_id' => $modelVarId,
                            'sort_type_id' => $modelItem['sort_type_id'],
                            'package_type' => $modelItem['package_type'],
                            'order_id' => $orderId,
                            'order_item_id' => $orderItemId,
                            'barcode_customer_id' => $modelItem['barcode_customer_id'],
                            'is_main_barcode' => $modelItem['barcode']
                        ]);
                        if ($modelTPIB_TW->save()) {
                            $saved = true;
                        } else {
                            $saved = false;
                            break;
                        }
                    }
                    if (!empty($goodsId)) {
                        $ids = join(',', $goodsId);
                        $sql = "select g.id as g1_id,
                                   tgd.quantity,
                                   tgd.sort_type_id,
                                   g.type as g1_type,
                                   gi.quantity as g1_qty,
                                   g2.id as g2_id,
                                   g2.type as g2_type,
                                   gi2.quantity as g2_qty,
                                   g3.id as g3_id,
                                   g3.type as g3_type,
                                   gi3.quantity as g3_qty,
                                   g4.id as g4_id,
                                   g4.type as g4_type from goods g
                            left join tikuv_goods_doc tgd on g.id = tgd.goods_id
                            left join goods_items gi on gi.parent = g.id
                            left join goods g2 on g2.id = gi.child
                            left join goods_items gi2 on gi2.parent = g2.id
                            left join goods g3 on gi2.child = g3.id
                            left join goods_items gi3 on gi3.parent = g3.id
                            left join goods g4 on gi3.child = g4.id
                            left join goods_items gi4 on gi4.parent = g4.id
                            where g.id IN (%s) AND tgd.tgdp_id = %d;";
                        $sql = sprintf($sql, $ids, $id);
                        $result = Yii::$app->db->createCommand($sql)->queryAll();
                        $dataGoods = [];
                        foreach ($result as $m) {
                            if (!empty($m['g1_id']) && $m['g1_type'] > 1) {
                                $qty = $m['g1_qty'] * $m['quantity'];
                                if (!empty($m['g2_id']) && $m['g2_type'] > 1) {
                                    $qty = $qty * $m['g2_qty'];
                                    if (!empty($m['g3_id']) && $m['g3_type'] > 1) {
                                        if (array_key_exists(($m['g4_id'] . "-" . $m['sort_type_id']), $dataGoods)) {
                                            $dataGoods[$m['g4_id'] . "-" . $m['sort_type_id']]['quantity'] += $qty * $m['g3_qty'];
                                        } else {
                                            $dataGoods[$m['g4_id'] . "-" . $m['sort_type_id']] = [
                                                'quantity' => $qty * $m['g3_qty'],
                                                'sort_type_id' => $m['sort_type_id'],
                                                'id' => $m['g4_id']
                                            ];
                                        }
                                    } else {
                                        if (array_key_exists(($m['g3_id'] . "-" . $m['sort_type_id']), $dataGoods)) {
                                            $dataGoods[$m['g3_id'] . "-" . $m['sort_type_id']]['quantity'] += $qty;
                                        } else {
                                            $dataGoods[$m['g3_id'] . "-" . $m['sort_type_id']] = [
                                                'quantity' => $qty,
                                                'sort_type_id' => $m['sort_type_id'],
                                                'id' => $m['g3_id']
                                            ];
                                        }
                                    }
                                } else {
                                    if (array_key_exists(($m['g2_id'] . "-" . $m['sort_type_id']), $dataGoods)) {
                                        $dataGoods[$m['g2_id'] . "-" . $m['sort_type_id']]['quantity'] += $m['quantity'];
                                    } else {
                                        $dataGoods[$m['g2_id'] . "-" . $m['sort_type_id']] = [
                                            'quantity' => $qty,
                                            'sort_type_id' => $m['sort_type_id'],
                                            'id' => $m['g2_id']
                                        ];
                                    }
                                }
                            } else {
                                if (array_key_exists(($m['g1_id'] . "-" . $m['sort_type_id']), $dataGoods)) {
                                    $dataGoods[$m['g1_id'] . "-" . $m['sort_type_id']]['quantity'] += $m['quantity'];
                                } else {
                                    $dataGoods[$m['g1_id'] . "-" . $m['sort_type_id']] = [
                                        'quantity' => $m['quantity'],
                                        'sort_type_id' => $m['sort_type_id'],
                                        'id' => $m['g1_id']
                                    ];
                                }
                            }
                        }
                        if (!empty($dataGoods)) {
                            foreach ($dataGoods as $key => $modelItem) {
                                $goodsId = $modelItem['id'];
                                $params = [
                                    'goods_id' => $goodsId,
                                    'model_var_id' => $modelVarId,
                                    'model_list_id' => $modelListId,
                                    'nastel_no' => $nastelNo,
                                    'sort_type_id' => $modelItem['sort_type_id'],
                                    'department_id' => $currentDept,
                                    /*'from_department' => $fromDepartment,*/
                                    'dept_type' => 'P',
                                    'order_id' => $orderId,
                                    'order_item_id' => $orderItemId,
                                    'barcode_customer_id' => $barcodeCustomerId
                                ];
                                $lastRecordPackageP = TikuvPackageItemBalance::find()->where($params)->orderBy(['id' => SORT_DESC])->asArray()->one();
                                if (!empty($lastRecordPackageP)) {
                                    if ($modelItem['quantity'] > $lastRecordPackageP['inventory']) {
                                        $goods = Goods::findOne($lastRecordPackageP['goods_id']);
                                        Yii::$app->session->setFlash('error', Yii::t('app', '{size} dan {count} yetmayapti!', [
                                            'size' => $goods->size0->name,
                                            'count' => $modelItem['quantity'] - $lastRecordPackageP['inventory']
                                        ]));
                                        $res = [];
                                        $res['message'] = Yii::t('app', '{size} dan {count} yetmayapti!', [
                                            'size' => $goods->size0->name,
                                            'count' => $modelItem['quantity'] - $lastRecordPackageP['inventory']
                                        ]);
                                        $res['sql_goods'] = $sql;
                                        $res['data_goods'] = $dataGoods;
                                        $sql = TikuvPackageItemBalance::find()->where([
                                            'goods_id' => $goodsId,
                                            'model_var_id' => $modelVarId,
                                            'model_list_id' => $modelListId,
                                            'nastel_no' => $nastelNo,
                                            'sort_type_id' => $modelItem['sort_type_id'],
                                            'department_id' => $currentDept,
                                            'dept_type' => 'P',
                                            'order_id' => $orderId,
                                            'order_item_id' => $orderItemId,
                                            'barcode_customer_id' => $barcodeCustomerId
                                        ])->orderBy(['id' => SORT_DESC]);
                                        $res["sql"] = "{$sql->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql}";
                                        $res['list'] = [
                                            'goods_id' => $goodsId,
                                            'model_var_id' => $modelVarId,
                                            'model_list_id' => $modelListId,
                                            'nastel_no' => $nastelNo,
                                            'sort_type_id' => $modelItem['sort_type_id'],
                                            'department_id' => $currentDept,
                                            'from_department' => $fromDepartment,
                                            'dept_type' => 'P',
                                            'order_id' => $orderId,
                                            'order_item_id' => $orderItemId,
                                            'barcode_customer_id' => $barcodeCustomerId
                                        ];
                                        new Telegram([
                                            'text' => $res['message'] . ' #sql_goods ' . $res["sql_goods"] . ' #goods' . json_encode($res["list"]) . ' #sql ' . $res["sql"] . " 956",
                                        ]);
                                        new Telegram([
                                            'text' => $res['message'] . ' #sql_goods ' . $res["sql_goods"] . ' #goods' . json_encode($res["list"]) . ' #sql ' . $res["sql"],
                                            'id' => 64520993
                                        ]);
                                        $res['goods'] = $goods ? $goods->toArray() : $goods;
                                        Yii::info($res, 'save');
                                        return $this->redirect(['view', 'id' => $id, 'i' => $i, 'floor' => $floor]);
                                    }
                                } else {
                                    $lastRecordPackageP = TikuvPackageItemBalance::find()->where([
                                        'goods_id' => $goodsId,
                                        'model_var_id' => $modelVarId,
                                        'model_list_id' => $modelListId,
                                        'nastel_no' => $nastelNo,
                                        'sort_type_id' => $modelItem['sort_type_id'],
                                        'department_id' => $currentDept,
                                        'dept_type' => 'P',
                                        'order_id' => $orderId,
                                        'order_item_id' => $orderItemId,
                                        'barcode_customer_id' => $barcodeCustomerId
                                    ])->orderBy(['id' => SORT_DESC]);
                                    $res = [];
                                    $res['message'] = 'Tikuv qoldiq topilmadi';
                                    $res['sql_goods'] = $sql;
                                    $res['data_goods'] = $dataGoods;
                                    $res["sql"] = "{$lastRecordPackageP->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql}";
                                    $res['list'] = [
                                        'goods_id' => $goodsId,
                                        'model_var_id' => $modelVarId,
                                        'model_list_id' => $modelListId,
                                        'nastel_no' => $nastelNo,
                                        'sort_type_id' => $modelItem['sort_type_id'],
                                        'department_id' => $currentDept,
                                        'from_department' => $fromDepartment,
                                        'dept_type' => 'P',
                                        'order_id' => $orderId,
                                        'order_item_id' => $orderItemId,
                                        'barcode_customer_id' => $barcodeCustomerId
                                    ];
                                    new Telegram([
                                        'text' => $res["sql_goods"] . ' #goods' . json_encode($res["list"] . ' #sql ' . $res["sql"]) . " 999",
                                    ]);
                                    new Telegram([
                                        'text' => $res["sql_goods"] . ' #goods' . json_encode($res["list"] . ' #sql ' . $res["sql"]),
                                        'id' => 64520993
                                    ]);
                                    Yii::info($res, 'save');
                                    Yii::$app->session->setFlash('error', Yii::t('app', 'Omborda mahsulot topilmadi!'));
                                    return $this->redirect(['view', 'id' => $id, 'i' => $i, 'floor' => $floor]);
                                }
                                if (!empty($currentDept)) {
                                    //decrement from production
                                    if (!empty($lastRecordPackageP) && $lastRecordPackageP['inventory'] > 0) {
                                        $inventoryPackageP = $lastRecordPackageP['inventory'] - $modelItem['quantity'];
                                        $modelTPIB_P = new TikuvPackageItemBalance();
                                        $modelTPIB_P->setAttributes([
                                            'goods_id' => $goodsId,
                                            'count' => (-1) * (int)$modelItem['quantity'],
                                            'inventory' => (int)$inventoryPackageP,
                                            'from_department' => $fromDepartment,
                                            'from_musteri' => $fromMusteri,
                                            'nastel_no' => $nastelNo,
                                            'doc_type' => 2,
                                            'dept_type' => 'P',
                                            'department_id' => $currentDept,
                                            'model_list_id' => $modelListId,
                                            'model_var_id' => $modelVarId,
                                            'sort_type_id' => $modelItem['sort_type_id'],
                                            'order_id' => $orderId,
                                            'order_item_id' => $orderItemId,
                                            'barcode_customer_id' => $barcodeCustomerId,
                                            'is_main_barcode' => $lastRecordPackageP['is_main_barcode']
                                        ]);
                                        if ($modelTPIB_P->save()) {
                                            $saved = true;
                                        } else {
                                            $saved = false;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $model->status = 3;
            if(!$model->save()){
                $saved = false;
            }
            if ($saved) {
                if ($i == 2) {
                    $saved = $model->sendToAPI($model->id);
                    $model->makeWmsDocumentForTmo($model->id);
                    if (!$saved) {
                        Yii::$app->session->setFlash('info', "Ombor bilan aloqa uzilgan. Iltimos 20 soniyadan so'ng qayta harakat qilib ko'ring");
                    }
                }
                if ($saved) {
                    $transaction->commit();
                }
            } else {
                $transaction->rollBack();
            }

        } catch (\Exception $e) {
            Yii::info("Not saved {$e->getMessage()}", 'save');
        }
        return $this->redirect(['view', 'id' => $id, 'i' => $i, 'floor' => $floor]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $i = Yii::$app->request->get('i', 1);
        $floor = Yii::$app->request->get('floor', 1);
        $model = $this->findModel($id);
        if (!empty($model->tikuvGoodsDocs)) {
            foreach ($model->tikuvGoodsDocs as $item) {
                $item->delete();
            }
        }
        $model->delete();
        return $this->redirect(['index', 'i' => $i, 'floor' => $floor]);
    }

    public function actionNastelList()
    {
        $q = Yii::$app->request->get('q', '');
        $deptId = Yii::$app->request->get('deptId', '');
        $dept = Yii::$app->request->get('dept', '');
        $deptType = Yii::$app->request->get('dept_type', 'P');
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (empty($q) || (empty($dept) && $deptType == 'P')) {
            return $out['results'] = ['id' => '', 'text' => ''];
        }
        $results = TikuvGoodsDocPack::getModelListWithNastel($q, $dept, $deptId, $deptType, false);
        $out = [];
        foreach ($results as $key => $result) {
            $out['results'][$key] = [
                'id' => $result['nastel_no'],
                'text' => "({$result['brand']}) {$result['nastel_no']} ({$result['article']} {$result['code']}) (Jami: {$result['sum']})",
                'data_model_id' => $result['model_id'],
                'data_nastel_no' => $result['nastel_no'],
                'data_model_var_id' => $result['model_var_id'],
                'data_order_id' => $result['order_id'],
                'data_order_item_id' => $result['order_item_id'],
                'data_brand_type' => $result['brand_type'],
                'data_brand' => $result['brand'],
                'data_brand_id' => $result['brandId']
            ];
        }
        return $out;
    }

    public function actionNastelOthers()
    {
        $musteri = Yii::$app->request->post('musteri', '');
        $nastel_no = Yii::$app->request->post('nastel_no', '');
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (empty($musteri) && empty($nastel_no)) {
            return $out['results'] = ['id' => '', 'text' => ''];
        }
        $results = TikuvGoodsDocPack::getModelListWithOthers($musteri, $nastel_no, false);
        $out = [];
        $brand = Constants::$brandSAMO;
        $brandId = null;
        $brandSAMO = Brend::find()->select(['id'])->where(['token' => $brand])->asArray()->one();
        if (!empty($brandSAMO)) {
            $brandId = $brandSAMO['id'];
        }
        if (!empty($nastel_no)) {
            foreach ($results as $key => $result) {
                if ($result['brand_type'] == 2) {
                    $brand = $result['brand2'];
                    $brandId = $result['brand_id2'];
                } elseif ($result['brand_type'] == 3) {
                    $brand = $result['brand3'];
                    $brandId = $result['brand_id3'];
                }
                $out['results'][] = [
                    'id' => $result['model_var_id'],
                    'text' => "({$brand}) {$result['size']} ({$result['article']} {$result['code']}) (Jami: {$result['sum']})",
                    'data_model_id' => $result['model_id'],
                    'data_nastel_no' => $result['nastel_no'],
                    'data_model_var_id' => $result['model_var_id'],
                    'data_order_id' => $result['order_id'],
                    'data_order_item_id' => $result['order_item_id'],
                    'data_brand_type' => $result['brand_type'],
                    'data_brand' => $brand,
                    'data_brand_id' => $brandId,
                    'data_size_id' => $result['size_id'],
                    'data_size_type_id' => $result['size_type_id'],
                ];
            }
        } else {
            foreach ($results as $key => $result) {
                if ($result['brand_type'] == 2) {
                    $brand = $result['brand2'];
                    $brandId = $result['brand_id2'];
                } elseif ($result['brand_type'] == 3) {
                    $brand = $result['brand3'];
                    $brandId = $result['brand_id3'];
                }
                $out['results'][] = [
                    'id' => $result['model_var_id'],
                    'text' => "({$brand}) {$result['nastel_no']} ({$result['article']} {$result['code']}) (Jami: {$result['sum']})",
                    'data_model_id' => $result['model_id'],
                    'data_nastel_no' => $result['nastel_no'],
                    'data_model_var_id' => $result['model_var_id'],
                    'data_order_id' => $result['order_id'],
                    'data_order_item_id' => $result['order_item_id'],
                    'data_brand_type' => $result['brand_type'],
                    'data_brand' => $brand,
                    'data_brand_id' => $brandId,
                    'data_color' => "{$result['code']} {$result['pantone']}"
                ];
            }
        }
        return $out;
    }

    /**
     * @return array
     */
    public function actionGoodsItems()
    {

        Yii::$app->response->format = Response::FORMAT_JSON;

        $modelVarId = Yii::$app->request->get('modelVar');
        $modelId = Yii::$app->request->get('modelId');
        $nastelNo = Yii::$app->request->get('nastelNo');
        $brandType = Yii::$app->request->get('brandType');

        $response = [];
        $response['status'] = false;

        if (!empty($modelId) && !empty($modelVarId) && !empty($nastelNo) && !empty($brandType)) {
            $out = TikuvGoodsDoc::getGoodsItemsForTabular($nastelNo, $modelId, $modelVarId, $brandType);
            $response['status'] = true;
            $response['items'] = $out;
        }
        return $response;
    }

    public function actionExportExcel()
    {
        header('Content-Type: application/vnd.ms-excel');
        $filename = "tikuv-goods-doc-pack_" . date("d-m-Y-His") . ".xls";
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => TikuvGoodsDocPack::find()->select([
                'id',
            ])->all(),
            'columns' => [
                'id',
            ],
            'headers' => [
                'id' => '№',
                'article' => 'Артикул',
            ],
            'autoSize' => true,
        ]);
    }


    /**
     * @param $q
     * @param $modelId
     * @param $modelVarId
     * @param $nastelNo
     * @param $brandTypeId
     * @param $type
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionAjaxRequest($q, $modelId, $modelVarId, $nastelNo, $brandTypeId, $type)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
        $params = [
            'q' => $q,
            'modelId' => $modelId,
            'modelVarId' => $modelVarId,
            'nastelNo' => $nastelNo,
            'brandTypeId' => $brandTypeId,
            'type' => $type
        ];
        $items = TikuvGoodsDocPack::searchAjax($params);
        return $items;

    }
    public function actionAjaxGetValues($q, $modelId, $modelVarId, $nastelNo, $brandTypeId, $type, $deptId){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $sabu = Yii::$app->request->get('sabu');
        $params = [
            'q_q' => $q,
            'modelId' => $modelId,
            'modelVarId' => $modelVarId,
            'nastelNo' => $nastelNo,
            'brandTypeId' => $brandTypeId,
            'type' => $type,
            'deptId' => $deptId,
            'sabu' => $sabu
        ];
        $items = TikuvGoodsDocPack::searchGetValues($params);
        return $items;
    }
    /**
     * @param $id
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionOrder($id)
    {
        $response = ['status' => false];
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response['status'] = true;
            $response['data'] = TikuvOutcomeProductsPack::getOrderList($id);
        }
        return $response;
    }

    /**
     * @param $order
     * @param $item
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionOrderItems($order, $item)
    {
        $sqlCheck = "select tgdp.id from tikuv_goods_doc_pack tgdp
                     WHERE tgdp.order_id = %d AND tgdp.order_item_id = %d AND tgdp.is_incoming = 2 AND tgdp.is_full = 2
                     GROUP BY tgdp.id;";
        $sqlCheck = sprintf($sqlCheck, $order, $item);
        $exists = Yii::$app->db->createCommand($sqlCheck)->queryScalar();
        $items = [];
        if (!$exists) {
            $sql = "select g.id as gid, 
                       SUM(tgd.quantity) as accepted,
                       (select SUM(t2.quantity) from tikuv_goods_doc_moving t2
                        WHERE t2.order_id = %d AND t2.order_item_id = %d AND g.id = t2.goods_id
                        GROUP BY t2.goods_id, t2.order_id, t2.order_item_id
                       ) as moving,
                       g.name, 
                       g.model_no, 
                       g.type, 
                       cp.code as color, 
                       s.name as sizeName,
                       tgd.weight,
                       u.name as unitName 
                from tikuv_goods_doc_pack tgdp
                         left join tikuv_goods_doc tgd on tgdp.id = tgd.tgdp_id
                         left join unit u on tgd.unit_id = u.id
                         left join goods g on tgd.goods_id = g.id
                         left join size s on g.size = s.id
                         left join color_pantone cp on g.color = cp.id
                         left join tikuv_goods_doc_moving t on g.id = t.goods_id
                where tgdp.is_incoming = 1 AND tgdp.is_full = 1 AND tgdp.order_id = %d AND tgdp.order_item_id = %d AND tgdp.status = 3
                GROUP BY g.id, tgdp.order_id, tgdp.order_item_id ORDER BY g.name, g.model_no, color, sizeName;";
            $sql = sprintf($sql, $order, $item, $order, $item);
            $items = Yii::$app->db->createCommand($sql)->queryAll();
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('order-items', [
                'items' => $items,
            ]);
        }
        return $this->render('order-items', [
            'items' => $items,
        ]);
    }
    /**
     * Finds the TikuvGoodsDocPack model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TikuvGoodsDocPack the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TikuvGoodsDocPack::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * @return Response
     * @throws NotFoundHttpException
     * Tayyor maxsulotlar omboridan qaytgan maxsulotlarni qayta qaubul qilish
     */
    public function actionAccept()
    {

        $getData = Yii::$app->request->get();
        $model = $this->findModel($getData['tgdp_id']);
        if( $model->acceptItemFromTmo($getData)){
            $findTikuvGoods = TikuvGoodsDocAcceptedByTmo::find()
                ->where([
                    'tgdp_id' => $getData['tgdp_id'],
                    'tgd_id' => $getData['tgd_id'],
                    'type' => $model::TYPE_CENCALLED,
                    'status' => $model::STATUS_ACTIVE
                ])->one();
            $findTikuvGoods->updateCounters(['status' => 2]);
            Yii::$app->session->setFlash('success',Yii::t('app','Saved Successfully'));
        }else{
            Yii::$app->session->setFlash('erroe',Yii::t('app','Saved Not Successfully'));
        }
        return $this->redirect(Yii::$app->request->referrer);

    }

    //TODO :: O'chirib tashlashimiz zarur
    public function actionQuantityToAccept(){
        $sql = "SELECT tgd.id FROM `tikuv_goods_doc` tgd 
        LEFT JOIN tikuv_goods_doc_pack tgdp ON tgd.tgdp_id = tgdp.id
        WHERE tgdp.status = 3 AND tgd.accepted_quantity = 0";
            $query = Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($query as $s){
                $tgd = TikuvGoodsDoc::findOne(['id' => $s['id']]);
                $tgd['quantity'] = intval($tgd['quantity']);
                $tgd['accepted_quantity'] =$tgd['quantity'];
                    if(!$tgd->save()){
                    }
            }
    }
    
}
