<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 05.03.20 10:16
 */

namespace app\modules\tikuv\controllers;


use app\modules\tikuv\models\TikuvKonveyer;
use app\modules\tikuv\models\TikuvKonveyerSearch;
use Yii;

class MasterController extends BaseController
{
    public function actionIndex($id=null)
    {
        $konveyer = TikuvKonveyer::find()->where(['users_id' => Yii::$app->user->id])->asArray()->all();
        $list = ($id)?TikuvKonveyer::getList($id):"";
        return $this->render('index', [
            'konveyer' => $konveyer,
        ]);
    }
}