<?php


namespace app\modules\toquv\controllers;


use app\modules\toquv\models\ToquvNeSearch;
use app\modules\toquv\models\ToquvPusFineSearch;
use app\modules\toquv\models\ToquvThreadSearch;
use Yii;
use yii\filters\VerbFilter;
use app\modules\toquv\models\ToquvNe;
use app\modules\toquv\models\ToquvPusFine;
use app\modules\toquv\models\ToquvThread;
use yii\web\Response;

class ToquvDirectoryController extends BaseController
{

    public static $active;

    /**
     * @return string
     */
    public function actionIndex()
    {
        $this::$active = 'index';
        $searchModel = new ToquvNeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        $searchPusFine = new ToquvPusFineSearch();
        $dataPusFine = $searchPusFine->search(Yii::$app->request->queryParams);


        $searchThread = new ToquvThreadSearch();
        $dataThread = $searchThread->search(Yii::$app->request->queryParams);

        $dataProvider->pagination->pageParam = 'ne-page';
        $dataProvider->sort->sortParam = 'ne-sort';

        $dataPusFine->pagination->pageParam = 'pusfine-page';
        $dataPusFine->sort->sortParam = 'pusfine-sort';

        $dataThread->pagination->pageParam = 'thread-page';
        $dataThread->sort->sortParam = 'thread-sort';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchPusFine' => $searchPusFine,
            'dataPusFine' => $dataPusFine,
            'searchThread' => $searchThread,
            'dataThread' => $dataThread,
            'index' =>'index'
        ]);
    }

    public function actionSaveEntity($type = 'neModelType'){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $response = [];
        $response['status'] = 0;
        $response['message'] = 'Not saved';
        $response['error'] = 'Unknown Error';
        switch ($type){
            case 'neModelType':
                    $model = new ToquvNe();
                    if(isset($data['ToquvNe']['id'])){
                        $model = ToquvNe::findOne($data['ToquvNe']['id']);
                    }
                    break;
            case 'pusfineModelType':
                $model = new ToquvPusFine();
                if(isset($data['ToquvPusFine']['id'])){
                    $model = ToquvPusFine::findOne($data['ToquvPusFine']['id']);
                }
                break;
            case 'threadModelType':
                $model = new ToquvThread();
                if(isset($data['ToquvThread']['id'])){
                    $model = ToquvThread::findOne($data['ToquvThread']['id']);
                }
                break;
        }
        if ($model->load($data) && $model->save()) {
            $response['status'] = 1;
            $response['message'] = 'success';
            $response['error'] = 'No';
        }else{
            if($model->hasErrors('name')){
                $response['error'] = $model->getErrors('name');
            }
        }
        return $response;
    }
}