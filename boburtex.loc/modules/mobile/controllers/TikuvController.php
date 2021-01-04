<?php


namespace app\modules\mobile\controllers;

use app\models\Constants;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
use app\modules\hr\models\HrEmployeeUsers;
use app\modules\mobile\components\Menu;
use app\modules\mobile\models\MobileProcess;
use app\modules\mobile\models\MobileTables;
use app\modules\mobile\models\MobileTablesRelHrEmployee;
use app\modules\tikuv\models\TikuvDoc;
use app\modules\tikuv\models\TikuvDocItems;
use app\modules\tikuv\models\TikuvSliceItemBalance;
use Yii;
use yii\base\Model;
use yii\helpers\Inflector;
use yii\web\Controller;
use app\modules\mobile\models\SearchFormViaNastel;
use yii\web\NotFoundHttpException;

class TikuvController extends Controller
{
    public $slug;
    public $currentUserId;
    public $mobileTable;

    public function init()
    {
        parent::init();

        $this->currentUserId = Yii::$app->user->identity->id;
    }

    public function beforeAction($action)
    {
        // your custom code here, if you want the code to run before action filters,
        // which are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here
        $this->slug = Yii::$app->request->get('slug');
        if (!in_array($action->id, ['process-menu'])) {
            $this->mobileTable = MobileTables::getTableByUserIdAndProcessToken($this->currentUserId, $this->slug);
        }

        return true; // or false to not run the action
    }

    public function actionIndex() {
        return $this->render('menu');
    }

    public function actionProcessMenu($department_id)
    {
        $processesMenuItems = Menu::getProcessMenuItemsByDepartmentId($department_id);

        return $this->render('process-menu', [
            'processesMenuItems' => $processesMenuItems,
        ]);
    }

    public function actionConveyorIn() {
        $searchModel = new SearchFormViaNastel();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params, TikuvDoc::DOC_TYPE_ACCEPTED, $this->slug);
        return $this->render('conveyor-in',['dataProvider' => $dataProvider, 'model' => $searchModel]);
    }

    public function actionConveyorOut() {
        $searchModel = new SearchFormViaNastel();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params, TikuvDoc::DOC_TYPE_MOVING, $this->slug);
        return $this->render('conveyor-out',['dataProvider' => $dataProvider, 'model' => $searchModel]);
    }

    public function actionView($id) {
        $request = Yii::$app->getRequest();
        $model = $this->findModel($id);
        $models = $model->tikuvDocItems;

        if ($request->isAjax) {
            return $this->renderAjax('accept_slice', [
                'model' => $model,
                'models' => $models,
            ]);
        }
        throw new NotFoundHttpException(Yii::t('app', 'The request must be ajax'));
    }

    public function actionTransfer() {
        $request = Yii::$app->getRequest();
        $model = new TikuvDoc();
        $models = [];

        $currentDepartmentId = HrDepartments::getDepartmentIdByToken(HrDepartments::TOKEN_TIKUV);
        if ($currentDepartmentId === false) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'The department does not exist') . ': ' . HrDepartments::TOKEN_TIKUV);
            Yii::error(Yii::t('app', 'The department does not exist') . ': ' . HrDepartments::TOKEN_TIKUV);
        }

        $nextProcess = MobileProcess::getNextProcessInstanceByTableId($this->mobileTable['id']);
        $nextTable = MobileTables::getNextTableInstanceByProcessId($nextProcess['id']);

        if ($nextProcess === null) {
            Yii::error('Keyingi jarayon topilmadi');
            Yii::$app->session->setFlash('error', Yii::t('app', 'The next process was not found'));
            return $this->redirect(['conveyor-out', 'slug' => $this->slug]);
        }

        //init doc
        $model->doc_number = 'T'.TikuvDoc::getNextId();
        $model->document_type = TikuvDoc::DOC_TYPE_MOVING;
        $model->reg_date = date('Y-m-d');
        $model->from_hr_department  = HrDepartments::getDepartmentIdByToken(HrDepartments::TOKEN_TIKUV);
        $model->to_hr_department = $nextProcess['department_id'];
        $model->from_hr_employee = MobileTablesRelHrEmployee::getResponsiblePersonByTableId($this->mobileTable['id'])['id'];//HrEmployeeUsers::getEmployeeByUserId(Yii::$app->user->identity->id);//MobileTablesRelHrEmployee::getResponsiblePersonByTableId()
        $model->to_hr_employee = MobileTablesRelHrEmployee::getResponsiblePersonByTableId($nextTable['id'])['id'];
        $model->mobile_table_id = $this->mobileTable['id'];//$nextTable['id'];
        $model->mobile_process_id = $this->mobileTable['mobile_process_id'];//$nextProcess['id'];

        if ($request->isPost && $model->load($request->post())) {
            $docItems = $request->post('TikuvDocItems', []);
            foreach (array_keys($docItems) as $index) {
                $models[$index] = new TikuvDocItems();
            }

            Model::loadMultiple($models, $request->post());

            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try {
                $mobileTableToken = Constants::TOKEN_TIKUV_CONVEYOR_OUT; // TODO: har bir jarayonni dinamik olib kelish kerak
                // hujjatni saqlash
                $saved = $model->$mobileTableToken($request, $model, $models);

                if ($saved) {
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
            } catch (\Throwable $e) {
                Yii::error($e->getMessage(),'save');
                $transaction->rollBack();
                $saved = false;
            }

            if ($saved) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Successfully shipped'));
                return $this->redirect(['conveyor-out', 'slug' => $this->slug]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'An error occurred'));
            }
        }

        return $this->render('transfer', [
            'model' => $model,
            'models' => $models,
            'nextProcess' => $nextProcess,
        ]);
    }

    public function actionAccept($id) {
        $request = Yii::$app->getRequest();
        $model = $this->findModel($id);

        if ($model->status >= TikuvDoc::STATUS_SAVED) {
            return $this->asJson([
                'success' => false,
                'text' => Yii::t('app', 'No such digital cut was found'),
            ]);
        }

        $models = $model->tikuvDocItems;

        /** change scenario */
        if (is_countable($models)) {
            foreach ($models as $tikuvDocItem) {
                $tikuvDocItem->setScenario(TikuvDocItems::SCENARIO_ACCEPT_SLICE);
            }
        }

        if ($request->isPost) {
            TikuvDocItems::loadMultiple($models, $request->post());

            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try {
                $mobileTableToken = Constants::TOKEN_TIKUV_ACCEPT_SLICE;
                // hujjatni saqlash
                $saved = $model->$mobileTableToken($request, $model, $models);

                if ($saved) {
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
            } catch (\Throwable $e) {
                Yii::error($e->getMessage());
                $transaction->rollBack();
                $saved = false;
            }

            if ($saved) {
                return $this->asJson(['success' => true]);
            } else {
                return $this->asJson(['success' => false]);
            }
        }

        return $this->asJson([
            'success' => false,
            'text' => Yii::t('app', 'An error occurred'),
        ]);
    }

    public function actionSearchRemain($nastelNo) {
        $request = Yii::$app->getRequest();
        $validatedNastelNoValue = trim(strval($nastelNo));
        $departmentId = HrDepartments::getDepartmentIdByToken(HrDepartments::TOKEN_TIKUV);

        if ($request->isAjax) {
            $result = TikuvSliceItemBalance::searchRemainByNastelNo($validatedNastelNoValue, $departmentId, $this->mobileTable);

            if ($result) {
                return $this->asJson([
                    'success' => true,
                    'items' => $result,
                    'message' => Yii::t('app', 'Data found'),
                ]);
            }

            return $this->asJson([
                'success' => false,
                'message' => Yii::t('app', 'No data found'),
            ]);
        }
    }

    /**
     * @param $id
     * @return TikuvDoc|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id) {
        if (($model = TikuvDoc::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}