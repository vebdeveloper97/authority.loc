<?php

namespace app\modules\base\controllers;

use app\models\Constants;
use app\modules\bichuv\models\BichuvGivenRollItems;
use app\modules\bichuv\models\BichuvGivenRolls;
use app\modules\bichuv\models\BichuvTableRelWmsDoc;
use app\modules\bichuv\models\BichuvTables;
use app\modules\bichuv\models\NastelSearch;
use app\modules\hr\models\HrDepartments;
use app\modules\mobile\models\MobileTables;
use app\modules\tikuv\models\TikuvKonveyer;
use app\modules\wms\models\WmsDocument;
use Yii;
use app\modules\bichuv\models\TikuvKonveyerBichuvGivenRolls;
use app\modules\bichuv\models\TikuvPlanSearch;
use app\modules\bichuv\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 *
 */
class PlanController extends BaseController
{
    /**
     * Lists all Wms Documents models DOC TYPE = 20.
     * @return mixed
     */
    /** Tayyor **/
    public function actionIndex()
    {
        $lists = BichuvTableRelWmsDoc::getBichuvPlanList();
        $listPlanDone = BichuvTableRelWmsDoc::getBichuvPlanListDone();

        return $this->render('index', [
            'lists' => $lists,
            'listPlanDone' => $listPlanDone,
        ]);
    }

    /** Tayyor **/
    public function actionSortUpdate()
    {
        if (Yii::$app->request->post() && Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 0;
            $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
            $response['type'] = 'fail';
            $data = Yii::$app->request->post();
                if (!empty($data['list'])){
                    $item = $data['list'];
                    $new_wms_doc = BichuvTableRelWmsDoc::findOne(['wms_doc_id' => $item]);
                    if ($new_wms_doc) {
                        $new_wms_doc->setAttributes([
                            'indeks' => $data['indeks']
                        ]);
                    }else {
                        $new_wms_doc = new BichuvTableRelWmsDoc();
                        $new_wms_doc->setAttributes([
                            'wms_doc_id' => $item,
                            'indeks' => $data['indeks']
                        ]);
                    }
                    if($new_wms_doc['status'] < $new_wms_doc::STATUS_STARTED) {
                        if ($new_wms_doc->save()) {
                            $response['status'] = 1;
                            $response['message'] = Yii::t('app', 'Saved Successfully');
                            $response['type'] = 'success';
                        }
                    }
                    if($new_wms_doc->hasErrors()){
                        $res = [
                            'status' => 'error',
                            'message' => "Saqlanmadi",
                            'content' => $new_wms_doc->getErrors()
                        ];
                        Yii::info($res, 'save');
                    }
                }
            return $response;
        }
    }
    /** Tayyor **/
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
                $item = $data['list'];
                $bt_rel_wd = BichuvTableRelWmsDoc::findOne(['wms_doc_id' => $item]);

                if($bt_rel_wd && $bt_rel_wd['status'] ==  1){
                    if($bt_rel_wd->delete()){
                        $response['status'] = 1;
                        $response['message'] = Yii::t('app', 'Saved Successfully');
                        $response['type'] = 'success';
                    }
                    else{
                        $res = [
                            'status' => 'error',
                            'message' => "TikuvKonveyer Nastel o'chirilmadi",
                            'content' => $bt_rel_wd->getErrors()
                        ];
                        Yii::info($res, 'save');
                    }
                }
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
            $res['status'] = 1;
            $res['list'] = BichuvTableRelWmsDoc::getRmSearch($data['query'],$data['list']);
        }
        return $res;
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

    /**
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
