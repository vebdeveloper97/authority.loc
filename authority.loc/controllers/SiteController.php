<?php

namespace app\controllers;

use app\components\Events\Foo;
use app\models\Reference;
use app\modules\admin\models\AboutUz;
use app\modules\admin\models\CategoriesUz;
use app\modules\admin\models\EvaluationStatus;
use app\modules\admin\models\EvaluationUz;
use app\modules\admin\models\MessageUz;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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

        $lang = Yii::$app->language;
        $modelUz = MessageUz::find()
            ->alias('m')
            ->select(['m.id', 'm.title', 'm.author', 'm.content', 'm.date', 'a.path'])
            ->leftjoin("message_attachments_{$lang} as client", 'm.id = client.message_id')
            ->leftJoin("attachments as a", 'a.id = client.attachments_id')
            ->where(['m.status' => 1, 'm.top' => false])
            ->asArray()
            ->groupBy('client.id');
        $countQuery = clone $modelUz;
        $pages = new Pagination([
            'defaultPageSize' => 9,
            'totalCount' => $countQuery->count()
        ]);
        $models = $modelUz
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $this->view->params['messages'] = $models;
        $this->view->params['pages'] = $pages;

        $this->view->params['top'] = MessageUz::find()
            ->alias('m')
            ->select(['m.id', 'm.title', 'm.author', 'm.content', 'm.date', 'a.path'])
            ->leftjoin("message_attachments_{$lang} as client", 'm.id = client.message_id')
            ->leftJoin("attachments as a", 'a.id = client.attachments_id')
            ->where(['m.status' => 1, 'm.top' => true])
            ->limit(6)
            ->asArray()
            ->all();

        $this->view->params['alo'] = EvaluationStatus::find()
            ->where(['evaluation_id' => 1])
            ->count();
        $this->view->params['yaxshi'] = EvaluationStatus::find()
            ->where(['evaluation_id' => 2])
            ->count();
        $this->view->params['qoniqarli'] = EvaluationStatus::find()
            ->where(['evaluation_id' => 3])
            ->count();
        $this->view->params['qoniqarsiz'] = EvaluationStatus::find()
            ->where(['evaluation_id' => 4])
            ->count();
        $this->view->params['yomon'] = EvaluationStatus::find()
            ->where(['evaluation_id' => 5])
            ->count();

        $this->view->params['about'] = AboutUz::find()->all();

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
//        \Yii::$app->response->redirect('http://authority.loc/uz/site/index', 200)->send();

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

    public function actionNews(){
        $id = Yii::$app->request->get('id');
        $message = MessageUz::findOne($id);
        if($message){
            return $this->render('news', [
                'message' => $message
            ]);
        }
        else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Not Found'));
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionSearch(){
        $name = Yii::$app->request->get('search');
        if(isset($name) && !empty($name)){
            $lang = Yii::$app->language;
            $model = MessageUz::find()
                ->alias('m')
                ->select(['m.id', 'm.title', 'm.author', 'm.content', 'm.date', 'a.path'])
                ->leftjoin("message_attachments_{$lang} as client", 'm.id = client.message_id')
                ->leftJoin("attachments as a", 'a.id = client.attachments_id')
                ->where(['m.status' => 1])
                ->andWhere(['like', 'title', $name])
                ->limit(12)
                ->asArray()
                ->all();
            if(!empty($model)){
                return $this->render('search', [
                    'model' => $model
                ]);
            }
            else{
                return $this->render('error1', [
                    'model' => $name
                ]);
            }
        }
        else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Variable not found'));
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionNewsAll()
    {
        $action = Yii::$app->controller->action->id;
        $action = ($action == 'news-all') ? 'Yangiliklar' : $action;
        $category = CategoriesUz::find()
            ->where(['name' => $action])
            ->one();

        $lang = Yii::$app->language;
        $modelUz = MessageUz::find()
            ->alias('m')
            ->select(['m.id', 'm.title', 'm.author', 'm.content', 'm.date', 'a.path'])
            ->leftjoin("message_attachments_{$lang} as client", 'm.id = client.message_id')
            ->leftJoin("attachments as a", 'a.id = client.attachments_id')
            ->where(['m.type' => $category['id']])
            ->asArray()
            ->groupBy('client.id');
        $countQuery = clone $modelUz;
        $pages = new Pagination([
            'defaultPageSize' => 9,
            'totalCount' => $countQuery->count()
        ]);
        $model = $modelUz
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('news-all',[
            'model' => $model
        ]);
    }

    public function actionDistricts()
    {
        $action = Yii::$app->controller->action->id;
        $action = ($action == 'districts') ? 'Tumanlar' : $action;
        $category = CategoriesUz::find()
            ->where(['name' => $action])
            ->one();

        $lang = Yii::$app->language;
        $modelUz = MessageUz::find()
            ->alias('m')
            ->select(['m.id', 'm.title', 'm.author', 'm.content', 'm.date', 'a.path'])
            ->leftjoin("message_attachments_{$lang} as client", 'm.id = client.message_id')
            ->leftJoin("attachments as a", 'a.id = client.attachments_id')
            ->where(['m.type' => $category['id']])
            ->asArray()
            ->groupBy('client.id');
        $countQuery = clone $modelUz;
        $pages = new Pagination([
            'defaultPageSize' => 9,
            'totalCount' => $countQuery->count()
        ]);
        $model = $modelUz
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('districts',[
            'model' => $model
        ]);
    }
}
