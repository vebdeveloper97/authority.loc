<?php


namespace app\modules\api\controllers;


use app\models\NewsPosts;
use yii\rest\Controller;
use Yii;

class NewsPostController extends Controller
{
    public function actionIndex(){
        $id = Yii::$app->request->get('id', null);
        if($id != null){
            $model = NewsPosts::findOne($id);
        }
        else
            $model = NewsPosts::find()
                ->asArray()
                ->all();
        return $model;
    }

    public function actionCreate(){
        $model = new NewsPosts();

        if($model->load(Yii::$app->request->post(), '') && $model->save()){
            return $model;
        }
        Yii::$app->response->statusCode = 422;
        return [
            'errors' => $model->errors
        ];
    }

    public function actionUpdate(){
        $id = Yii::$app->request->get('id', null);
        if($id != null){
            $model = NewsPosts::findOne($id);
            $data = Yii::$app->request->post();
            $model->setAttributes($data);
            if($model->save()){
                return $model;
            }
            Yii::$app->response->statusCode = 422;
            return [
                'errors' => $model->errors
            ];
        }
        Yii::$app->response->statusCode = 404;
        return [
            'errors' => 'Parametr mavjud emas'
        ];
    }
}