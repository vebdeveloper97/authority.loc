<?php

namespace app\controllers;

use app\models\Reference;
use app\modules\admin\models\EvaluationStatus;
use app\modules\admin\models\EvaluationUz;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public $ip;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->ip = Yii::$app->request->userIP;
        $this->view->params['allReference'] = Reference::find()->count();
        $this->view->params['allComplate'] = Reference::find()->where(['status' => 3])->count();
        $this->view->params['allContinued'] = Reference::find()->where(['status' => 2])->count();
        $this->view->params['isTrue'] = EvaluationStatus::find()->where(['ip_address' => $this->ip])->all()?true:false;
        $this->view->params['spravish'] = EvaluationUz::find()->asArray()->all();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new Reference();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSaveAjax()
    {
        if(Yii::$app->request->isAjax){
            $name = Yii::$app->request->get('name');
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response['status'] = false;
            $model = new EvaluationStatus();
            $model->setAttributes([
                'ip_address' => Yii::$app->request->userIP,
                'evaluation_id' => $name,
                'status' => 1
            ]);
            if($model->save()){
                $response['data'] = $model;
                $response['status'] = true;
            }
            return $response;
        }
        else{
            return Yii::$app->request->referrer;
        }
    }
}
