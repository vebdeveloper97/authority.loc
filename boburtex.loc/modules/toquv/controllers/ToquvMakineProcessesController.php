<?php

namespace app\modules\toquv\controllers;

use app\models\Constants;
use app\models\Users;
use app\modules\toquv\models\ToquvInstructionItems;
use app\modules\toquv\models\ToquvInstructionRm;
use app\modules\toquv\models\ToquvInstructions;
use Yii;
use app\modules\toquv\models\ToquvMakineProcesses;
use app\modules\toquv\models\ToquvMakineProcessesSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ToquvMakineProcessesController implements the CRUD actions for ToquvMakineProcesses model.
 */
class  ToquvMakineProcessesController extends BaseController
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
                    'delete' => ['POST','GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all ToquvMakineProcesses models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvMakineProcessesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ToquvMakineProcesses model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $responsible = null;
        $count = 14;
        if ($data = Yii::$app->request->post()){
            if(!empty($data['users_id'])) {
                $responsible = Users::find()->where(['IN', 'id', $data['users_id']])->all();
            }
            if(!empty($data['count'])) {
                $count = $data['count'];
            }
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'responsible' => $responsible,
            'count' => $count
        ]);
    }

    /**
     * Creates a new ToquvMakineProcesses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvMakineProcesses();

        if (Yii::$app->request->post()){//} && $model->save()) {
            $data = Yii::$app->request->post();
            $ti = ToquvInstructions::findOne($data['ToquvMakineProcesses']['ti_id']);
            $tir = ToquvInstructionRm::findOne($data['ToquvMakineProcesses']['toquv_instruction_rm_id']);
            $order = $ti['toquv_order_id'];
            $orderItem = $tir['toquv_rm_order_id'];
            if($ti && $tir) {
                foreach ($data['ToquvMakineProcesses']['machines'] as $key) {
                    /*$makine = ToquvMakineProcesses::findOne(['machine_id' => $key, 'status' => 1]);
                    if ($makine) {
                        $makine->ended_at = date('Y-m-d H:i:s');
                        $makine->ended_by = Yii::$app->user->id;
                        $makine->machines = 1;
                        $makine->status = 3;
                        $makine->save();
                        if($makine->hasErrors()){
                            \yii\helpers\VarDumper::dump($makine->getErrors(),10,true);die;
                        }
                    }*/
                    ToquvMakineProcesses::updateAll(
                        [
                            'ended_at'=>date('Y-m-d H:i:s'),
                            'ended_by'=>Yii::$app->user->id,
                            'status' => 3
                        ],
                        [
                            'AND',
                            ['=','machine_id',$key],
                            ['=','status',1],
                            ['is','ended_at', new \yii\db\Expression('null')],
                        ]
                    );
                    $item = new ToquvMakineProcesses();
                    $item->setAttributes([
                        'ti_id' => $ti['id'],
                        'toquv_instruction_rm_id' => $tir['id'],
                        'toquv_order_id' => $order,
                        'toquv_order_item_id' => $orderItem,
                        'machine_id' => $key,
                        'machines' => 1
                    ]);
                    $item->save();
                }
                return $this->redirect(['view','id'=>$item->id]);
            }else{
                Yii::$app->session->setFlash('error',Yii::t('app','Xatolik yuz berdi!'));
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ToquvMakineProcesses model.
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

    /**
     * Deletes an existing ToquvMakineProcesses model.
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
    /**
     * Lists all ToquvMakineProcesses models.
     * @return mixed
     */
    public function actionMachineProcess()
    {
        $searchModel = new ToquvMakineProcessesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $isOrder = true);
        return $this->render('machine-process', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOrderItems($id)
    {
        $this->layout = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 0;
        $response['message'] = 'error';
        $response['data'] = [];
        if(Yii::$app->request->isAjax){
            $sql = "select tirm.id as tid,
                   tpf.name as pus_fine,
                   tro.quantity,
                   tirm.quantity t_qty,
                   trm.name as mato,
                   tirm.finish_en,
                   tirm.thread_length,
                   tirm.finish_gramaj,
                   ti.priority,
                   tro.done_date,
                   cp.name as cname,
                   cp.code as ccode, r, g, b,
                   type.name as tname,
                   tro.color_pantone_id,
                   tro.color_id,
                    c.color_id cl_name,
                    c.color cl_color
                    from toquv_instructions ti
                             left join toquv_instruction_rm tirm on ti.id = tirm.toquv_instruction_id
                             left join toquv_rm_order tro on tirm.toquv_rm_order_id = tro.id
                             left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                             left join toquv_pus_fine tpf on tirm.toquv_pus_fine_id = tpf.id
                             left join color_pantone cp on tro.color_pantone_id = cp.id
                            left join color_panton_type as type ON cp.color_panton_type_id = type.id
                            left join color c ON tro.color_id = c.id
                    where ti.id =:orderId";
            $row = Yii::$app->db->createCommand($sql)->bindValues(['orderId' => $id])->queryAll();
            if(!empty($row)){
                $response['status'] = 1;
                $response['message'] = 'success';
                foreach ($row as $item){
                    array_push($response['data'],[
                        'id' => $item['tid'],
                        'name' => " <span class='btn btn-default'> <span style='color: #00695C;'> <b>{$item['mato']}</b></span>". ' |
                         <b>' .round($item['quantity'],0) .'</b> kg </span> |
                            pus/fine-><b>'.$item['pus_fine'].'</b> |
                            Ip Uzunligi-><b>'.$item['thread_length'].'</b> |
                            Finsh en-><b>'.$item['finish_en'].'</b> |
                            Finsh gramaj-><b>'.$item['finish_gramaj'].'</b> | 
                            Muddati-><b>'.$item['done_date'].'</b> |
                            Zarurligi-><b>'.Constants::getPriorityList($item['priority']).'</b> | 
                            Ko\'rsatma miqdori -> <b>'.$item['t_qty'] ."</b> kg | Rang: <span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>"
                            .$item['tname'] . "</span></span> {$item['ccode']} | Rang(Bo'yoqxona): <span style='background:{$item['cl_color']}; width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>"
                        .$item['cl_color'] . "</span></span> ".$item['cl_name'],
                        'pus_fine_id' => $item['pus_fine'],

                    ]);
                }
                //$response['data'] = ArrayHelper::map($response['data'],'id','name');
            }
        }
        return $response;
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "toquv-makine-processes_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => ToquvMakineProcesses::find()->where(['is','ended_by', new \yii\db\Expression('null')])->all(),
            'columns' => [
                [
                    'attribute' => 'Nomer',
                    'value' => function ($model) {
                        return $model->toquvMakine['id'];
                    },
                ],
                [
                    'attribute' => 'toquv_order_id',
                    'value' => function ($model) {
                        $model_musteri = ($model->toquvOrder->modelMusteri)?" ({$model->toquvOrder->modelMusteri->name})":"";
                        return $model->toquvOrder->musteri->name.$model_musteri . " - " . $model->toquvOrder['document_number'];
                    },
                ],
                [
                    'attribute' => 'toquv_order_item_id',
                    'value' => function ($model) {
                        $ip = ($model->toquvOrderItem->toquvRawMaterials)?"(".$model->toquvOrderItem->toquvRawMaterials->getRawMaterialIP(',',true).")":"";
                        return $model->toquvOrderItem->toquvRawMaterials['name']." ".$ip;
                    },
                    'label' => Yii::t('app', 'Mato nomi')
                ],
                [
                    'attribute' => 'model',
                    'value' => function ($model) {
                        return $model->toquvOrderItem['model_code'];
                    },
                    'label' => Yii::t('app', 'Model'),
                ],
                [
                    'attribute' => 'rang',
                    'value' => function ($model) {
                        $item = $model->toquvOrderItem->colorPantone;
                        return $item['code'];
                    },
                    'label' => Yii::t('app', 'Rang'),
                ],
                [
                    'attribute' => 'rang_boyoq',
                    'value' => function ($model) {
                        $item = $model->toquvOrderItem->color;
                        return $item['color_id'];
                    },
                    'label' => Yii::t('app', "Rang (Bo'yoqxona)"),
                ],
                [
                    'attribute' => 'toquv_kg',
                    'value' => function ($model) {
                        return round($model->toquvOrderItem['quantity'], 0);
                    },
                    'label' => Yii::t('app', 'Buyurtma miqdori'),
                ],
                /*[
                    'attribute' => 'instruction_kg',
                    'label' => Yii::t('app', 'Ko\'rsatma miqdori'),
                    'value' => function ($model) {
                        $tir = $model->getToquvInstructionRm()->asArray()->one();
                        return ($tir)?round($tir['quantity']):'';
                    },
                ],*/
                [
                    'attribute' => 'machine_id',
                    'value' => function ($model) {
                        return $model->toquvMakine['name'];
                    },
                ],
                [
                    'attribute' => 'pus_fine',
                    'value' => function ($model) {
                        return $model->toquvInstructionRm->toquvPusFine['name'];
                    },
                ],
                [
                    'attribute' => 'Uzu/Eni/Gra',
                    'value' => function ($model) {
                        return $model->toquvOrderItem['thread_length'] . ' | ' . $model->toquvOrderItem['finish_en'] . ' | ' . $model->toquvOrderItem['finish_gramaj'];
                    },
                ],
                [
                    'attribute' => 'started_at',
                    'value' => function ($model) {
                        return $model->userBy['user_fio'] . " \n" . $model->started_at;
                    },
                ],
                [
                    'attribute' => 'ended_at',
                    'value' => function ($model) {
                        return $model->endedBy['user_fio'] . " \n" . $model->ended_at;
                    },
                ],
                [
                    'attribute' => 'done_date',
                    'value' => function ($model) {
                        return $model->toquvOrderItem['done_date'];
                    },
                ],
            ],
            'headers' => [
                'id' => 'Id',
            ],
            'autoSize' => true,
        ]);
    }

    public function actionProcessEnd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $thisUser = Yii::$app->user->id;
        $response = [];
        $response['status'] = 0;
        $response['message'] = 'error';
        $data = Yii::$app->request->post();
        if($data['id']){
            $sql = "UPDATE toquv_makine_processes SET ended_at = NOW(), ended_by = {$thisUser}, status = 3 WHERE id = {$data['id']}";
            $query = Yii::$app->db->createCommand($sql)->execute();
            if($query){
                $response['status'] = 1;
                $response['message'] = 'OK';
            }
        }
        return $response;
    }
    public function actionGetMachines($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 0;
        $response['message'] = 'error';
        $response['data'] = [];
        $res = ToquvMakineProcesses::getMachineList($id);

        $macines=[];
        foreach ( $res as $mac ){
            $macines[$mac['id']]= -1;
        }
        $sql = "SELECT  tm.id,
                        tpf.name pus_fine,
                        tm.name AS mname,
                        tm.type
                   from toquv_makine tm
                   LEFT JOIN toquv_pus_fine tpf ON tm.pus_fine_id = tpf.id
                   where tm.id != 0";
        $machineList = Yii::$app->db->createCommand($sql)->queryAll();

        if (!empty($res)) {
            $order = Yii::t('app', 'Buyurtma');
            $tayyor = Yii::t('app', 'Tayyorlandi');
            foreach ($res as $item) {
                $model_mushteri = (!empty($item['model_mushteri']))?" (<span style='color:red'>{$item['model_mushteri']}</span>)":'';

                $remain = abs($item['order_quantity'] - $item['tk_quantity']);
                $qoldi = ($remain<=0)?Yii::t('app', 'Ortiqcha tayyorlandi'):Yii::t('app', 'Qoldi');
                $name = "<b>{$item['mname']}</b> - (<b>{$item['pus_fine']}</b>) <b><span style='color:lightblue;background-color: black;padding: 0 5px;'>{$item['mato']}</span></b> (<b>{$item['musname']}{$model_mushteri}</b>) (<b>{$item['length']}</b> | <b>{$item['en']}</b> | <b>{$item['gramaj']}</b>)  <b>{$order} : <span style='color:lightblue;background-color: black;padding: 0 5px;'>{$item['order_quantity']}</span></b> kg | <b>{$tayyor} : <span style='color:lightblue;background-color: black;padding: 0 5px;'>{$item['tk_quantity']}</span></b> kg | {$qoldi} : <b>{$remain}</b>";
                array_push($response['data'], [
                    'id' => $item['id'],
                    'text' => $name,
                ]);
            }
            if(!empty($response['data'])){
                $response['status'] = 1;
                $response['message'] = 'success';
//                $response['data'] = ArrayHelper::map($response['results'],'id','text');
            }
        }

//        if (!empty($machineList)){
//            foreach ($machineList as $items) {
//                if ($macines[$items['id']] != -1) {
//                    $name = $items['mname'] . ' - <b>' . $items['pus_fine'] . "</b>";
//                    array_push($response['data'], [
//                        'id' => $items['id'],
//                        'text' => $name,
//                    ]);
//                }
//            }
//            if(!empty($response['data'])){
//                $response['status'] = 1;
//                $response['message'] = 'success';
////                $response['data'] = ArrayHelper::map($response['results'],'id','text');
//            }
//
//        }
        else if (empty($res) && empty($machineList)){
            $response['data'] = [
                'id' => '',
                'text' => '',
            ];
        }
        return $response;
    }

    /**
     * Finds the ToquvMakineProcesses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvMakineProcesses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvMakineProcesses::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
