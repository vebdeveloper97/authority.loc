<?php

namespace app\modules\toquv\controllers;

use app\modules\base\models\ModelOrdersItems;
use app\modules\tikuv\models\TikuvOutcomeProductsPack;
use app\modules\toquv\models\RemainSearchMato;
use app\modules\toquv\models\RemainSearchModel;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvOrders;
use app\modules\toquv\models\ToquvRawMaterials;
use Yii;
use app\modules\toquv\models\ToquvItemBalance;
use app\modules\toquv\models\ToquvDocumentBalanceSearch;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ReportsController implements the CRUD actions for ToquvItemBalance model.
 */
class ReportsController extends BaseController
{
    public $acs = ToquvRawMaterials::ENTITY_TYPE_ACS;
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        $searchModel = new ToquvDocumentBalanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionReportIpSklad(){

        $searchModel = new ToquvDocumentBalanceSearch();
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '01.01.2019';
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));
        $data['group_by_lot'] = false;
        $data['name'] = Yii::t('app', "Bo'lim tanlanmagan");
        if(!empty($params['ToquvDocumentBalanceSearch'])){
            if(!empty($params['ToquvDocumentBalanceSearch']['from_date'])){
                $data['from_date'] = $params['ToquvDocumentBalanceSearch']['from_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['to_date'])){
                $data['to_date'] = $params['ToquvDocumentBalanceSearch']['to_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['group_by_lot'])){
                $data['group_by_ip'] = $params['ToquvDocumentBalanceSearch']['group_by_ip'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['ToquvDocumentBalanceSearch']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $data['name'] = $dpt['name'];
                }
            }
        }
        $items = $searchModel->search($params);

        return $this->render('report-ip-sklad', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    public function actionReportMatoSklad(){

        $searchModel = new ToquvDocumentBalanceSearch();
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '2019-11-01 00:00:00';
        $data['to_date'] = date('Y-m-d H:i:s', strtotime('tomorrow'));
        $searchModel['created_at'] = "{$data['from_date']} - {$data['to_date']}";
        $data['group_by_lot'] = false;
        $data['name'] = Yii::t('app', "Bo'lim tanlanmagan");
        if(!empty($params['ToquvDocumentBalanceSearch'])){
            if(!empty($params['ToquvDocumentBalanceSearch']['from_date'])){
                $data['from_date'] = $params['ToquvDocumentBalanceSearch']['from_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['to_date'])){
                $data['to_date'] = $params['ToquvDocumentBalanceSearch']['to_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['group_by_lot'])){
                $data['group_by_ip'] = $params['ToquvDocumentBalanceSearch']['group_by_ip'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['ToquvDocumentBalanceSearch']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $data['name'] = $dpt['name'];
                }
            }
        }
        $items = $searchModel->searchMato($params);

        return $this->render('report-mato-sklad', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    public function actionReportModelMatoSklad(){

        $searchModel = new ToquvDocumentBalanceSearch();
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '2019-11-01 00:00:00';
        $data['to_date'] = date('Y-m-d H:i:s', strtotime('tomorrow'));
        $searchModel['created_at'] = "{$data['from_date']} - {$data['to_date']}";
        $data['group_by_lot'] = false;
        $data['name'] = Yii::t('app', "Bo'lim tanlanmagan");
        if(!empty($params['ToquvDocumentBalanceSearch'])){
            if(!empty($params['ToquvDocumentBalanceSearch']['from_date'])){
                $data['from_date'] = $params['ToquvDocumentBalanceSearch']['from_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['to_date'])){
                $data['to_date'] = $params['ToquvDocumentBalanceSearch']['to_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['group_by_lot'])){
                $data['group_by_ip'] = $params['ToquvDocumentBalanceSearch']['group_by_ip'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['ToquvDocumentBalanceSearch']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $data['name'] = $dpt['name'];
                }
            }
        }
        $items = $searchModel->searchMato($params);

        return $this->render('report-model-mato-sklad', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    public function actionReportExportMatoSklad(){

        $searchModel = new ToquvDocumentBalanceSearch();
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '2019-11-01 00:00:00';
        $data['to_date'] = date('Y-m-d H:i:s', strtotime('tomorrow'));
        $searchModel['created_at'] = "{$data['from_date']} - {$data['to_date']}";
        $data['group_by_lot'] = false;
        $data['name'] = Yii::t('app', "Bo'lim tanlanmagan");
        if(!empty($params['ToquvDocumentBalanceSearch'])){
            if(!empty($params['ToquvDocumentBalanceSearch']['from_date'])){
                $data['from_date'] = $params['ToquvDocumentBalanceSearch']['from_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['to_date'])){
                $data['to_date'] = $params['ToquvDocumentBalanceSearch']['to_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['group_by_lot'])){
                $data['group_by_ip'] = $params['ToquvDocumentBalanceSearch']['group_by_ip'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['ToquvDocumentBalanceSearch']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $data['name'] = $dpt['name'];
                }
            }
        }
        $items = $searchModel->searchMato($params,null,ToquvOrders::ORDER_EXPORT);

        return $this->render('report-export-mato-sklad', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    public function actionReportAksessuarSklad(){

        $searchModel = new ToquvDocumentBalanceSearch();
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '2019-11-01 00:00:00';
        $data['to_date'] = date('Y-m-d H:i:s', strtotime('tomorrow'));
        $searchModel['created_at'] = "{$data['from_date']} - {$data['to_date']}";
        $data['group_by_lot'] = false;
        $data['name'] = Yii::t('app', "Bo'lim tanlanmagan");
        if(!empty($params['ToquvDocumentBalanceSearch'])){
            if(!empty($params['ToquvDocumentBalanceSearch']['from_date'])){
                $data['from_date'] = $params['ToquvDocumentBalanceSearch']['from_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['to_date'])){
                $data['to_date'] = $params['ToquvDocumentBalanceSearch']['to_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['group_by_lot'])){
                $data['group_by_ip'] = $params['ToquvDocumentBalanceSearch']['group_by_ip'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['ToquvDocumentBalanceSearch']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $data['name'] = $dpt['name'];
                }
            }
        }
        $items = $searchModel->searchAksessuar($params,$this->acs);
        $type = ToquvRawMaterials::ACS;
        return $this->render('report-aksessuar-sklad', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data,
            'entity_type' => $type,
        ]);
    }
    public function actionReportModelAksessuarSklad(){

        $searchModel = new ToquvDocumentBalanceSearch();
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '2019-11-01 00:00:00';
        $data['to_date'] = date('Y-m-d H:i:s', strtotime('tomorrow'));
        $searchModel['created_at'] = "{$data['from_date']} - {$data['to_date']}";
        $data['group_by_lot'] = false;
        $data['name'] = Yii::t('app', "Bo'lim tanlanmagan");
        if(!empty($params['ToquvDocumentBalanceSearch'])){
            if(!empty($params['ToquvDocumentBalanceSearch']['from_date'])){
                $data['from_date'] = $params['ToquvDocumentBalanceSearch']['from_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['to_date'])){
                $data['to_date'] = $params['ToquvDocumentBalanceSearch']['to_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['group_by_lot'])){
                $data['group_by_ip'] = $params['ToquvDocumentBalanceSearch']['group_by_ip'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['ToquvDocumentBalanceSearch']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $data['name'] = $dpt['name'];
                }
            }
        }
        $items = $searchModel->searchAksessuar($params,$this->acs);
        $type = ToquvRawMaterials::ACS;
        return $this->render('report-model-aksessuar-sklad', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data,
            'entity_type' => $type,
        ]);
    }
    public function actionMusteri($id)
    {
        $response = ['status'=>false];
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;

            $response['status'] = true;
            $response['data'] = TikuvOutcomeProductsPack::getMusteriList($id);
        }
        return $response;
    }
    public function actionOrder($id)
    {
        $response = ['status'=>false];
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response['status'] = true;
            $response['data'] = TikuvOutcomeProductsPack::getOrderList($id);
        }
        return $response;
    }
    public function actionOrderItems($id)
    {
        $model = ModelOrdersItems::findOne($id);
        if (Yii::$app->request->isAjax){
            return $this->renderAjax('order-items', [
                'model' => $model,
                'size' => $model->modelOrdersItemsSizes,
            ]);
        }
        return $this->render('order-items', [
            'model' => $model,
            'size' => $model->modelOrdersItemsSizes,
        ]);
    }
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionReportMoving(){

        $searchModel = new RemainSearchModel();
        $searchModel->setScenario(RemainSearchModel::SCENARIO_MOVING);
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '01.01.2019';
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));
        $data['name'] = Yii::t('app', "Bo'lim tanlanmagan");
        if(!empty($params['RemainSearchModel'])){
            if(!empty($params['RemainSearchModel']['from_date'])){
                $data['from_date'] = $params['RemainSearchModel']['from_date'];
            }
            if(!empty($params['RemainSearchModel']['to_date'])){
                $data['to_date'] = $params['RemainSearchModel']['to_date'];
            }
            if(!empty($params['RemainSearchModel']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchModel']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $data['name'] = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchModel']['is_own'])){
                $data['isOwn'] = $params['RemainSearchModel']['is_own'];
            }
        }


        $items = $searchModel->search($params, ToquvDocuments::DOC_TYPE_MOVING);

        return $this->render('report-moving', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    public function actionReportOutcoming(){

        $searchModel = new RemainSearchModel();
        $searchModel->setScenario(RemainSearchModel::SCENARIO_OUTCOMING);
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '01.01.2019';
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));
        $data['name'] = Yii::t('app', "Bo'lim tanlanmagan");
        if(!empty($params['RemainSearchModel'])){
            if(!empty($params['RemainSearchModel']['from_date'])){
                $data['from_date'] = $params['RemainSearchModel']['from_date'];
            }
            if(!empty($params['RemainSearchModel']['to_date'])){
                $data['to_date'] = $params['RemainSearchModel']['to_date'];
            }
            if(!empty($params['RemainSearchModel']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchModel']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $data['name'] = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchModel']['is_own'])){
                $data['isOwn'] = $params['RemainSearchModel']['is_own'];
            }
        }


        $items = $searchModel->search($params, ToquvDocuments::DOC_TYPE_OUTCOMING);

        return $this->render('report-outcoming', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }

    public function actionReportWriteOff(){

        $searchModel = new RemainSearchModel();
        $searchModel->setScenario(RemainSearchModel::SCENARIO_WRITE_OFF);
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '01.01.2019';
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));
        $data['name'] = Yii::t('app', "Bo'lim tanlanmagan");
        $data['isOwn'] = "";
        if(!empty($params['RemainSearchModel'])){
            if(!empty($params['RemainSearchModel']['from_date'])){
                $data['from_date'] = $params['RemainSearchModel']['from_date'];
            }
            if(!empty($params['RemainSearchModel']['to_date'])){
                $data['to_date'] = $params['RemainSearchModel']['to_date'];
            }
            if(!empty($params['RemainSearchModel']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['status' => $params['RemainSearchModel']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $data['name'] = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchModel']['is_own'])){
                $data['isOwn'] = $params['RemainSearchModel']['is_own'];
            }
        }

        $items = $searchModel->search($params, ToquvDocuments::DOC_TYPE_WRITE_OFF_GOODS);

        return $this->render('report-write-off', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    public function actionReportKalite(){
        $searchModel = new RemainSearchMato();
        $searchModel->setScenario(RemainSearchMato::SCENARIO_KALITE);
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = date('Y').'-'.date('m').'-01 00:00:00';
        $data['to_date'] = date('Y-m-d H:i:s', strtotime('tomorrow'));
        $from_dept = Yii::t('app', "Barcha bo'limlar");
        $to_dept = Yii::t('app', "Barcha bo'limlar");
        if(!empty($params['RemainSearchMato'])){
            if(!empty($params['RemainSearchMato']['from_date'])){
                $data['from_date'] = $params['RemainSearchMato']['from_date'];
            }
            if(!empty($params['RemainSearchMato']['to_date'])){
                $data['to_date'] = $params['RemainSearchMato']['to_date'];
            }
            if(!empty($params['RemainSearchMato']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $from_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['to_department'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['to_department']])->asArray()->one();
                if(!empty($dpt)){
                    $to_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['is_own'])){
                $data['isOwn'] = $params['RemainSearchMato']['is_own'];
            }
        }
        $data['name'] = $from_dept. " -> ". $to_dept;

        $items = $searchModel->searchMatoKalite($params);
        return $this->render('report-kalite', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    public function actionReportAllMato(){

        $searchModel = new RemainSearchMato();
        $searchModel->setScenario(RemainSearchMato::SCENARIO_ALL);
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '01.01.2019 00:00:00';
        $data['to_date'] = date('d.m.Y H:i:s', strtotime('tomorrow'));
        $from_dept = Yii::t('app', "Barcha bo'limlar");
        if(!empty($params['RemainSearchMato'])){
            if(!empty($params['RemainSearchMato']['from_date'])){
                $data['from_date'] = $params['RemainSearchMato']['from_date'];
            }
            if(!empty($params['RemainSearchMato']['to_date'])){
                $data['to_date'] = $params['RemainSearchMato']['to_date'];
            }
            if(!empty($params['RemainSearchMato']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $from_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['is_own'])){
                $data['isOwn'] = $params['RemainSearchMato']['is_own'];
            }
        }
        $data['name'] = $from_dept;

        $items = $searchModel->searchAll($params);
        $chiqim = $searchModel->searchAll($params,[2,5]);
        $kalite = $searchModel->searchKalite($params);
        $kalite_send = $searchModel->searchKalite($params,null,3);
        $kalite_no_send = $searchModel->searchKalite($params, null, 1);
        $sklad = $searchModel->searchMato($params);
        $brak = $searchModel->searchKalite($params,3);
        return $this->render('report-all-mato', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data,
            'chiqim' => $chiqim,
            'kalite' => $kalite,
            'brak' => $brak,
            'sklad' => $sklad,
            'department' => $searchModel['department_id'],
            'kalite_send' => $kalite_send,
            'kalite_no_send' => $kalite_no_send,
        ]);
    }
    public function actionReportIncomingMato(){

        $searchModel = new RemainSearchMato();
        $searchModel->setScenario(RemainSearchMato::SCENARIO_INCOMING);
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = date('Y-m-d H:i', strtotime("-1 day"));
        $data['to_date'] = date('Y-m-d H:i');
        $from_dept = Yii::t('app', "Barcha bo'limlar");
        $to_dept = Yii::t('app', "Barcha bo'limlar");
        if(!empty($params['RemainSearchMato'])){
            if(!empty($params['RemainSearchMato']['from_date'])){
                $data['from_date'] = $params['RemainSearchMato']['from_date'];
            }
            if(!empty($params['RemainSearchMato']['to_date'])){
                $data['to_date'] = $params['RemainSearchMato']['to_date'];
            }
            if(!empty($params['RemainSearchMato']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $from_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['to_department'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['to_department']])->asArray()->one();
                if(!empty($dpt)){
                    $to_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['is_own'])){
                $data['isOwn'] = $params['RemainSearchMato']['is_own'];
            }
        }
        $data['name'] = $from_dept. " -> ". $to_dept;

        $items = $searchModel->search($params, ToquvDocuments::DOC_TYPE_INCOMING);
        return $this->render('report-incoming-mato', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    public function actionReportMovingMato(){

        $searchModel = new RemainSearchMato();
        $searchModel->setScenario(RemainSearchMato::SCENARIO_MOVING);
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = date('Y-m-d H:i', strtotime("-1 day"));
        $data['to_date'] = date('Y-m-d H:i');
        $from_dept = Yii::t('app', "Barcha bo'limlar");
        $to_dept = Yii::t('app', "Barcha bo'limlar");
        if(!empty($params['RemainSearchMato'])){
            if(!empty($params['RemainSearchMato']['from_date'])){
                $data['from_date'] = $params['RemainSearchMato']['from_date'];
            }
            if(!empty($params['RemainSearchMato']['to_date'])){
                $data['to_date'] = $params['RemainSearchMato']['to_date'];
            }
            if(!empty($params['RemainSearchMato']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $from_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['to_department'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['to_department']])->asArray()->one();
                if(!empty($dpt)){
                    $to_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['is_own'])){
                $data['isOwn'] = $params['RemainSearchMato']['is_own'];
            }
        }
        $data['name'] = $from_dept. " -> ". $to_dept;

        $items = $searchModel->search($params, ToquvDocuments::DOC_TYPE_MOVING);

        return $this->render('report-moving-mato', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    public function actionReportInsideMovingMato(){

        $searchModel = new RemainSearchMato();
        $searchModel->setScenario(RemainSearchMato::SCENARIO_INSIDE_MOVING);
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = date('Y-m-d H:i', strtotime("-1 day"));
        $data['to_date'] = date('Y-m-d H:i');
        $from_dept = Yii::t('app', "Barcha mijozlar");
        $to_dept = Yii::t('app', "Barcha mijozlar");
        if(!empty($params['RemainSearchMato'])){
            if(!empty($params['RemainSearchMato']['from_date'])){
                $data['from_date'] = $params['RemainSearchMato']['from_date'];
            }
            if(!empty($params['RemainSearchMato']['to_date'])){
                $data['to_date'] = $params['RemainSearchMato']['to_date'];
            }
            if(!empty($params['RemainSearchMato']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $from_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['to_department'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['to_department']])->asArray()->one();
                if(!empty($dpt)){
                    $to_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['is_own'])){
                $data['isOwn'] = $params['RemainSearchMato']['is_own'];
            }
        }
        $data['name'] = $from_dept. " -> ". $to_dept;

        $items = $searchModel->search($params, ToquvDocuments::DOC_TYPE_INSIDE_MOVING);

        return $this->render('report-inside-moving-mato', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    public function actionReportOutcomingMato(){

        $searchModel = new RemainSearchMato();
        $searchModel->setScenario(RemainSearchMato::SCENARIO_OUTCOMING);
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '01.01.2019';
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));
        $from_dept = Yii::t('app', "Barcha bo'limlar");
        $to_dept = Yii::t('app', "Barcha bo'limlar");
        if(!empty($params['RemainSearchMato'])){
            if(!empty($params['RemainSearchMato']['from_date'])){
                $data['from_date'] = $params['RemainSearchMato']['from_date'];
            }
            if(!empty($params['RemainSearchMato']['to_date'])){
                $data['to_date'] = $params['RemainSearchMato']['to_date'];
            }
            if(!empty($params['RemainSearchMato']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $from_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['to_department'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['to_department']])->asArray()->one();
                if(!empty($dpt)){
                    $to_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['is_own'])){
                $data['isOwn'] = $params['RemainSearchMato']['is_own'];
            }
        }
        $data['name'] = $from_dept. " -> ". $to_dept;

        $items = $searchModel->search($params, ToquvDocuments::DOC_TYPE_OUTCOMING);

        return $this->render('report-outcoming-mato', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    public function actionReportIncomingAksessuar(){

        $searchModel = new RemainSearchMato();
        $searchModel->setScenario(RemainSearchMato::SCENARIO_INCOMING);
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '01.01.2019';
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));
        $from_dept = Yii::t('app', "Barcha bo'limlar");
        $to_dept = Yii::t('app', "Barcha bo'limlar");
        if(!empty($params['RemainSearchMato'])){
            if(!empty($params['RemainSearchMato']['from_date'])){
                $data['from_date'] = $params['RemainSearchMato']['from_date'];
            }
            if(!empty($params['RemainSearchMato']['to_date'])){
                $data['to_date'] = $params['RemainSearchMato']['to_date'];
            }
            if(!empty($params['RemainSearchMato']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $from_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['to_department'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['to_department']])->asArray()->one();
                if(!empty($dpt)){
                    $to_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['is_own'])){
                $data['isOwn'] = $params['RemainSearchMato']['is_own'];
            }
        }
        $data['name'] = $from_dept. " -> ". $to_dept;

        $items = $searchModel->search($params, ToquvDocuments::DOC_TYPE_INCOMING);
        return $this->render('report-incoming-aksessuar', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    public function actionReportMovingAksessuar(){

        $searchModel = new RemainSearchMato();
        $searchModel->setScenario(RemainSearchMato::SCENARIO_MOVING);
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '01.01.2019';
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));
        $from_dept = Yii::t('app', "Barcha bo'limlar");
        $to_dept = Yii::t('app', "Barcha bo'limlar");
        if(!empty($params['RemainSearchMato'])){
            if(!empty($params['RemainSearchMato']['from_date'])){
                $data['from_date'] = $params['RemainSearchMato']['from_date'];
            }
            if(!empty($params['RemainSearchMato']['to_date'])){
                $data['to_date'] = $params['RemainSearchMato']['to_date'];
            }
            if(!empty($params['RemainSearchMato']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $from_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['to_department'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['to_department']])->asArray()->one();
                if(!empty($dpt)){
                    $to_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['is_own'])){
                $data['isOwn'] = $params['RemainSearchMato']['is_own'];
            }
        }
        $data['name'] = $from_dept. " -> ". $to_dept;

        $items = $searchModel->search($params, ToquvDocuments::DOC_TYPE_MOVING);

        return $this->render('report-moving-aksessuar', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    public function actionReportOutcomingAksessuar(){

        $searchModel = new RemainSearchMato();
        $searchModel->setScenario(RemainSearchMato::SCENARIO_OUTCOMING);
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '01.01.2019';
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));
        $from_dept = Yii::t('app', "Barcha bo'limlar");
        $to_dept = Yii::t('app', "Barcha bo'limlar");
        if(!empty($params['RemainSearchMato'])){
            if(!empty($params['RemainSearchMato']['from_date'])){
                $data['from_date'] = $params['RemainSearchMato']['from_date'];
            }
            if(!empty($params['RemainSearchMato']['to_date'])){
                $data['to_date'] = $params['RemainSearchMato']['to_date'];
            }
            if(!empty($params['RemainSearchMato']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $from_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['to_department'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['RemainSearchMato']['to_department']])->asArray()->one();
                if(!empty($dpt)){
                    $to_dept = $dpt['name'];
                }
            }
            if(!empty($params['RemainSearchMato']['is_own'])){
                $data['isOwn'] = $params['RemainSearchMato']['is_own'];
            }
        }
        $data['name'] = $from_dept. " -> ". $to_dept;

        $items = $searchModel->search($params, ToquvDocuments::DOC_TYPE_OUTCOMING);

        return $this->render('report-outcoming-aksessuar', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }

    public function actionReportMatoIp(){

        $searchModel = new ToquvDocumentBalanceSearch();
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '01-11-2019 00:00:00';
        $data['to_date'] = date('d-m-Y H:i:s', strtotime('tomorrow'));
        $data['group_by_lot'] = false;
        $data['name'] = Yii::t('app', "Bo'lim tanlanmagan");
        if(!empty($params['ToquvDocumentBalanceSearch'])){
            if(!empty($params['ToquvDocumentBalanceSearch']['from_date'])){
                $data['from_date'] = $params['ToquvDocumentBalanceSearch']['from_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['to_date'])){
                $data['to_date'] = $params['ToquvDocumentBalanceSearch']['to_date'];
            }
            if(!empty($params['ToquvDocumentBalanceSearch']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['id' => $params['ToquvDocumentBalanceSearch']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $data['name'] = $dpt['name'];
                }
            }
        }
        $items = $searchModel->searchMatoIp($params);

        return $this->render('report-mato-ip', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionReportService(){
        $searchModel = new RemainSearchModel();
        $searchModel->setScenario(RemainSearchModel::SCENARIO_SERVICE);
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = '01.01.2019';
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));
        $data['name'] = Yii::t('app', "Bo'lim tanlanmagan");
        if(!empty($params['RemainSearchModel'])){
            if(!empty($params['RemainSearchModel']['from_date'])){
                $data['from_date'] = $params['RemainSearchModel']['from_date'];
            }
            if(!empty($params['RemainSearchModel']['to_date'])){
                $data['to_date'] = $params['RemainSearchModel']['to_date'];
            }
            if(!empty($params['RemainSearchModel']['department_id'])){
                $dpt = ToquvDepartments::find()->where(['status' => $params['RemainSearchModel']['department_id']])->asArray()->one();
                if(!empty($dpt)){
                    $data['name'] = $dpt['name'];
                }
            }
        }
        $items = $searchModel->search($params, ToquvDocuments::DOC_TYPE_SERVICE);

        return $this->render('report-service', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }

    /**
     * Displays a single ToquvItemBalance model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ToquvItemBalance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvItemBalance();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ToquvItemBalance model.
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
     * Deletes an existing ToquvItemBalance model.
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
     * Finds the ToquvItemBalance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvItemBalance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvItemBalance::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
