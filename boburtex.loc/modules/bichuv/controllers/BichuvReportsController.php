<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 13.07.20 21:38
 */

namespace app\modules\bichuv\controllers;


use app\modules\bichuv\models\BichuvReportSearch;
use Yii;

class BichuvReportsController extends BaseController
{
    public function actionReportAcceptedSlice()
    {
        $searchModel = new BichuvReportSearch();
        $dataProvider = $searchModel->searchAcceptedSlice(Yii::$app->request->queryParams);

        return $this->render('report-accepted-slice', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionReportMato()
    {
        $searchModel = new BichuvReportSearch();
        $dataProvider = $searchModel->searchReportMato(Yii::$app->request->queryParams);

        return $this->render('report-mato', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}