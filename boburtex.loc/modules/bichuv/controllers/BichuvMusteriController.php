<?php

namespace app\modules\bichuv\controllers;

use app\modules\bichuv\models\BichuvMusteriType;
use Yii;
use app\modules\bichuv\models\BichuvMusteri;
use app\modules\bichuv\models\BichuvMusteriSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ToquvMusteriController implements the CRUD actions for ToquvMusteri model.
 */
class BichuvMusteriController extends BaseController
{
    /**
     * Lists all ToquvMusteri models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BichuvMusteriSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGenerateMusteriToken()
    {
        $musteri = \app\modules\base\models\Musteri::find()->where(['token' => NULL])->all();
        foreach ($musteri as $i=>$value) {
            $value->token = self::generateToken($value->name);
            $value->save('false');
        }
        die();
        return ;
    }

    static function generateToken($name)
    {
        $chars = ["Q","W","E","R","T","Y","U","I","O","P",
                   "A","S","D","F","G","H","J","K","L",
                    "Z","X","C","V","B","N","M"];

        $name = mb_strtoupper($name);
        $name = str_replace([' ', '"', '-', "'", "_", "OOO", "MCHJ"], "", $name);
        if (strlen($name) != 5) {
            $name.= $chars[array_rand($chars)]
                 .  $chars[array_rand($chars)]
                 .  $chars[array_rand($chars)]
                 .  $chars[array_rand($chars)];
        }
        $name = mb_substr($name, 0, 5);
        $name.= rand(1000, 9999);
        $count = \app\modules\base\models\Musteri::find()
            ->where(['token' => $name])
            ->count();
        if ( $count != 0 ) {
            self::generateToken($name);
        }
        return $name;
    }

    /**
     * Displays a single ToquvMusteri model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ToquvMusteri model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');
        $model = new BichuvMusteri();

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            if($model->save()){
                $response['status'] = 0;
            }else{
                $response['status'] = 1;
                $response['errors'] = $model->getErrors();
            }

            return $response;

        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            if($model->save()){
                $response['status'] = 0;
            }else{
                $response['status'] = 1;
                $response['errors'] = $model->getErrors();
            }
            return $response;
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if($this->findModel($id)->delete()){
            echo "success";
        }else{
            echo "fail";
        }

        exit();
    }

    /**
     * @param $id
     * @return BichuvMusteri|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = BichuvMusteri::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    public function actionCreateNewItem()
    {
        $this->enableCsrfValidation = false;
        $name = trim(Yii::$app->request->post('name'));
        $getModel = trim(Yii::$app->request->post('model'));
        $model = null;

        if($getModel == 'toquv-ne') {
            $model = new BichuvMusteriType();
            $model->name = $name;
        }


        if($model->save()){
            return $model->id;
        }else{
            return "fail";
        }
    }
}
