<?php

namespace app\modules\tikuv\controllers;

use app\modules\tikuv\models\TikuvDoc;
use app\modules\tikuv\models\TikuvOutcomeProductsPackSearch;
use app\modules\tikuv\models\TikuvPackageItemBalance;
use app\modules\tikuv\models\TikuvTempReportForm;
use Yii;
use yii\web\NotFoundHttpException;
use app\modules\bichuv\models\BichuvItemBalance;
use app\modules\admin\models\ToquvUserDepartment;

class ReportsController extends BaseController
{

    public function actionExportExcel()
    {
        header('Content-Type: application/vnd.ms-excel');
        $filename = "doc_" . date("d-m-Y-His") . ".xls";
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => TikuvDoc::find()->select([
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
     * @return string
     * @throws \yii\db\Exception
     */
   public function actionTikuvDocInform()
   {
       $searchModel = new TikuvTempReportForm();
       $dataProvider = $searchModel->searchDocitems(Yii::$app->request->queryParams);
       return $this->render('report-tikuv-doc-inform', [
           'dataProvider' => $dataProvider,
           'modalForm' => $searchModel
       ]);
   }
    public function actionSliceRemain()
    {
        $searchModel = new TikuvTempReportForm();
        $items = $searchModel->searchSlice(Yii::$app->request->queryParams);

        return $this->render('report-slice-remain', [
            'items' => $items,
            'modalForm' => $searchModel
        ]);
    }

    public function actionBrakRemain()
    {
        $searchModel = new TikuvTempReportForm();
        $items = $searchModel->searchRemainBrak(Yii::$app->request->queryParams);

        return $this->render('report-brak-remain', [
            'items' => $items,
            'modelForm' => $searchModel,
        ]);
    }/**

    /**
     * @return string
     */
    public function actionUslugaRemain()
    {
        $searchModel = new TikuvTempReportForm();
        $items = $searchModel->searchUsluga(Yii::$app->request->queryParams);

        return $this->render('report-usluga-remain', [
            'items' => $items,
            'modalForm' => $searchModel
        ]);
    }

    public function actionAcsRemain()
    {
        $currentUserId = Yii::$app->user->id;
        $sql = "select p.name as model, acs.sku, acs.name, bap.name as property, bib.inventory, td.name as dept from bichuv_item_balance bib
                left join bichuv_doc bd on bib.document_id = bd.id
                left join toquv_departments td on bib.department_id = td.id
                left join bichuv_doc_items bdi on bd.id = bdi.bichuv_doc_id
                left join product p on bdi.model_id = p.id
                left join bichuv_acs acs on bib.entity_id = acs.id
                left join bichuv_acs_property bap on acs.property_id = bap.id
                where bib.id IN (select MAX(bib2.id) from bichuv_item_balance bib2 WHERE bib2.department_id IN (select tud.department_id from toquv_user_department tud where tud.user_id = %d) GROUP BY bib2.entity_id)
                AND bib.inventory > 0
                GROUP BY bib.entity_id ORDER BY acs.sku, acs.name DESC;";
        $sql = sprintf($sql, $currentUserId);
        $items = Yii::$app->db->createCommand($sql)->queryAll();
        return $this->render('report-acs-remain', ['items' => $items]);
    }

    public function actionRemainPackage(){
        $modelForm = new TikuvTempReportForm();
        $results = $modelForm->searchRemainPackage(Yii::$app->request->queryParams);

        return $this->render('report-package-remain', [
            'items' => $results,
            'modelForm' => $modelForm
        ]);
    }

    public function actionAccepted(){
        $sql = "SELECT tgd.quantity, 
                        tgd.model_no,
                        tgd.size     
                FROM tikuv_goods_doc_pack tgdp
                LEFT JOIN tikuv_goods_doc tgd ON tgdp.id = tgd.tgdp_id
                LEFT JOIN models_list ml ON tgdp.model_list_id = ml.id 
                LEFT JOIN models_variations mv ON tgdp.model_var_id = mv.id 
                LEFT JOIN color_pantone cp ON cp.id = mv.color_pantone_id 
                WHERE tgdp.is_incoming = 1";
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        return $this->render('accepted', ['results' => $results]);
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionOutcoming(){

        $searchModel = new TikuvTempReportForm();
        $items = $searchModel->searchOutcoming(Yii::$app->request->queryParams);

        return $this->render('report-outcoming',[
            'items' => $items,
            'modelForm' => $searchModel,
            'params' => null,
            ]);
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionReportIncoming(){

        $searchModel = new TikuvTempReportForm();
        $items = $searchModel->searchIncoming(Yii::$app->request->queryParams);


        return $this->render('report-incoming',[
            'items' => $items,
            'modelForm' => $searchModel,
            'params' => null,
        ]);
    }

    /**
     * Finds the BichuvItemBalance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BichuvItemBalance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BichuvItemBalance::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
