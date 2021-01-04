<?php

namespace app\modules\bichuv\controllers;

use app\models\Constants;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvGivenRollItems;
use app\modules\bichuv\models\BichuvGivenRollItemsAcs;
use app\modules\bichuv\models\BichuvGivenRolls;
use app\modules\bichuv\models\BichuvTableRelWmsDoc;
use app\modules\bichuv\models\BichuvTables;
use app\modules\bichuv\models\NastelSearch;
use app\modules\bichuv\models\RmSearch;
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
 * BichuvPlanController implements the CRUD actions for TikuvKonveyerBichuvGivenRolls model.
 */
class BichuvPlanController extends BaseController
{

    /** Tayyor **/
    public function actionPreview()
    {
        $this->layout = '@app/views/layouts/layout';
        $bichuv_id = HrDepartments::findOne(['token' => Constants::$TOKEN_BICHUV])->id;
        $tables = MobileTables::getMobileTableByDepartment($bichuv_id);

        $listPlanDone = BichuvTableRelWmsDoc::getBichuvPlanListDone(true);

        return $this->render('preview', [
            'tables' => $tables,
            'listPlanDone' => $listPlanDone
        ]);
    }

    public function  actionView($id){

        $model = self::findModel($id);
        $models = $model->wmsDocumentItems;

        return $this->render('view',[
            'model' => $model,
            'models' => $models
        ]);
    }

    public function actionRmList()
    {
        $searchModel = new RmSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('rm_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
    protected function findModel($id)
    {
        $model = WmsDocument::findOne(['id' => $id]);
        if(!empty($model)){
            return  $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
