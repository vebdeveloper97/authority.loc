<?php

namespace app\modules\bichuv\controllers;

use app\modules\bichuv\models\BichuvDetailTypes;
use app\modules\bichuv\models\BichuvGivenRollItemsSub;
use app\modules\bichuv\models\BichuvGivenRolls;
use app\modules\bichuv\models\BichuvNastelDetailItems;
use app\modules\bichuv\models\BichuvNastelProcesses;
use app\modules\bichuv\models\BichuvProcesses;
use app\modules\bichuv\models\BichuvProcessesUsers;
use app\modules\bichuv\models\BichuvTables;
use Yii;
use app\modules\bichuv\models\BichuvGivenRollItems;
use app\modules\bichuv\models\BichuvNastelDetailsSearch;
use app\modules\toquv\controllers\BaseController;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BichuvNastelDetailsController implements the CRUD actions for BichuvGivenRollItems model.
 */
class BichuvNastelDetailsController extends BaseController
{
    public $slug;
    public $type;
    public $table;
    public $_process;
    public $_type;
    public $_table;
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
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $slug = Yii::$app->request->get('slug');
            $type = Yii::$app->request->get('type');
            $table = Yii::$app->request->get('table');
            $flag = false;
            if (!empty($slug)) {
                $check_proccess = ArrayHelper::getColumn(BichuvProcesses::find()->joinWith('bichuvProcessesUsers')->select('slug')->where(['users_id'=>Yii::$app->user->id])->asArray()->all(),'slug');
                if(!in_array($slug,$check_proccess)){
                    throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
                }
                $processName = BichuvProcesses::findOne(['slug'=>$slug]);
                if($processName){
                    $this->_process = $processName;
                }
                $flag = true;
                $this->slug = $slug;
            }
            if (!empty($type)) {
                $process = BichuvProcesses::find()->joinWith('bichuvProcessesUsers')->select('bichuv_processes.id')->where(['users_id'=>Yii::$app->user->id]);
                $list = BichuvDetailTypes::find()->select('slug')->where(['bichuv_process_id' => $process])->all();
                $check_proccess = ArrayHelper::getColumn($list,'slug');
                if(!in_array($type,$check_proccess)){
                    throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
                }
                $typeName = BichuvDetailTypes::findOne(['slug'=>$type]);
                if($typeName){
                    $this->_type = $typeName;
                }
                $flag = true;
                $this->type = $type;
            }
            if (!empty($table)) {
                $process = BichuvProcesses::find()->joinWith('bichuvProcessesUsers')->select('bichuv_processes.id')->where(['users_id'=>Yii::$app->user->id]);
                $list = BichuvTables::find()->select('slug')->where(['bichuv_processes_id' => $process])->all();
                $check_proccess = ArrayHelper::getColumn($list,'slug');
                if(!in_array($table,$check_proccess)){
                    throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
                }
                $tableName = BichuvTables::findOne(['slug'=>$table]);
                if($tableName){
                    $this->_table = $tableName;
                }
                $flag = true;
                $this->table = $table;
            }
            /*if (!$flag) {
                throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
            }*/
            /*if (Yii::$app->authManager->getPermission(Yii::$app->controller->id . "/" . Yii::$app->controller->action->id)) {
                if (!Yii::$app->user->can(Yii::$app->controller->id . "/" . $this->slug . "/" . Yii::$app->controller->action->id)) {
                    throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
                }
            }*/
            return true;
        } else {
            return false;
        }
    }
    /**
     * Lists all BichuvGivenRollItems models.
     * @return mixed
     */
    public function actionIndex()
    {
        $check_proccess = BichuvProcesses::find()->joinWith('bichuvProcessesUsers')->select('id')->where(['users_id'=>Yii::$app->user->id])->asArray();
        $detail_types = BichuvDetailTypes::find()->select(['slug','name'])->where(['bichuv_process_id' => $check_proccess])->asArray()->all();
        $tables = BichuvTables::find()->where(['bichuv_processes_id'=>$check_proccess])->andWhere(['bichuv_processes_id'=>$this->_process['id']])->asArray()->all();
        $processes = BichuvNastelProcesses::find()->where(['bichuv_process_id' => $this->_process['id'], 'bichuv_detail_type_id' => $this->_type['id'], 'bichuv_nastel_stol_id' => $this->_table['id']])->orderBy(['id'=>SORT_DESC])->all();
        return $this->render('index', [
            'detail_types' => $detail_types,
            'tables' => $tables,
            'processes' => $processes,
            'bichuv_processes' => BichuvProcesses::find()->joinWith('bichuvProcessesUsers')->where(['users_id'=>Yii::$app->user->id])->asArray()->all(),
            'check' => BichuvNastelProcesses::checkList($this->_process['id'],$this->_type['id'],$this->_table['id'])
        ]);
    }

    public function actionSection($limit = 12)
    {
        /*$nastels = BichuvGivenRollItems::find()->joinWith(['bichuvDetailType', 'bichuvGivenRoll', 'bichuvMatoInfo.mato', 'bichuvNastelProcesses'])->andWhere(['bichuv_detail_types.token' => $this->_type['token']])->andWhere(['or',['not in','bichuv_nastel_processes.bichuv_nastel_stol_id', $this->_table['id']],['is','bichuv_nastel_processes.id',null]])->asArray()->orderBy(['id' => SORT_DESC])->limit($limit)->all();*/

        $sql = "SELECT bgri.entity_id,
                       bgri.id bgri_id, 
                       m.name,
                       rm.name         as mato,
                       nename.name     as ne,
                       thr.name        as thread,
                       pf.name         as pus_fine,
                       c.color_id,
                       ct.name         as ctone,
                       c.pantone,
                       p.name          as model,
                       bmi.en,
                       bmi.gramaj,
                       bgri.roll_count as rulon_count,
                       bgri.quantity   as rulon_kg,
                       bgri.party_no,
                       bgri.musteri_party_no,
                       bgr.nastel_party,
                       bdt.name        as detail,
                       bdt.id          as detail_type_id,
                       bp.id           as process_id
                FROM bichuv_given_roll_items bgri
                         LEFT JOIN bichuv_detail_types bdt
                                   ON bgri.bichuv_detail_type_id = bdt.id
                         LEFT JOIN bichuv_given_rolls bgr ON bgri.bichuv_given_roll_id = bgr.id
                         LEFT JOIN bichuv_mato_info bmi ON bgri.entity_id = bmi.id
                         LEFT JOIN raw_material ON bmi.rm_id = raw_material.id
                         left join bichuv_processes bp on bdt.bichuv_process_id = bp.id
                         left join product p on bgri.model_id = p.id
                         left join raw_material rm on bmi.rm_id = rm.id
                         left join ne nename on nename.id = bmi.ne_id
                         left join pus_fine pf on pf.id = bmi.pus_fine_id
                         left join thread thr on thr.id = bmi.thread_id
                         left join color c on bmi.color_id = c.id
                         left join color_tone ct on c.color_tone = ct.id
                         left join musteri m on bgr.musteri_id = m.id
                WHERE (bdt.token = '%s') AND bgri.id not in (select bnp.bichuv_given_roll_items_id
                                                              from bichuv_nastel_processes bnp
                                                              where bnp.bichuv_nastel_stol_id = %d and bnp.bichuv_given_roll_items_id is not null)
                ORDER BY bgri.id DESC
                LIMIT %d";
        $sql = sprintf($sql,$this->_type['token'],$this->_table['id'],$limit);
        $nastels = Yii::$app->db->createCommand($sql)->queryAll();
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $res = [];
            $res['status'] = 0;
            $res['list'] = [];
            if (Yii::$app->request->post() && Yii::$app->request->isAjax){
                $data = Yii::$app->request->post();
                if (!empty($data['list'])){
                    $list = implode(',', $data['list']);
                }
                $res['status'] = 1;
                $res['list'] = BichuvGivenRolls::getNastelSearch($data['query'],$list,$this->_table['id'],$this->_type['token']);
            }
            return $res;
        }
        return $this->render('section',[
            'nastels' => $nastels,
        ]);
    }
    public function actionNastelDetailInfo($id)
    {
        $roll = BichuvGivenRolls::getRollOne($id);
        $roll_items = BichuvGivenRolls::getNastelDetalItems($id);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('nastel-detail-info', [
                'roll' => $roll,
                'roll_items' => $roll_items,
            ]);
        }
        return $this->render('nastel-detail-info', [
            'roll' => $roll,
            'roll_items' => $roll_items,
        ]);
    }

    public function actionProcessList()
    {
        $detail = $this->_type['id'];
        if ($data = Yii::$app->request->post()){
            $roll = BichuvGivenRolls::getRollOne($data['id']);
            if(empty($roll)){
                Yii::$app->session->setFlash('error', Yii::t('app', 'Hatolik yuz berdi'));
            }else {
                $process = BichuvNastelProcesses::findOne([
                    'bichuv_detail_type_id' => $this->_type['id'],
                    'bichuv_nastel_stol_id' => $this->_table['id'],
                    'bichuv_process_id' => $this->_process['id'],
                    'bichuv_given_roll_items_id' => $data['id']
                ]);
                if (!$process) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $saved = false;
                        $process = new BichuvNastelProcesses([
                            'nastel_no' => $roll['nastel_no'],
                            'bichuv_detail_type_id' => $this->_type['id'],
                            'bichuv_nastel_stol_id' => $this->_table['id'],
                            'action' => BichuvNastelProcesses::ACTION_BEGIN,
                            'user_started' => Yii::$app->user->id,
                            'started_time' => date('Y-m-d H:i:s'),
                            'bichuv_process_id' => $this->_process['id'],
                            'bichuv_given_roll_items_id' => $data['id']
                        ]);
                        if ($process->save()) {
                            $saved = true;
                            $bgri = BichuvGivenRollItems::findOne($data['id']);
                            if ($bgri) {
                                $bgri_sub = new BichuvGivenRollItemsSub([
                                    'bichuv_given_roll_items_id' => $bgri['id'],
                                    'bichuv_nastel_processes_id' => $process['id'],
                                ]);
                                $bgri->status = BichuvGivenRollItems::STATUS_BEGIN;
                                if ($bgri->save() && $bgri_sub->save()) {
                                    $saved = true;
                                } else {
                                    $saved = false;
                                }
                            }
                            if ($bgri->getNastelItemsList(true)) {
                                foreach ($bgri->getNastelItemsList(true) as $item) {
                                    $new_item = new BichuvNastelDetailItems([
                                        'size_id' => $item['size_id'],
                                        'bichuv_nastel_detail_id' => $item['bichuv_nastel_detail_id'],
                                        'count' => $item['count'],
                                        'required_count' => $item['required_count'],
                                        'weight' => $item['weight'],
                                        'required_weight' => $item['required_weight'],
                                        'type' => $item['type'],
                                        'bichuv_given_roll_items_id' => $item['bichuv_given_roll_items_id'],
                                        'bichuv_processes_id' => $item['bichuv_processes_id'],
                                        'bichuv_nastel_processes_id' => $process['id'],
                                        'order' => $item['order'] + 1
                                    ]);
                                    if ($new_item->save()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            }
                        } else {
                            $saved = false;
                        }
                        if ($saved) {
                            Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                            $transaction->commit();
                        } else {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', Yii::t('app', 'Hatolik yuz berdi'));
                        }
                    } catch (\Exception $e) {
                        Yii::$app->session->setFlash('error', $e->getMessage());
                        Yii::info('Not saved bgri ' . $e->getMessage() . ' ' . $e->getCode(), 'save');
                    }
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Bunday nastel raqamdagi partiya avval bu stolda boshlangan :('));
                }
            }
            return $this->redirect(['index', 'slug' => $this->slug, 'type' => $this->type, 'table' => $this->table]);
        }
        $list = BichuvNastelProcesses::getList($detail);
        return $this->render('process-list', [
            'list' => $list
        ]) ;
    }
    public function actionProcess($id)
    {
        $roll = BichuvGivenRolls::getRollOne($id);

        $process = BichuvNastelProcesses::findOne($id);
        if ($data = Yii::$app->request->post()){
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                $modelBGRIS = BichuvGivenRollItemsSub::findOne($data['BichuvGivenRollItemsSub']['id']);
                if($modelBGRIS){
                    $modelBGRIS->setAttributes([
                       'roll_remain' => $data['BichuvGivenRollItemsSub']['roll_remain'],
                       'remain' => $data['BichuvGivenRollItemsSub']['remain'],
                       'otxod' => $data['BichuvGivenRollItemsSub']['otxod'],
                    ]);
                    if($modelBGRIS->save()){
                        $saved = true;
                    }else{
                        $saved = false;
                        $transaction->rollBack();
                    }
                }
                if(!empty($data['BichuvNastelDetailItems'])){
                    foreach ($data['BichuvNastelDetailItems'] as $key => $item) {
                        $detail_item = BichuvNastelDetailItems::findOne($key);
                        if($detail_item){
                            $detail_item->setAttributes([
                                'count' => $item['count'],
                                'weight' => $item['weight'],
                            ]);
                            if($detail_item->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                }
                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                }else{
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Hatolik yuz berdi'));
                }
                if ($data['finish']&&$process){
                    $process->setAttributes([
                        'action' => BichuvNastelProcesses::ACTION_END,
                        'user_ended' => Yii::$app->user->id,
                        'ended_time' => date('Y-m-d H:i:s')
                    ]);
                    if($process->save()){
                        return $this->redirect(['index', 'slug' => $this->slug, 'type' => $this->type, 'table' => $this->table]);
                    }
                }
            }catch (\Exception $e){
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::info('Not saved bgri ' . $e->getMessage(). ' ' .$e->getCode(), 'save');
            }
        }
        if($process){
                $sub = $process->bichuvGivenRollItemsSubs ?? new BichuvGivenRollItemsSub();
                $roll_items = $process->bichuvNastelDetailItems ?? new BichuvNastelDetailItems();
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('process', [
                    'sub' => $sub,
                    'roll_items' => $roll_items,
                ]);
            }
            return $this->render('process', [
                'sub' => $sub,
                'roll_items' => $roll_items,
            ]);

        }else{
            throw new NotFoundHttpException(Yii::t('app', 'Jarayon topilmadi'));
        }
    }
    /**
     * Displays a single BichuvGivenRollItems model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BichuvGivenRollItems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BichuvGivenRollItems();
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
     * Updates an existing BichuvGivenRollItems model.
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

    /**
     * Deletes an existing BichuvGivenRollItems model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(Yii::$app->request->isAjax){
            if($this->findModel($id)->delete()){
                echo "success";
            }else{
                echo "fail";
            }
            exit();
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "bichuv-nastel-details_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => BichuvGivenRollItems::find()->select([
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
     * Finds the BichuvGivenRollItems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BichuvGivenRollItems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BichuvGivenRollItems::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
