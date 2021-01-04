<?php

namespace backend\modules\logger\controllers;

use Yii;
use common\modules\request_log\models\RequestLog;
use backend\modules\logger\models\RequestLogSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * LoggerController implements the CRUD actions for RequestLog model.
 */
class DefaultController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all RequestLog models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequestLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RequestLog model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView(int $id)
    {
        return $this->render('view', [
            'requests' => RequestLog::getAllPairRequests($id),
        ]);
    }
}
