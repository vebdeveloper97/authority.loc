<?php

namespace app\modules\toquv\controllers;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;
use yii2mod\editable\EditableAction;
use app\modules\toquv\models\ToquvKalite;
use app\modules\toquv\models\ToquvKaliteDefects;
use app\modules\toquv\models\ToquvStatistics;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * ToquvKaliteStatController implements the CRUD actions for ToquvKalite model.
 * Bahriddin Mo'minov
 */
class ToquvStatisticsController extends BaseController
{

    public function actions()
    {
        return [
            'change-defects' => [
                'class' => EditableAction::class,
                'modelClass' => ToquvKaliteDefects::class,
            ],
        ];
    }

    public function actionIndex()
    {
        switch ($_GET['action']) {
            case "getSmenaData":
                echo json_encode( ToquvStatistics::getMakineStatistics() );
                die;
                break;
            case "getSmenaDateRange":
                echo json_encode( ToquvStatistics::getMakineStatistics($_POST['start'], $_POST['end']) );
                die;
                break;
        }
        return $this->render('index');
    }


    public function actionKaliteUserDefects()
    {
        $kaliteDefects = ToquvStatistics::getKaliteUserDefects($_GET['start'], $_GET['end']);
        
        $provider = new ArrayDataProvider([
            'allModels' => $kaliteDefects,
            'sort' => [
                'attributes' => ['user_fio','quantity','razryad','1','2','3','4','5','6','7','8','9','10'],
            ],
            'pagination' => [
                'pageSize' => count($kaliteDefects),
            ],
        ]);
        return $this->render('kalite-user-defects', [
            'provider' => $provider,
            'kaliteDefects' => json_encode(array_slice($kaliteDefects, 0, count($kaliteDefects) )),
            'defects' => ToquvStatistics::getDefects(),
        ]);
    }


    public function actionToquvMakine()
    {
        $kaliteMakine = ToquvStatistics::getMakineStatistics($_GET['start'], $_GET['end']);

        $provider = new ArrayDataProvider([
            'allModels' => $kaliteMakine,
            'sort' => [
                'attributes' => ['makine_name','quantity'],
            ],
            'pagination' => [
                'pageSize' => count($kaliteMakine),
            ],
        ]);
        return $this->render('toquv-makine', [
            'provider' => $provider,
            'makineStatuses' => ToquvStatistics::getMakineStatuses(),
            'result_smena' => json_encode($kaliteMakine),
        ]);
    }


    public function actionKaliteDefect()
    {
        $provider = new ArrayDataProvider([
            'allModels' => ToquvStatistics::getKaliteUserDefects($_GET['start'], $_GET['end']),
            'sort' => [
                'attributes' => ['user_fio', 'quantity', 'razryad', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
            ],
            'pagination' => [
                'pageSize' => 40,
            ],
        ]);
        switch ($_GET['action']) {
            case "getKaliteTable":
                return $this->render('kalite-defect', [
                    'dataTable' => ToquvStatistics::getKaliteUserDefects($_GET['start'], $_GET['end']),
                    'defects' => ToquvStatistics::getDefects(),
                ]);
                die;
                break;
        }
        return $this->render('kalite-defect', [
            'provider' => $provider,
            'defects' => ToquvStatistics::getDefects(),
        ]);

    }




}
