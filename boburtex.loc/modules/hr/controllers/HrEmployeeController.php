<?php

namespace app\modules\hr\controllers;

use app\modules\hr\models\EmployeeRelSkills;
use app\modules\hr\models\form\UploadForm;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployeeForm;
use app\modules\hr\models\HrEmployeeRelAttachment;
use app\modules\hr\models\HrEmployeeStudy;
use app\modules\hr\models\HrEmployeeWorkPlace;
use app\modules\hr\models\HrExportEmployee;
use Prophecy\Doubler\CachedDoubler;
use Yii;
use app\modules\hr\models\HrEmployee;
use app\modules\hr\models\HrEmployeeSearch;
use yii\base\ErrorException;
use yii\base\Model;
use yii\bootstrap\ActiveForm;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * HrEmployeeController implements the CRUD actions for HrEmployee model.
 */
class HrEmployeeController extends BaseController
{

    /**
     * Lists all HrEmployee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HrEmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetEmployeeViaAjax()
    {
        $out = [];

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $q = Yii::$app->request->get('q');
            $out['results'] = [
                'id' => null,
                'text' => null
            ];
            $out['status'] = false;
            if (!empty($q) && strlen($q)) {
                $employees = HrEmployee::find()->where(['like', 'fish', $q])->asArray()->all();
                if (!empty($employees)) {
                    $out['results'] = [];
                    $out['status'] = true;
                    foreach ($employees as $employee) {
                        array_push($out['results'], [
                            'id' => $employee['id'],
                            'text' => $employee['fish']
                        ]);
                    }
                }
            }
        }
        return $out;
    }

    public function actionViewGraph()
    {
        $dept = HrDepartments::find();
        $model = new HrEmployeeForm();
        $params = Yii::$app->request->queryParams;
        if (!empty($params)) {
            $employees = $model->search($params);
        } else {
            $employees = HrEmployee::find()->where(['status' => 1])->asArray()->limit(10)->orderBy(['id' => SORT_DESC])->all();
        }
        return $this->render('view-tree', [
            'dept' => $dept,
            'model' => $model,
            'employees' => $employees,
        ]);
    }

    /**
     * Displays a single HrEmployee model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $work = HrEmployeeWorkPlace::find()
            ->where(['hr_employee_id' => $id])
            ->all();
        $study = HrEmployeeStudy::find()
            ->where(['hr_employee_id' => $id])
            ->all();
//        $attachment = HrEmployeeRelAttachment::find()->where(['hr_employee_id' => $id])->asArray()->all();
        $attachment = $model->getHrEmployeeRelAttachments()->orderBy(['extension' => SORT_DESC])->asArray()->all();
        $skills = $model->employeeRelSkills;
        return $this->render('view', [
            'model' => $model,
            'work' => $work,
            'study' => $study,
            'attachment' => $attachment,
            'skills' => $skills,
        ]);
    }

    /**
     * Creates a new HrEmployee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HrEmployee();
        $attachment = new HrEmployeeRelAttachment();
        $study = [new HrEmployeeStudy()];
        $work = [new HrEmployeeWorkPlace()];
        $skills = [new EmployeeRelSkills()];
        $attachmentAll = new UploadForm();
        $imageUploadForm = new UploadForm(['scenario' => UploadForm::SCENARIO_UPLOAD_IMAGE]);
        $imageUploadForm->skipOnEmpty = false;

        if (Yii::$app->request->isPost) {
            $request = Yii::$app->getRequest();
            $postData = $request->post();

            $skillData = $request->post('EmployeeRelSkills', []);
            foreach (array_keys($skillData) as $index) {
                $skills[$index] = new EmployeeRelSkills();
            }

            $study = $request->post('HrEmployeeStudy', []);
            foreach (array_keys($study) as $index) {
                $study[$index] = new HrEmployeeStudy();
            }

            $work = $request->post('HrEmployeeWorkPlace', []);
            foreach (array_keys($work) as $index) {
                $work[$index] = new HrEmployeeWorkPlace();
            }

            /** barcha modellar ma'lumotlarini o'qib olish */
            if ($model->load($postData))
            {
                // bu modellar majburiy emas;
                Model::loadMultiple($skills, $postData);
                Model::loadMultiple($study, $postData);
                Model::loadMultiple($work, $postData);

                $isAllSaved = true;
                $transaction = Yii::$app->db->beginTransaction();
                $validationErrors = [];
                $errorModel = 'undefined';
                try {
                    /** asosiy xodim ma'lumotlarini saqlash */
                    $isAllSaved = $isAllSaved && $model->save();
                    if (!$isAllSaved) {
                        $validationErrors = $model->getErrors();
                        $errorModel = get_class($model);
                        throw new ErrorException();
                    }

                    /** o'qigan joylarini saqlash */
                    if ($study && $isAllSaved) {
                        /** @var HrEmployeeStudy $studyItem */
                        foreach ($study as $key => $studyItem) {
                            if ($key == 0
                                && empty($studyItem->where_studied)
                                && empty($studyItem->from)
                                && empty($studyItem->to)
                                && empty($studyItem->level)) { // TODO: bo'shligini tekshirishni qayta ko'rish kerak
                                break;
                            }
                            $studyItem->hr_employee_id = $model->id;
                            $isAllSaved = $isAllSaved && $studyItem->save();
                            if (!$isAllSaved) {
                                $validationErrors = $studyItem->getErrors();
                                $errorModel = get_class($studyItem);
                                throw new ErrorException();
                            }
                        }
                    }

                    /** barcha mehnat faoliyatlarini saqlash */
                    if ($work && $isAllSaved) {
                        /** @var HrEmployeeWorkPlace $workItem */
                        foreach ($work as $key => $workItem) {
                            if ($key == 0
                                && empty($workItem->organization)
                                && empty($workItem->from)
                                && empty($workItem->to)
                                && empty($workItem->position)) { // TODO: bo'shligini tekshirishni qayta ko'rish kerak
                                break;
                            }
                            $workItem->hr_employee_id = $model->id;
                            $isAllSaved = $isAllSaved && $workItem->save();
                            if (!$isAllSaved) {
                                $validationErrors = $workItem->getErrors();
                                $errorModel = get_class($workItem);
                                throw new ErrorException();
                            }
                        }
                    }

                    /** save employee skills */
                    if ($isAllSaved && $skills) {
                        /** @var EmployeeRelSkills $skill */
                        foreach ($skills as $key => $skill) {
                            if ($key == 0
                                && empty($skill->employee_skills_id)
                                && empty($skill->add_info)) { // TODO: bo'shligini tekshirishni qayta ko'rish kerak
                                break;
                            }
                            $skill->hr_employee_id = $model->id;
                            $isAllSaved = $isAllSaved && $skill->save();
                            if (!$isAllSaved) {
                                $validationErrors = $skill->getErrors();
                                $errorModel = get_class($skill);
                                throw new ErrorException();
                            }
                        }
                    }

                    /** xodim rasmini saqlash */
                    $imageUploadForm->imageFile = UploadedFile::getInstance($imageUploadForm, 'imageFile');
                    if($isAllSaved && $imageUploadForm->validate() && $imageUploadForm->imageFile !== null){
                        if ($savedImageInfo = $imageUploadForm->uploadImage()) {
                            $attachmentModel2 = new HrEmployeeRelAttachment();
                            $attachmentModel2->setAttributes($savedImageInfo);
                            $attachmentModel2->hr_employee_id = $model->id;
                            $isAllSaved = $isAllSaved && $attachmentModel2->save();
                            if (!$isAllSaved) {
                                $validationErrors = $attachmentModel2->getErrors();
                                $errorModel = get_class($attachmentModel2);
                                throw new ErrorException();
                            }
                        } else {
                            $validationErrors = $imageUploadForm->getErrors();
                            $errorModel = get_class($imageUploadForm);
                            throw new ErrorException();
                        }
                    }

                    // save additional files
                    $attachmentAll->file = UploadedFile::getInstances($attachmentAll, 'file');
                    if ($isAllSaved && $attachmentAll->file && ($savedFiles = $attachmentAll->uploadMultiple())) {
                        foreach ($savedFiles as $savedFile) {
                            $attachmentModel2 = new HrEmployeeRelAttachment();
                            $attachmentModel2->setAttributes($savedFile);
                            $attachmentModel2->hr_employee_id = $model->id;
                            $isAllSaved = $isAllSaved && $attachmentModel2->save();
                            if (!$isAllSaved) {
                                $validationErrors = $attachmentModel2->getErrors();
                                $errorModel = get_class($attachmentModel2);
                                throw new ErrorException();
                            }
                        }
                        if ($savedFiles === false) {
                            $validationErrors = $attachmentAll->getErrors();
                            $errorModel = get_class($attachmentAll);
                            throw new ErrorException();
                        }
                    }
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                    return $this->redirect(['update', 'id' => $model->id]);
                }
                catch (ErrorException $baseException) {
                    $transaction->rollBack();
                    $validationErrors['errorModel'] = $errorModel;
                    Yii::error($baseException->getMessage(),  'save');
                    Yii::error($validationErrors,  'save');
                    Yii::$app->session->setFlash('danger', Yii::t('app', 'An error occurred'));
                }
                catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::error($e->getMessage(), 'save');
                    Yii::$app->session->setFlash('danger', Yii::t('app', 'Error exception'));
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'attachment' => $attachment,
            'study' => $study,
            'work' => $work,
            'attachmentAll' => $attachmentAll,
            'imageUploadForm' => $imageUploadForm,
            'skills' => $skills,
        ]);
    }

    /**
     * Updates an existing HrEmployee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $url = $_SERVER['HTTP_REFERER'];
        $model = $this->findModel($id);
        $attachment = new HrEmployeeRelAttachment();
        $attachmentAll = new UploadForm();
        $imageUploadForm = new UploadForm(['scenario' => UploadForm::SCENARIO_UPLOAD_IMAGE]);
        $study = $model->hrEmployeeStudies;
        $work = $model->hrEmployeeWorkPlaces;
        $skills = $model->employeeRelSkills ? $model->employeeRelSkills : [new EmployeeRelSkills()];
        $attachmentAllOldImages = $model->getHrEmployeeRelAttachments()
            ->where(['type' => 1])
            ->orderBy('extension')
            ->asArray()
            ->all();

        // malumotlar bosh bolsa
        if (empty($study))
            $study = [new HrEmployeeStudy()];
        if (empty($work))
            $work = [new HrEmployeeWorkPlace()];


        $isPost = Yii::$app->request->post();

        if (Yii::$app->request->isAjax && $model->load($isPost)) {
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($model);
        }

        $skillData = Yii::$app->request->post('EmployeeRelSkills', []);
        foreach (array_keys($skillData) as $index) {
            $skills[$index] = new EmployeeRelSkills();
        }

        if ($model->load($isPost) && Model::loadMultiple($skills, $isPost) && $model->save()) {
            $results = $isPost;
            $work = new HrEmployeeWorkPlace();
            $study = new HrEmployeeStudy();
            $saved = false;

            $transaction = Yii::$app->db->beginTransaction();
            try{
                /** xodim rasmini saqlash */
                $imageUploadForm->imageFile = UploadedFile::getInstance($imageUploadForm, 'imageFile');
                if($imageUploadForm->imageFile !== null){
                    // tekshiramiz oldingi rasmi bormi yoqmi
                    $isImage = HrEmployeeRelAttachment::findOne(['id' => $id]);
                    $is = false;
                    if($isImage !== null){
                        $del = $isImage->delete();
                    }

                    $imageFile = $imageUploadForm->imageFile;
                    $name = time().'_'.$imageFile->name;

                    $imageFile->saveAs('uploads/'.$name);

                    $relAttachment = new HrEmployeeRelAttachment();
                    $relAttachment->setAttributes([
                        'hr_employee_id' => $id,
                        'type' => 2,
                        'name' => $imageFile->name,
                        'size' => $imageFile->size,
                        'path' => 'uploads/'.$name,
                        'extension' => $imageFile->type,
                        'status' => HrEmployeeRelAttachment::STATUS_ACTIVE,
                    ]);

                    if($relAttachment->save()){
                        $saved = true;
                    }
                    else{
                        $saved = false;
                    }
                }

                $work_del = HrEmployeeWorkPlace::getRemoveEmployeeId($id);
                $study_del = HrEmployeeStudy::getRemoveEmployeeId($id);

                // hr_employee_work_place save
                $result_work = $work->getSaves($results['HrEmployeeWorkPlace'], $id);

                // Study ga yozish
                $result_study = $study->getSaves($results['HrEmployeeStudy'], $id);

                /** save employee skills */
                if ($skills) {

                    /** oldinki skill larini o'chiramiz */
                    if ($model->id) {
                        EmployeeRelSkills::deleteAll(['hr_employee_id' => $model->id]);
                    }

                    /** @var EmployeeRelSkills $skill */
                    foreach ($skills as $skill) {
                        $skill->hr_employee_id = $model->id;
                        $skill->status = EmployeeRelSkills::STATUS_ACTIVE;
                        $isSaved = $skill->save();
                        if (!$isSaved) {
                            Yii::error($skill->getErrors(), $skill->formName());
                            break;
                        }
                        else{
                            $saved = true;
                        }
                    }
                }

                // save additional files
                $attachmentAll->file = UploadedFile::getInstances($attachmentAll, 'file');
                if (!empty($attachmentAll->file) && $savedFiles = $attachmentAll->uploadMultiple()) {
                    foreach ($savedFiles as $savedFile) {
                        $attachmentModel2 = new HrEmployeeRelAttachment();
                        $attachmentModel2->setAttributes($savedFile);
                        $attachmentModel2->hr_employee_id = $model->id;
                        $isSaved = /*$isSaved && */$attachmentModel2->save();

                        if (!$isSaved) {
                            Yii::debug($attachmentModel2->getErrors(), 'validations');
                            break;
                        }
                    }
                }

                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                    return $this->redirect(['view', 'id' => $id]);
                }
                else{
                    $transaction->rollBack();
                    Yii::t('app', Yii::t('app', 'Error'));
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
            catch(\Exception $e){
                Yii::info('error Message '.$e->getMessage(), 'save');
                return $this->redirect(Yii::$app->request->referrer);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'attachment' => $attachment,
            'study' => $study,
            'work' => $work,
            'imageUploadForm' => $imageUploadForm,
            'attachmentAll' => $attachmentAll,
            'attachmentAllOldImages' => $attachmentAllOldImages,
            'skills' => $skills,
            'url' => $url,
        ]);
    }

    /**
     * Deletes an existing HrEmployee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model =  $this->findModel($id);
        $model->updateCounters(['status' => 3]);
        return $this->redirect(['index']);
    }

    public function actionExportExcel()
    {
        header('Content-Type: application/vnd.ms-excel');
        $filename = "hr-employee_" . date("d-m-Y-His") . ".xls";
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => HrEmployee::find()->select([
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

    public function actionRemove()
    {
        $isAJax = Yii::$app->request->isAjax;
        if ($isAJax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response['status'] = 1;
            $id = Yii::$app->request->get('hr_employee_id');
            $type = Yii::$app->request->get('type');
            if($type == 1){
                $model = HrEmployeeRelAttachment::deleteAll(
                    ['and',
                        [ 'hr_employee_id'=>$id],
                        ['type' => $type]
                    ]
                );
                if($model){
                    $response['status'] = 1;
                    $response['results'] = 'hey';
                }
                else{
                    $response['status'] = 0;
                    $response['results'] = 'hey';
                }
                return $response;
            }
            else{
                $model = HrEmployeeRelAttachment::findOne(['hr_employee_id' => $id, 'type' => $type]);
                if (!empty($model)) {
                    if ($model->delete()) {
                        $response['result'] = 'Ok';
                        $response['status'] = 1;
                    } else {
                        $response['result'] = 'No Remove Data';
                        $response['status'] = 0;
                    }
                } else {
                    $response['result'] = 'Data Empty';
                    $response['status'] = 0;
                }
                return $response;
            }

        }
    }

    public function actionExcelCreate()
    {
        $model = new HrExportEmployee();

        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            // excel faylni vaqtincha yuklash
            $file = UploadedFile::getInstance($model, 'file');

            // excel faylni o'qib olish
            $PHPReader = \PHPExcel_IOFactory::load($file->tempName);
            $sheetData = $PHPReader->getActiveSheet()->toArray(null, true, true, true);

            if(!empty($sheetData)){
                $employee = new HrEmployee();

                // excelni ozini tekshirish bir xil pasport seriyaga
                $result = $employee->getIsExcel($sheetData);

                if(!$result){
                    Yii::$app->session->setFlash('error', 'Bir xil pasport seriya mavjud!');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                // dbdagi malumotlarni pasport_seriyaga tekshirish
                $res = $employee->getIsDb($sheetData);
                if(!$res){
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Pasport seriya oldinroq kiritilgan!'));
                    return $this->refresh();
                }

                //dbga excel orqali yozish
                $results = $employee->getExcelImport($sheetData);
                if($results)
                    return $this->redirect(['index']);
            }
        }

        return $this->render('excel', [
            'model' => $model
        ]);
    }

    /**
     * Finds the HrEmployee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HrEmployee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HrEmployee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
