<?php


namespace app\controllers;
use app\models\News;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

class NewsController extends BaseApiController
{
    public $modelClass = News::class;

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this,'getData'];
        return $actions;
    }

    public function getData()
    {
        return \Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => News::find()->where(['id' => [1,2,3]]),
        ]);
    }

    public function actionTitles(){
        return News::find()
            ->select(['title', 'author'])
            ->groupBy('status')
            ->all();
    }
}