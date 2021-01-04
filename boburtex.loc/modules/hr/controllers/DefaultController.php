<?php

namespace app\modules\hr\controllers;

use app\modules\hr\models\Districts;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use Yii;
use yii\web\Response;

/**
 * Default controller for the `hr` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDistrictByRegion()
    {
        $response = [];
        $response['status'] = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $districtByRegion = Districts::find()->where(['region_id' => $id])->all();
        $districtByRegion = ArrayHelper::map($districtByRegion, 'id', 'name');

        if(!empty($districtByRegion)){
            $response['items'] = $districtByRegion;
            $response['status'] = true;
        }

        return $response;
    }




}
