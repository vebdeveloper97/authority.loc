<?php

namespace app\modules\toquv\controllers;

use app\models\Users;
use app\modules\toquv\models\KaliteMatoForm;
use app\modules\toquv\models\MatoInfo;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvKaliteDefects;
use app\modules\toquv\models\ToquvKaliteDefectsSearch;
use app\modules\toquv\models\ToquvKaliteDeleted;
use app\modules\toquv\models\ToquvMakine;
use app\modules\toquv\models\ToquvMakineInfo;
use app\modules\toquv\models\ToquvMakineProcesses;
use app\modules\toquv\models\ToquvMatoItemBalance;
use Yii;
use app\modules\toquv\models\ToquvKalite;
use app\modules\toquv\models\ToquvKaliteSearch;
use yii\data\Pagination;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii2mod\editable\EditableAction;

/**
 * ToquvKaliteController implements the CRUD actions for ToquvKalite model.
 */
class ToquvKaliteController extends BaseController
{
    public function actions()
    {
        return [
            'change-defects' => [
                'class' => EditableAction::class,
                'modelClass' => ToquvKaliteDefects::class,
            ],
        ];
    }
    /**
     * @return array|string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        if(Yii::$app->request->post()){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $response['message'] = 'error';
            $model = new ToquvKalite();
            if ($result = $model->saveKalite(Yii::$app->request->post('Kalite'))){
                $one = ToquvKalite::getOneKalite($result->toquv_instruction_rm_id, null, null);
                if(!empty($one)){
                    $makine = ToquvMakineInfo::findOne(['toquv_makine_id'=>$result->toquv_makine_id]) ?? new ToquvMakineInfo();
                    $difference = $one['quantity'] - $one['summa'];
                    $diff = ($difference>0)?$difference:0;
                    $makine->setAttributes([
                        'toquv_makine_id' => $result->toquv_makine_id,
                        'toquv_instruction_rm_id' => $result->toquv_instruction_rm_id,
                        'musteri' => $one['musteri_id'],
                        'doc_number' => $one['doc_number'],
                        'mato' => $one['mato'],
                        'info' => $one['info'],
                        'order_quantity' => $one['quantity'],
                        'quantity' => $one['summa'],
                        'difference' => $diff,
                        'remain' => ($difference<0)?abs($difference):0,
                        'roll' => $one['roll'],
                        'add_info' => $result->toquvRawMaterials->getRawMaterialIp(', ', '.')
                    ]);
                    $makine->save();
                    if($difference<=0) {
                        ToquvMakineProcesses::updateAll(
                            [
                                'ended_at' => date('Y-m-d H:i:s'),
                                'ended_by' => Yii::$app->user->id,
                                'status' => 3
                            ],
                            [
                                'AND',
                                ['=', 'toquv_instruction_rm_id', $result->toquv_instruction_rm_id],
                                ['=', 'status', 1],
                            ]
                        );
                    }
                }
                $response['status'] = 0;
                $response['message'] = "Success";
                $response['quantity'] = $result->quantity;
                $response['result'] = $result->code;
                $response['sort'] = $result->sortName->name;
                $response['ip'] = $result->toquvInstructionRm->getIplar();
                $response['thread_length'] = $result->toquvInstructionRm['thread_length'];
                $response['makine'] = $result->toquvMakine['name'];
                $response['pus_fine'] = $result->toquvMakine->toquvPusFine['name'];
                $response['defects'] = $result->groupDefects;
                $response['difference'] = $diff ?? 'yoq';
                $tabel = (!empty($result->user->usersInfo['tabel']))?" T-".$result->user->usersInfo['tabel']:'';
                $response['toquvchi'] = $result->user->user_fio.$tabel;
            }
            return $response;
        }
        $makine = ToquvMakine::getMakine();
        return $this->render('index', [
            'row' => ToquvMakine::getUsersMakine(),
            'makine' => $makine,
        ]);
    }
    public function actionAjax($id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('ajax', [
                'model' => ToquvMakine::findOne($id),
            ]);
        }
        return $this->render('ajax', [
            'model' => ToquvMakine::findOne($id),
        ]);
    }
    public function actionKalite()
    {
        $searchModel = new ToquvKaliteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);
        $sum = ToquvKalite::getTotal($dataProvider->models,'quantity');
        return $this->render('kalite', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sum' => $sum
        ]);
    }
    public function actionReport()
    {
        $searchModel = new ToquvKaliteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);
        $sum = ToquvKalite::getTotal($dataProvider->models,'quantity') ?? 0;
        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sum' => $sum,
        ]);
    }

    /**
     * @return string
     */
    public function actionReportGroup()
    {
        $searchModel = new ToquvKaliteSearch();
        $dataProvider = $searchModel->searchGroup(Yii::$app->request->queryParams,1);
        $sum = ToquvKalite::getTotal($dataProvider->models,'quantity') ?? 0;
        $roll = ToquvKalite::getTotal($dataProvider->models,'count') ?? 0;
        return $this->render('report-group', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sum' => $sum,
            'roll' => $roll,
        ]);
    }

    public function actionReportMonth()
    {
        $searchModel = new ToquvKaliteSearch();
        $dataProvider = $searchModel->monthReport(Yii::$app->request->queryParams);
        $sum = ToquvKalite::getTotal($dataProvider->models,'quantity') ?? 0;
        $roll = ToquvKalite::getTotal($dataProvider->models,'count') ?? 0;
        return $this->render('report-month', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sum' => $sum,
            'roll' => $roll,
        ]);
    }

    public function actionChangeKalite()
    {
        if ($data = Yii::$app->request->post()){
            $one = ToquvKalite::findOne(['code' => $data['one']]);
            $mato_sklad_id = ToquvDepartments::findOne(['token'=>'TOQUV_MATO_SKLAD'])['id'];
            if(!$one){
                Yii::$app->session->setFlash('error', Yii::t("app","<b>{code}</b> kodli mato topilmadi", ['code' => $data['one']]));
            }elseif(!empty($data['kalite'])&&!empty($mato_sklad_id)){
                $items =explode(',', $data['kalite']);
                $no_changed_items = '';
                $changed_items = '';
                $flagIB = false;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($items as $item) {
                        $new_item = ToquvKalite::findOne(['code' => $item]);
                        if($new_item && !empty($new_item->toquv_instruction_rm_id)){
                            $new_model = new ToquvKaliteDeleted();
                            $new_model->attributes = $new_item->attributes;
                            $new_model->toquv_kalite_id = $new_item->id;
                            $ombor = ($new_item->status==3)? "Ombor holati ham o'zgartirildi.":"";
                            $fio = Yii::$app->user->identity->user_fio;
                            $new_model->info = "{$new_item->toquvRmOrder->id} buyurtma {$one->toquvRmOrder->id} buyurtmaga o'zgartirildi. {$ombor} O'zgartirdi {$fio}";
                            $new_model->save();
                            if($new_model->hasErrors()){
                                echo 'new_model saqlanmadi';
                                $flagIB = false;
                                \yii\helpers\VarDumper::dump($new_model->getErrors(),10,true);die;
                                break;
                            }
                            if ($new_item && $new_item->status == 3) {
                                if(Yii::$app->user->id!=1){
                                    return \yii\helpers\VarDumper::dump("{$item} kodli rulon omborga jo'natilgan, uni o'zgartira olmaysiz!!!",10,true);die;
                                }
                                $item_array = $new_item->toArray();
                                $mato = MatoInfo::findOne([
                                    'entity_id' => $new_item->toquvRmOrder->toquv_raw_materials_id,
                                    'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                                    'pus_fine_id' => $new_item->toquvInstructionRm->toquv_pus_fine_id,
                                    'thread_length' => $new_item->toquvInstructionRm->thread_length,
                                    'finish_en' => $new_item->toquvInstructionRm->finish_en,
                                    'finish_gramaj' => $new_item->toquvInstructionRm->finish_gramaj,
                                    'type_weaving' => $new_item->toquvInstructionRm->type_weaving,
                                    'musteri_id' => $new_item->toquvRmOrder->toquvOrders->musteri_id,
                                    'toquv_rm_order_id' => $new_item->toquv_rm_order_id,
                                ]);
                                if($mato){
                                    $ItemBalanceModel = new ToquvMatoItemBalance();
                                    $item_array['entity_id'] = $mato->id;
                                    $item_array['entity_type'] = ToquvDocuments::ENTITY_TYPE_MATO;
                                    $item_array['lot'] = $item_array['sort_name_id'];
                                    $item_array['department_id'] = $mato_sklad_id;
                                    $lastRec = ToquvMatoItemBalance::getLastRecordMoving($item_array);
                                    //tekwirish
                                    if (!empty($lastRec) && ($lastRec['inventory'] - $item_array['quantity']) >=0) {
                                        $attributesTIB['entity_id'] = $item_array['entity_id'];
                                        $attributesTIB['entity_type'] = $item_array['entity_type'];
                                        $attributesTIB['is_own'] = $item_array['is_own'];
                                        $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                        $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                        $attributesTIB['document_id'] = null;
                                        $attributesTIB['inventory'] = $lastRec['inventory'] - $item_array['quantity'];
                                        $attributesTIB['roll_inventory'] = $lastRec['roll_inventory'] - 1;
                                        $attributesTIB['lot'] = (string)$item_array['lot'];
                                        $attributesTIB['count'] = (-1)*$item_array['quantity'];
                                        $attributesTIB['roll_count'] = -1;
                                        $attributesTIB['department_id'] = $item_array['department_id'];;
                                        $attributesTIB['from_department'] = null;
                                        $attributesTIB['to_musteri'] = $new_item->toquvRmOrder->toquvOrders->musteri_id;
                                        $attributesTIB['document_type'] = 10;
                                        $attributesTIB['musteri_id'] = $item_array['musteri_id'];
                                        $attributesTIB['reg_date'] = date('Y-m-d H:i:s');
                                        $ItemBalanceModel->setAttributes($attributesTIB);
                                    }else {
                                        echo "LastRec yo'q yoki noldan past";
                                        \yii\helpers\VarDumper::dump($item_array,10,true);die;
                                        $flagIB = false;
                                        break;
                                    }
                                    if ($ItemBalanceModel->save()) {
                                        $flagIB = true;
                                    } else {
                                        echo "Itembalance saqlanmadi(-)";
                                        \yii\helpers\VarDumper::dump($ItemBalanceModel->getErrors(),10,true);die;
                                        $flagIB = false;
                                        break;
                                    }
                                }else{
                                    echo "Mato topilmadi";
                                    \yii\helpers\VarDumper::dump($item_array,10,true);die;
                                }
                            }
                            $send_user = $new_item->send_user_id ?? Yii::$app->user->id;
                            $new_item->setAttributes([
                                'toquv_rm_order_id' => $one->toquv_rm_order_id,
                                'toquv_instructions_id' => $one->toquv_instructions_id,
                                'toquv_instruction_rm_id' => $one->toquv_instruction_rm_id,
                                'toquv_raw_materials_id' => $one->toquv_raw_materials_id,
                                'send_user_id' => $send_user,
                                'updated_by' => Yii::$app->user->id
                            ]);
                            if($new_item->save()){
                                if ($new_item && $new_item->status == 3) {
                                    $item_array = $new_item->toArray();
                                    $mato = MatoInfo::findOne([
                                        'entity_id' => $new_item->toquvRmOrder->toquv_raw_materials_id,
                                        'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                                        'pus_fine_id' => $new_item->toquvInstructionRm->toquv_pus_fine_id,
                                        'thread_length' => $new_item->toquvInstructionRm->thread_length,
                                        'finish_en' => $new_item->toquvInstructionRm->finish_en,
                                        'finish_gramaj' => $new_item->toquvInstructionRm->finish_gramaj,
                                        'type_weaving' => $new_item->toquvInstructionRm->type_weaving,
                                        'musteri_id' => $new_item->toquvRmOrder->toquvOrders->musteri_id,
                                        'toquv_rm_order_id' => $new_item->toquv_rm_order_id,
                                    ]);
                                    if(!$mato){
                                        $mato = new MatoInfo([
                                            'entity_id' => $new_item->toquvRmOrder->toquv_raw_materials_id,
                                            'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                                            'pus_fine_id' => $new_item->toquvInstructionRm->toquv_pus_fine_id,
                                            'thread_length' => $new_item->toquvInstructionRm->thread_length,
                                            'finish_en' => $new_item->toquvInstructionRm->finish_en,
                                            'finish_gramaj' => $new_item->toquvInstructionRm->finish_gramaj,
                                            'type_weaving' => $new_item->toquvInstructionRm->type_weaving,
                                            'musteri_id' => $new_item->toquvRmOrder->toquvOrders->musteri_id,
                                            'toquv_rm_order_id' => $new_item->toquv_rm_order_id,
                                            'model_musteri_id' => $new_item->toquvRmOrder->toquvOrders->model_musteri_id,
                                            'model_code' => $new_item->toquvRmOrder->model_code,
                                            'color_pantone_id' => $new_item->toquvRmOrder->color_pantone_id,
                                            'toquv_instruction_id' => $new_item->toquvInstructionRm->toquv_instruction_id,
                                            'toquv_instruction_rm_id' => $new_item->toquv_instruction_rm_id
                                        ]);
                                        if($mato->save()){
                                            $flagIB = true;
                                        }else{
                                            echo "Mato saqlanmadi";
                                            \yii\helpers\VarDumper::dump($mato->getErrors(),10,true);die;
                                            $flagIB = false;
                                            break;
                                        }
                                    }
                                    if($mato){
                                        $ItemBalanceModel = new ToquvMatoItemBalance();
                                        $item_array['entity_id'] = $mato->id;
                                        $item_array['entity_type'] = ToquvDocuments::ENTITY_TYPE_MATO;
                                        $item_array['lot'] = $item_array['sort_name_id'];
                                        $item_array['department_id'] = $mato_sklad_id;
                                        $lastRec = ToquvMatoItemBalance::getLastRecordMoving($item_array);
                                        //tekwirish
                                        if (!empty($lastRec) && ($lastRec['inventory'] >= 0)) {
                                            $attributesTIB['entity_id'] = $item_array['entity_id'];
                                            $attributesTIB['entity_type'] = $item_array['entity_type'];
                                            $attributesTIB['is_own'] = $item_array['is_own'];
                                            $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                            $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                            $attributesTIB['document_id'] = null;
                                            $attributesTIB['inventory'] = $lastRec['inventory'] + $item_array['quantity'];
                                            $attributesTIB['roll_inventory'] = $lastRec['roll_inventory'] + 1;
                                            $attributesTIB['lot'] = (string)$item_array['lot'];
                                            $attributesTIB['count'] = $item_array['quantity'];
                                            $attributesTIB['roll_count'] = 1;
                                            $attributesTIB['department_id'] = $item_array['department_id'];;
                                            $attributesTIB['from_department'] = null;
                                            $attributesTIB['document_type'] = 10;
                                            $attributesTIB['musteri_id'] = $item_array['musteri_id'];
                                            $attributesTIB['reg_date'] = date('Y-m-d H:i:s');
                                        }else {
                                            $attributesTIB['entity_id'] = $item_array['entity_id'];
                                            $attributesTIB['entity_type'] = $item_array['entity_type'];
                                            $attributesTIB['is_own'] = $item_array['is_own'];
                                            $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                            $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                            $attributesTIB['document_id'] = null;
                                            $attributesTIB['inventory'] = $item_array['quantity'];
                                            $attributesTIB['roll_inventory'] = 1;
                                            $attributesTIB['lot'] = (string)$item_array['lot'];
                                            $attributesTIB['count'] = $item_array['quantity'];
                                            $attributesTIB['roll_count'] = 1;
                                            $attributesTIB['department_id'] = $item_array['department_id'];;
                                            $attributesTIB['from_department'] = null;
                                            $attributesTIB['document_type'] = 10;
                                            $attributesTIB['musteri_id'] = $item_array['musteri_id'];
                                            $attributesTIB['reg_date'] = date('Y-m-d H:i:s');
                                        }
                                        $ItemBalanceModel->setAttributes($attributesTIB);
                                        if ($ItemBalanceModel->save()) {
                                            $flagIB = true;
                                        } else {
                                            echo "Yangi Itembalance saqlanmadi";
                                            \yii\helpers\VarDumper::dump($ItemBalanceModel->getErrors(),10,true);die;
                                            $flagIB = false;
                                            break;
                                        }
                                    }
                                }else{
                                    $flagIB = true;
                                }
                            }else{
                                echo "New item saqlanmadi";
                                \yii\helpers\VarDumper::dump($new_item->getErrors(),10,true);die;
                                $flagIB = false;
                                break;
                            }
                            $changed_items .= $item.", ";
                        }else{
                            /*echo 'new_item topilmadi';
                            \yii\helpers\VarDumper::dump($new_item,10,true);*/
                            $transaction->rollBack();
                            $no_changed_items .= $item.", ";
                        }
                    }
                    if ($flagIB) {
                        $transaction->commit();
                    }else{
                        $transaction->rollBack();
                        echo "FlagIb yo'q";
                        var_dump($no_changed_items."<br>");
                        \yii\helpers\VarDumper::dump($items,10,true);die;
                    }
                } catch (Exception $e) {
                    Yii::info('Not saved' . $e, 'save');
                    $transaction->rollBack();
                    \yii\helpers\VarDumper::dump($e->getMessage(),10,true);die;
                }
                if($changed_items != ''){
                    Yii::$app->session->setFlash('info', Yii::t("app","<b>{code}</b> kodli matolar muvaffaqqiyatli o'zgartirildi", ['code'=>$changed_items]));
                }
                if($no_changed_items != ''){
                    Yii::$app->session->setFlash('error', Yii::t("app","<b>{code}</b> kodli matolar topilmadi", ['code'=>$no_changed_items]));
                }
            }else{
                Yii::$app->session->setFlash('error', Yii::t("app","Departament topilmadi", ['code' => $data['one']]));
            }
        }
        return $this->render('change-kalite');
    }
    public function actionChangeSort()
    {
        if ($data = Yii::$app->request->post()){
            $one = ToquvKalite::findOne(['code' => $data['one']]);
            if(!empty($data['kalite'])){
                $items =explode(',', $data['kalite']);
                $no_changed_items = '';
                $changed_items = '';
                $flagIB = false;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($items as $item) {
                        $new_item = ToquvKalite::findOne(['code' => $item]);
                        if ($new_item && $new_item->status > 2) {
                            if(Yii::$app->user->id!=1){
                                return \yii\helpers\VarDumper::dump("{$item} kodli rulon omborga jo'natilgan, uni o'zgartira olmaysiz!!!",10,true);die;
                            }
                            $new_model = new ToquvKaliteDeleted();
                            $new_model->attributes = $new_item->attributes;
                            $new_model->toquv_kalite_id = $new_item->id;
                            $ombor = ($new_item->status==3)? "Ombor holati ham o'zgartirildi.":"";
                            $fio = Yii::$app->user->identity->user_fio;
                            $new_model->info = "{$new_item->sort_name_id} sortdan {$data['sort_name_id']} sortga o'zgartirildi. {$ombor} O'zgartirdi: {$fio}";
                            $new_model->save();
                            if($new_model->hasErrors()){
                                $flagIB = false;
                                \yii\helpers\VarDumper::dump($new_model->getErrors(),10,true);die;
                                break;
                            }
                            $send_user = $new_item->send_user_id ?? Yii::$app->user->id;
                            $old_item_sort = $new_item['sort_name_id'];
                            $new_item->setAttributes([
                                'sort_name_id' => $data['sort_name_id'],
                                'created_by' => Yii::$app->user->id
                            ]);
                            $new_item->save();
                            $mato = MatoInfo::findOne([
                                'entity_id' => $new_item->toquvRmOrder->toquv_raw_materials_id,
                                'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                                'pus_fine_id' => $new_item->toquvInstructionRm->toquv_pus_fine_id,
                                'thread_length' => $new_item->toquvInstructionRm->thread_length,
                                'finish_en' => $new_item->toquvInstructionRm->finish_en,
                                'finish_gramaj' => $new_item->toquvInstructionRm->finish_gramaj,
                                'type_weaving' => $new_item->toquvInstructionRm->type_weaving,
                                'musteri_id' => $new_item->toquvRmOrder->toquvOrders->musteri_id,
                                'toquv_rm_order_id' => $new_item->toquv_rm_order_id,
                            ]);
                            $new_item = ToquvKalite::find()->where(['id'=>$new_item->id])->asArray()->one();
                            if ($mato) {
                                $ItemBalanceModel = new ToquvMatoItemBalance();
                                $new_item['entity_id'] = $mato->id;
                                $new_item['entity_type'] = ToquvDocuments::ENTITY_TYPE_MATO;
                                $new_item['lot'] = $new_item['sort_name_id'];
                                $new_item['department_id'] = 3;
                                $lastRec = ToquvMatoItemBalance::getLastRecordMoving($new_item);
                                //tekwirish
                                if (!empty($lastRec)) {
                                    $attributesTIB['entity_id'] = $new_item['entity_id'];
                                    $attributesTIB['entity_type'] = $new_item['entity_type'];
                                    $attributesTIB['is_own'] = $new_item['is_own'];
                                    $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                    $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                    $attributesTIB['document_id'] = null;
                                    $attributesTIB['inventory'] = $lastRec['inventory'] + $new_item['quantity'];
                                    $attributesTIB['roll_inventory'] = $lastRec['roll_inventory'] + 1;
                                    $attributesTIB['lot'] = $new_item['lot'];
                                    $attributesTIB['count'] = $new_item['quantity'];
                                    $attributesTIB['roll_count'] = 1;
                                    $attributesTIB['department_id'] = $new_item['department_id'];
                                    $attributesTIB['from_department'] = null;
                                    $attributesTIB['document_type'] = 11;
                                    $attributesTIB['musteri_id'] = $new_item['musteri_id'];
                                    $attributesTIB['reg_date'] = date('Y-m-d H:i:s');
                                } else {
                                    $attributesTIB['entity_id'] = $new_item['entity_id'];
                                    $attributesTIB['entity_type'] = $new_item['entity_type'];
                                    $attributesTIB['is_own'] = $new_item['is_own'];
                                    $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                    $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                    $attributesTIB['document_id'] = null;
                                    $attributesTIB['inventory'] = $new_item['quantity'];
                                    $attributesTIB['roll_inventory'] = 1;
                                    $attributesTIB['lot'] = $new_item['lot'];
                                    $attributesTIB['count'] = $new_item['quantity'];
                                    $attributesTIB['roll_count'] = 1;
                                    $attributesTIB['department_id'] = $new_item['department_id'];;
                                    $attributesTIB['from_department'] = null;
                                    $attributesTIB['document_type'] = 9;
                                    $attributesTIB['musteri_id'] = $new_item['musteri_id'];
                                    $attributesTIB['reg_date'] = date('Y-m-d H:i:s');
                                };
                                $ItemBalanceModel->setAttributes($attributesTIB);
                                if ($ItemBalanceModel->save()) {
                                    $flagIB = true;
                                } else {
                                    $flagIB = false;
                                    break;
                                }
                                $new_item['lot'] = $old_item_sort;
                                $ItemBalanceModel = new ToquvMatoItemBalance();
                                $lastRec = ToquvMatoItemBalance::getLastRecordMoving($new_item);
                                //tekwirish
                                if (!empty($lastRec) && ($lastRec['inventory'] - $new_item['quantity']) >=0) {
                                    $attributesTIB['entity_id'] = $new_item['entity_id'];
                                    $attributesTIB['entity_type'] = $new_item['entity_type'];
                                    $attributesTIB['is_own'] = $new_item['is_own'];
                                    $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                    $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                    $attributesTIB['document_id'] = null;
                                    $attributesTIB['inventory'] = $lastRec['inventory'] - $new_item['quantity'];
                                    $attributesTIB['roll_inventory'] = $lastRec['roll_inventory'] - 1;
                                    $attributesTIB['lot'] = (string)$new_item['lot'];
                                    $attributesTIB['count'] = $new_item['quantity'];
                                    $attributesTIB['roll_count'] = 1;
                                    $attributesTIB['department_id'] = $new_item['department_id'];;
                                    $attributesTIB['from_department'] = null;
                                    $attributesTIB['document_type'] = 9;
                                    $attributesTIB['musteri_id'] = $new_item['musteri_id'];
                                    $attributesTIB['reg_date'] = date('Y-m-d H:i:s');
                                    $ItemBalanceModel->setAttributes($attributesTIB);
                                }else {
                                    $flagIB = false;
                                    break;
                                }
                                if ($ItemBalanceModel->save()) {
                                    $flagIB = true;
                                } else {
                                    $flagIB = false;
                                    break;
                                }
                            }
                            $changed_items .= $item . ", ";
                        } else {
                            $no_changed_items .= $item . ", ";
                        }
                    }
                    if ($flagIB) {
                        $transaction->commit();
                    }else{
                        $transaction->rollBack();
                        \yii\helpers\VarDumper::dump($new_item['lot'],10,true);die;
                    }
                } catch (Exception $e) {
                    Yii::info('Not saved' . $e, 'save');
                }
                if($changed_items != ''){
                    Yii::$app->session->setFlash('info', Yii::t("app","<b>{code}</b> kodli matolar muvaffaqqiyatli o'zgartirildi", ['code'=>$changed_items]));
                }
                if($no_changed_items != ''){
                    Yii::$app->session->setFlash('error', Yii::t("app","<b>{code}</b> kodli matolar topilmadi", ['code'=>$no_changed_items]));
                }
            }
        }
        return $this->render('change-sort');
    }
    public function actionSaveDefects(){
        if(Yii::$app->request->post()){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $response['message'] = 'error';
            $model = new ToquvKalite();
            if ($result = $model->saveDefects(Yii::$app->request->post('Kalite'))){
                $response['status'] = 0;
                $response['message'] = "Success";
                $response['result'] = $result->code;
                $response['sort'] = $result->sortName->name;
            }
            return $response;
        }
        return false;
    }
    /**
     * @return string
     */
    public function actionInstructions(){
       $params = Yii::$app->request->queryParams;
       $model = new KaliteMatoForm();
       $instructions = $model->search($params);
        $pages = new Pagination(['totalCount' => count(ToquvKalite::getInstructionsWithKalite()),'pageSize'=>$model->limit]);
       $results = [];
       foreach ($instructions as $item){
           $results[$item['id']]['ins'] = [
                'id' => $item['id'],
                'document_number' => $item['document_number'],
                'musteri' => $item['musteri'],
                'reg_date' => $item['reg_date'],
                'sort' => $item['sort'],
                'matoid' => $item['matoid']
           ];
           $results[$item['id']]['mato'][$item['matoid']] = [
             'mato' => $item['mato'],
             'qty' => $item['qty']
           ];
       }
       return $this->render('instructions', [
             'items' => $results,
             'model' => $model,
                'pages' => $pages
           ]);
    }

    /**
     * @param $id
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionInstruction($id){
        $items = ToquvKalite::getToquvKaliteWithDefects($id);
        return $this->render('instruction', ['items' => $items]);
    }

    /**
     * Displays a single ToquvKalite model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new ToquvKaliteDefectsSearch();
        $dataProvider = $searchModel->search($id);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionSendUser($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $flagIB = false;
            $transaction = Yii::$app->db->beginTransaction();
            $response = [];
            try {
                if($model->status==3){
                    $mato = MatoInfo::findOne([
                        'entity_id' => $model->toquvRmOrder->toquv_raw_materials_id,
                        'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                        'pus_fine_id' => $model->toquvInstructionRm->toquv_pus_fine_id,
                        'thread_length' => $model->toquvInstructionRm->thread_length,
                        'finish_en' => $model->toquvInstructionRm->finish_en,
                        'finish_gramaj' => $model->toquvInstructionRm->finish_gramaj,
                        'type_weaving' => $model->toquvInstructionRm->type_weaving,
                        'musteri_id' => $model->toquvRmOrder->toquvOrders->musteri_id,
                    ]);
                    $new_item = ToquvKalite::find()->where(['id'=>$model->id])->asArray()->one();
                    if ($mato) {
                        $ItemBalanceModel = new ToquvMatoItemBalance();
                        $new_item['entity_id'] = $mato->id;
                        $new_item['entity_type'] = ToquvDocuments::ENTITY_TYPE_MATO;
                        $new_item['lot'] = $new_item['sort_name_id'];
                        $new_item['department_id'] = 3;
                        $lastRec = ToquvMatoItemBalance::getLastRecordMoving($new_item);
                        //tekwirish
                        if (!empty($lastRec && ($lastRec['inventory'] - $new_item['quantity']) >=0)) {
                            $attributesTIB['entity_id'] = $new_item['entity_id'];
                            $attributesTIB['entity_type'] = $new_item['entity_type'];
                            $attributesTIB['is_own'] = $new_item['is_own'];
                            $attributesTIB['price_usd'] = $lastRec['price_usd'];
                            $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                            $attributesTIB['document_id'] = null;
                            $attributesTIB['inventory'] = $lastRec['inventory'] - $new_item['quantity'];
                            $attributesTIB['roll_inventory'] = $lastRec['roll_inventory'] - 1;
                            $attributesTIB['lot'] = (string)$new_item['lot'];
                            $attributesTIB['count'] = $new_item['quantity'];
                            $attributesTIB['roll_count'] = 1;
                            $attributesTIB['department_id'] = $new_item['department_id'];;
                            $attributesTIB['from_department'] = null;
                            $attributesTIB['document_type'] = 9;
                            $attributesTIB['musteri_id'] = $new_item['musteri_id'];
                            $attributesTIB['reg_date'] = date('Y-m-d H:i:s');
                            $ItemBalanceModel->setAttributes($attributesTIB);
                        }else {
                            $flagIB = false;
                            $res = [
                                'status' => 'error',
                                'model' => $lastRec,
                                'message' => 'Not saved ' . $lastRec['inventory'] - $new_item['quantity']
                            ];
                            Yii::info($res, 'save');
                        }
                        if ($ItemBalanceModel->save()) {
                            $flagIB = true;
                        } else {
                            $flagIB = false;
                            Yii::info('Not saved mato ' . $ItemBalanceModel->getErrors(), 'save');
                        }
                    }
                }
                if($model->status < 3 || $flagIB){
                    $model->setAttributes([
                        'send_date' => date('Y-m-d H:i:s'),
                        'status' => ToquvKalite::STATUS_ACCEPTED
                    ]);
                    $flagIB = false;
                    if($model->save()){
                        $flagIB = true;
                        $transaction->commit();
                    }else{
                        if($model->hasErrors()){
                            Yii::info('Not saved' . $model->getErrors(), 'save');
                        }
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Hatolik yuz berdi'));
                        $transaction->rollBack();
                    }
                }else{
                    $response['message'] = Yii::t('app', "Oldinroq berib yuborilgan");
                }
            }catch (\Exception $e){
                Yii::info('Not saved' . $e, 'save');
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($flagIB) {
                    $response['status'] = 0;
                } else {
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            }
            if ($flagIB) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('send-user', [
                'model' => $this->findModel($id),
            ]);
        }
        return $this->render('send-user', [
            'model' => $model,
        ]);
    }
    /**
     * Creates a new ToquvKalite model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvKalite();
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                if ($model->save()) {
                    $response['status'] = 0;
                } else {
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ToquvKalite model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                if ($model->save()) {
                    $response['status'] = 0;
                } else {
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    public function actionChangePassword()
    {
        $model = Yii::$app->user->identity;
        if ($model->load(Yii::$app->request->post())) {
            $old_password = Yii::$app->request->post('old_password');
            $check = md5($old_password) === $model->oldAttributes['password'];
            $new_password = $model->password;
            if(!$check){
                return $this->render('change-password', [
                    'model' => $model,
                    'error' => 1,
                    'error_message' => Yii::t('app', "Eski parol noto'g'ri kiritildi!"),
                    'new_password' => false,
                    'success' => false,
                ]);
            }
            if ($model->save()) {
                return $this->render('change-password', [
                    'model' => $model,
                    'error_message' => false,
                    'success' => true,
                    'new_password' => $new_password,
                    'error' => false,
                ]);
            }else{
                return $this->render('change-password', [
                    'model' => $model,
                    'error' => true,
                    'new_password' => false,
                    'success' => false,
                    'error_message' => Yii::t('app', "Xatolik yuz berdi!"),
                ]);
            }
        }
        return $this->render('change-password', [
            'model' => $model,
            'new_password' => false,
            'success' => false,
            'error' => false,
            'error_message' => false,
        ]);
    }
    /**
     * Deletes an existing ToquvKalite model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $saved = false;
        $model = $this->findModel($id);
        $new_model = new ToquvKaliteDeleted();
        $new_model->attributes = $model->attributes;
        if ($new_model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $response = [];
            try {
                if ($model->delete()) {
                    $fio = Yii::$app->user->identity->user_fio;
                    $new_model->toquv_kalite_id = $id;
                    $new_model->info = "O'chirdi: {$fio}";
                    if ($new_model->save()) {
                        $saved = true;
                    }
                }
                if ($saved) {
                    $transaction->commit();
                    $response['status'] = 0;
                    $response['message'] = Yii::t('app', "Deleted Successfully");
                } else {
                    $transaction->rollBack();
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                    $response['message'] = Yii::t('app', "Hatolik yuz berdi");
                }
            } catch (\Exception $e) {
                Yii::info('Not saved' . $e, 'save');
            }
        }
        if(Yii::$app->request->isAjax){
            if($saved){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;
            }else{
                return $this->renderAjax('delete',[
                    'model' => $model,
                    'new_model' => $new_model
                ]);
            }
        }
        if($saved){
            return $this->redirect(['index']);
        }else{
            return $this->render('delete',[
                'model' => $model,
                'new_model' => $new_model
            ]);
        }
    }
    public function actionDeleteDefect($id)
    {
        $defect = ToquvKaliteDefects::findOne($id);
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $response['message'] = Yii::t('app', 'Fail');
            if($defect->delete()){
                $response['status'] = 0;
                $response['message'] = Yii::t('app', 'Deleted Successfully');
            }
            return $response;
        }
        $defect->delete();
        return $this->redirect(['index']);
    }

    public function actionChangeProcess($id)
    {
        $model = ToquvMakineProcesses::findOne($id);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('change-process', [
                'model' => $model,
                'id' => $id
            ]);
        }

        return $this->render('change-process', [
            'model' => $model,
            'id' => $id
        ]);
    }

    public function actionAjaxAction($id,$p=null)
    {
        $model = (!$p)?ToquvMakine::findOne($id):false;
        $procces = (!$p)?$model->processes:ToquvMakineProcesses::findOne($id);
        if($procces->ti->is_closed == 2){
            $procces = $procces->toquvMakine->processes;
        }
        $response = [];
        $response['status'] = 1;
        if($procces) {
            $response['instruction_id'] = $procces->ti->id;
            $response['tir_id'] = $procces->toquvInstructionRm->id;
            $response['tro_id'] = $procces->toquvOrderItem->id;
            $response['trm_id'] = $procces->toquvOrderItem->toquvRawMaterials->id;
            /*if(!$p) {*/
                $response['procces'] = (!$p)?$model->getProccesList(true):$procces->toquvMakine->getProccesList(true);
            /*}*/
            $response['status'] = 0;
            $rmOrder = $procces->toquvOrderItem;
            $info = $procces->toquvInstructionRm;
            $ready = ($info->id)?ToquvKalite::getOneKalite($info->id):'';
            $summa = ($ready['summa']) ?? 0;
            $remain = ($rmOrder['quantity'])?$rmOrder['quantity'] - $ready['summa']:0;
            $status = ($remain>0)?number_format($remain,2, '.', ''):Yii::t('app', 'Buyurtma bajarildi');
            $status = ($info->id)?$status:'';
            $model_musteri = ($rmOrder->toquvOrders->modelMusteri)?"(<small>{$rmOrder->toquvOrders->modelMusteri->name}</small>)":'';
            $response['musteri'] = $rmOrder->toquvOrders->musteri->name.$model_musteri;
            $response['doc_no'] = $rmOrder->toquvOrders->document_number;
            $response['mato'] = $rmOrder->toquvRawMaterials->name;
            $response['pus_fine'] = $info->toquvPusFine->name;
            $response['order_quantity'] = $rmOrder->quantity;
            $response['summa'] = $summa;
            $response['remain'] = $status;
            $response['info'] = $info->thread_length.' | '.$info->finish_en.' | '.$info->finish_gramaj;
        }
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }
        return false;
    }
    public function actionChangeProcessAction($id)
    {
        $model = ToquvMakineProcesses::findOne($id);
        $response = [];
        $response['status'] = 1;
        if($model) {
            $response['instruction_id'] = $model->ti->id;
            $response['tir_id'] = $model->toquvInstructionRm->id;
            $response['tro_id'] = $model->toquvOrderItem->id;
            $response['trm_id'] = $model->toquvOrderItem->toquvRawMaterials->id;
            $response['status'] = 0;
        }
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }

        return $this->render('change-process', [
            'model' => $model,
            'id' => $id
        ]);
    }
    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndFinish($id){

        $model = $this->findModel($id);
        if($model->status !== ToquvKalite::STATUS_SAVED){
            $model->status = ToquvKalite::STATUS_SAVED;
            $model->save();
        }
        return $this->redirect(['kalite','id' => $id]);
    }
    public function actionExportExcel(){
        $searchModel = new ToquvKaliteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);
        $status = false;
        if(!empty($searchModel->status)){
            $status =
                [
                    'attribute' => 'send_user_id',
                    'value' => function($model){
                        $tabel = (!empty($model->sendedUser->usersInfo['tabel']))?" T-".$model->sendedUser->usersInfo['tabel']:'';
                        return $model->sendedUser['user_fio'].$tabel;
                    },
                ];
        }
        header('Content-Type: application/vnd.ms-excel');
        $filename = "toquv-kalite_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => $dataProvider->getModels(),
            'columns' => [
                'id',
                'code',
                [
                    'attribute' => 'toquv_instructions_id',
                    'value' => function($model){
                        return $model->toquvInstructions->toquvOrder->document_number;
                    },
                ],
                [
                    'attribute' => 'Buyurtmachi',
                    'value' => function($model){
                        $musteri = ($model->toquvRmOrder->toquvOrders->modelMusteri)?" ({$model->toquvRmOrder->toquvOrders->modelMusteri->name})":'';
                        return $model->toquvRmOrder->toquvOrders->musteri->name.$musteri;
                    },
                ],
                [
                    'attribute' => 'toquv_rm_order_id',
                    'value' => function($model){
                        return $model->toquvRmOrder->toquvRawMaterials->name;
                    },
                ],
                [
                    'attribute' => 'toquv_makine_id',
                    'value' => function($model){
                        return $model->toquvMakine->name;
                    },
                ],

                [
                    'attribute' => 'user_id',
                    'value' => function ($model) {
                        $tabel = (!empty($model->user->usersInfo['tabel']))?" T-".$model->user->usersInfo['tabel']:'';
                        return $model->user['user_fio'].$tabel;
                    },
                ],
                [
                    'attribute' => 'quantity',
                ],
                [
                    'attribute' => 'sort_name_id',
                    'value' => function($model){
                        return $model->sortName->name;
                    },
                ],
                [
                    'attribute' => 'user_kalite_id',
                    'value' => function($model){
                        return $model->userKalite->user_fio;
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'value' => function($model){
                        return date('Y-m-d H:i',$model->created_at);
                    },
                ],
                $status
            ],
            'headers' => [
                'id' => 'Id',
                'toquv_rm_order_id' => Yii::t('app', 'Mato nomi'),
                'send_user_id' => ($searchModel->status==4)?Yii::t('app', 'Kimga berilgan'):Yii::t('app', "Jo'natuvchi"),
            ],
            'autoSize' => true,
        ]);
    }
    public function actionExportReport(){
        $searchModel = new ToquvKaliteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);
        header('Content-Type: application/vnd.ms-excel');
        $filename = "toquv-kalite_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => $dataProvider->getModels(),
            'columns' => [
                'id',
                [
                    'attribute' => 'code',
                    'headerOptions' => [
                        'style' => 'width:90px'
                    ],
                ],
                [
                    'attribute' => 'toquv_makine_id',
                    'value' => function($model){
                        return $model->toquvMakine->m_code;
                    },
                ],
                [
                    'attribute' => 'user_id',
                    'value' => function ($model) {
                        return $model->user['user_fio'];
                    },
                ],
                [
                    'label' => Yii::t('app', 'Tabel raqami'),
                    'attribute' => 'tabel_raqami',
                    'value' => function ($model) {
                        return $model->user->usersInfo['tabel'];
                    },
                ],
                [
                    'attribute' => 'quantity',
                ],
                [
                    'label' => Yii::t('app', 'Teshik yoki igna'),
                    'attribute' => 'teshik_yoki_yirtiq',
                    'value' => function($model){
                        return $model->getDefect(1);
                    },
                ],
                [
                    'label' => Yii::t('app', 'Yirtiq'),
                    'attribute' => 'yirtiq',
                    'value' => function($model){
                        return $model->getDefect(2);
                    },
                ],
                [
                    'attribute' => 'sort_name_id',
                    'value' => function($model){
                        return $model->sortName->name;
                    },
                ],
                [
                    'attribute' => 'smena',
                ],
                [
                    'attribute' => 'user_kalite_id',
                    'value' => function($model){
                        return $model->userKalite->user_fio;
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'value' => function($model){
                        return (time()-$model->created_at<(60*60*24))?Yii::$app->formatter->format(date($model->created_at), 'relativeTime'):date('d.m.Y H:i',$model->created_at);
                    },
                ],
            ],
            'headers' => [
                'id' => 'Id',
            ],
            'autoSize' => true,
        ]);
    }
    public function actionExportReportGroup(){
        $searchModel = new ToquvKaliteSearch();
        $dataProvider = $searchModel->searchGroup(Yii::$app->request->queryParams,1);
        header('Content-Type: application/vnd.ms-excel');
        $filename = "toquv-kalite_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => $dataProvider->getModels(),
            'columns' => [
                [
                    'attribute' => 'toquv_makine_id',
                    'value' => function($model){
                        return $model->toquvMakine->name;
                    },
                ],
                [
                    'attribute' => 'user_id',
                    'value' => function ($model) {
                        return $model->user['user_fio'];
                    },
                ],
                [
                    'attribute' => 'tabel',
                    'value' => function ($model) {
                        return $model->user->usersInfo['tabel'];
                    },
                ],
                [
                    'attribute' => 'musteri',
                    'value' => function($model){
                        $musteri = ($model->toquvRmOrder->toquvOrders->modelMusteri)?" ({$model->toquvRmOrder->toquvOrders->modelMusteri->name})":'';
                        return $model->toquvRmOrder->toquvOrders->musteri->name.$musteri;
                    },
                ],
                [
                    'attribute' => 'toquv_raw_materials_id',
                    'label' => Yii::t('app', 'Mato'),
                    'value' => function($model){
                        return $model->toquvRawMaterials->name;
                    },
                ],
                [
                    'attribute' => 'pus_fine_id',
                    'label' => Yii::t('app', 'Pus/Fine'),
                    'value' => function($model){
                        return $model->toquvInstructionRm->toquvPusFine->name;
                    },
                ],
                [
                    'attribute' => 'count',
                ],
                [
                    'attribute' => 'quantity',
                ],
                [
                    'attribute' => 'sort_name_id',
                    'value' => function($model){
                        return $model->sortName->name;
                    },
                ],
            ],
            'headers' => [
                'count' => Yii::t('app', 'Rulon soni'),
                'tabel' => Yii::t('app', 'Tabel raqami'),
                'pus_fine_id' => Yii::t('app', 'Pus/Fine'),
                'musteri' => Yii::t('app', 'Buyurtmachi'),
            ],
            'autoSize' => true,
        ]);
    }
    /**
     * Finds the ToquvKalite model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvKalite the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvKalite::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
