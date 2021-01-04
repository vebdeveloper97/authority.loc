<?php

namespace app\modules\bichuv\controllers;

use app\models\Constants;
use app\modules\bichuv\models\BichuvGivenRollItems;
use app\modules\bichuv\models\BichuvGivenRolls;
use app\modules\bichuv\models\NastelSearch;
use app\modules\hr\models\HrDepartments;
use app\modules\mobile\models\MobileTables;
use app\modules\tikuv\models\TikuvKonveyer;
use Yii;
use app\modules\bichuv\models\TikuvKonveyerBichuvGivenRolls;
use app\modules\bichuv\models\TikuvPlanSearch;
use app\modules\bichuv\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TikuvPlanController implements the CRUD actions for TikuvKonveyerBichuvGivenRolls model.
 */
class TikuvPlanController extends BaseController
{
    /**
     * Lists all TikuvKonveyerBichuvGivenRolls models.
     * @return mixed
     */
    public function actionIndex()
    {
        // mobile_tables dan dep_id tayyorlovga tenglarini olamiz
        $tikuv_konveyer = MobileTables::getTablesByDepartmentTokenAndProcessName(HrDepartments::TOKEN_TIKUV, Constants::TOKEN_TIKUV_KONVEYER);
//        $list = TikuvKonveyer::getSliceList();
        $list = TikuvKonveyerBichuvGivenRolls::getSliceList();

        return $this->render('index', [
            'tikuv_konveyer' => $tikuv_konveyer,
            'list' => $list
        ]);
    }
    public function actionPreview()
    {
        $this->layout = '@app/views/layouts/layout';
        $tikuv_konveyer = TikuvKonveyer::find()->all();
        return $this->render('preview', [
            'tikuv_konveyer' => $tikuv_konveyer,
        ]);
    }
    public function actionSortUpdate()
    {
        if (Yii::$app->request->post() && Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 0;
            $response['message'] = Yii::t('app', 'Hatolik yuz berdi!');
            $response['type'] = 'fail';
            $data = Yii::$app->request->post();
//            $doc = TikuvKonveyer::findOne($data['id']);
            $doc = MobileTables::findOne($data['id']);
            if($doc){
                if (!empty($data['list'])){
                    /*foreach ($data['list'] as $key => $item) {*/
                        $item = $data['list'];
                        $key = $data['indeks'];
                        $td_doc = TikuvKonveyerBichuvGivenRolls::findOne(['bichuv_given_rolls_id' => $item]);
                        if ($td_doc) {
                            $td_doc->setAttributes([
                                'indeks' => $key,
                                'mobile_tables_id' => $data['id']
                            ]);
                        }else {
                            $td_doc = new TikuvKonveyerBichuvGivenRolls();
                            $td_doc->setAttributes([
                                'indeks' => $key,
                                'mobile_tables_id' => $data['id'],
                                'bichuv_given_rolls_id' => $item
                            ]);
                        }
                        if($td_doc['status'] < $td_doc::STATUS_STARTED) {
                            if ($td_doc->save()) {
                                $response['status'] = 1;
                                $response['message'] = Yii::t('app', 'Saved Successfully');
                                $response['type'] = 'success';
                            }
                        }
                        if($td_doc->hasErrors()){
                            $res = [
                                'status' => 'error',
                                'message' => "TikuvKonveyer Nastel saqlanmadi",
                                'content' => $td_doc->getErrors()
                            ];
                            Yii::info($res, 'save');
                        }
                    /*}*/
                }
            }
            return $response;
        }
    }
    public function actionSortDelete()
    {
        if (Yii::$app->request->post() && Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 0;
            $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
            $response['type'] = 'fail';
            $data = Yii::$app->request->post();
            if (!empty($data['list'])){
                /*foreach ($data['list'] as $key => $item) {*/
                    $item = $data['list'];
                    $td_doc = TikuvKonveyerBichuvGivenRolls::findOne(['bichuv_given_rolls_id' => $item]);
                    if($td_doc&&$td_doc['status']==1){
                        if($td_doc->delete()){
                            $response['status'] = 1;
                            $response['message'] = Yii::t('app', 'Saved Successfully');
                            $response['type'] = 'success';
                        }
                        else{
                            $res = [
                                'status' => 'error',
                                'message' => "TikuvKonveyer Nastel o'chirilmadi",
                                'content' => $td_doc->getErrors()
                            ];
                            Yii::info($res, 'save');
                        }
                    }
                /*}*/
            }
            return $response;
        }
    }
    public function actionSortSearch()
    {
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
//            $res['list'] = TikuvKonveyer::getSliceSearch($data['query'],$list);
            $res['list'] = TikuvKonveyerBichuvGivenRolls::getSliceSearch($data['query'],$list);
        }
        return $res;
    }
    public function actionNastelList()
    {
        $searchModel = new NastelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('nastel-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionView($id)
    {
        $bgri = BichuvGivenRollItems::findOne($id);
        $response = [];
        $response['status'] = 0;
        $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
        $response['type'] = 'error';
        if($bgri) {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('view', [
                    'model' => $bgri
                ]);
            } else {
                return $this->render('view', [
                    'model' => $bgri
                ]);
            }
        }else{
            throw new NotFoundHttpException(Yii::t('app', 'Bunday nastel raqamdagi partiya topilmadi'));
        }
    }
    public function actionConfirm($id)
    {
        $bgri = BichuvGivenRollItems::findOne($id);
        $response = [];
        $response['status'] = 0;
        $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
        $response['type'] = 'error';
        if($bgri){
            $bgri->status = BichuvGivenRollItems::STATUS_END;
            if($bgri->save(false)){
                $response['status'] = 1;
                $response['type'] = 'success';
                $response['message'] = Yii::t('app', 'Saved Successfully');
            }
        }else{
            $response['message'] = Yii::t('app', 'Bunday nastel raqamdagi partiya topilmadi');
        }
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }else{
            Yii::$app->session->setFlash($response['type'],$response['message']);
            return $this->redirect('nastel-list');
        }
    }
    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "tikuv-plan_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => TikuvKonveyerBichuvGivenRolls::find()->select([
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
     * Finds the TikuvKonveyerBichuvGivenRolls model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $tikuv_konveyer_id
     * @param integer $bichuv_given_rolls_id
     * @return TikuvKonveyerBichuvGivenRolls the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($tikuv_konveyer_id, $bichuv_given_rolls_id)
    {
        if (($model = TikuvKonveyerBichuvGivenRolls::findOne(['tikuv_konveyer_id' => $tikuv_konveyer_id, 'bichuv_given_rolls_id' => $bichuv_given_rolls_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
