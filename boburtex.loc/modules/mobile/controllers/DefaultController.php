<?php

namespace app\modules\mobile\controllers;

use app\models\LoginForm;
use app\modules\mobile\components\Menu;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Default controller for the `mobile` module
 */
class DefaultController extends Controller
{
    public $layout = 'main';

    public function behaviors()
    {
        return [
            'verb' => [
                'class' => VerbFilter::class,
                'actions' => [
                  'login' => ['GET', 'POST'],
                  'logout' => ['POST'],
              ],
            ]
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
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $key = Yii::$app->request->get('m');
        Menu::init();

        $menuItems = Menu::getSubMenuByKey($key);

        return $this->render('index', [
            'menuItems' => $menuItems,
        ]);
    }

    public function actionLogin() {
        $this->layout = 'login';

        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['index']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('index');
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['/mobile/default/login']);
    }
}
