<?php

namespace app\controllers;

use app\models\Color;
use app\models\ColorPantone;
use app\models\Users;
use app\modules\admin\models\AuthItem;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvServiceItemBalance;
use app\modules\hr\models\HrDepartmentResponsiblePerson;
use app\modules\usluga\models\UslugaDoc;
use app\modules\usluga\models\UslugaDocItems;
use Yii;
use yii\base\DynamicModel;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends MyController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
//                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post','get'],
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
    public function actionSavecolor($id = 1)
    {
    	/**
			color_panton_type_id
			1 => TSX
			2 => TPG
			3 => TCX
    	*/
		switch ($id) {
			case 1:
				$string = file_get_contents("TSX.json");
				break;
			
			case 2:
				$string = file_get_contents("tpg.json");
				break;
			case 3:
				$string = file_get_contents("tcx.json");
				break;
		}
    	$json = json_decode($string, true);
		foreach ($json['colors'] as $key) {
            $color = \app\models\ColorPantone::findOne([
                'name' => $key['name'],
				'code' => $key['code'],
				'r' => $key['rgb']['r'],
				'g' => $key['rgb']['g'],
				'b' => $key['rgb']['b'],
				'color_panton_type_id' => $id,
            ]);
            if(!$color){
                $model = new \app\models\ColorPantone;
                $model->setAttributes([
                    'name' => $key['name'],
                    'code' => $key['code'],
                    'r' => $key['rgb']['r'],
                    'g' => $key['rgb']['g'],
                    'b' => $key['rgb']['b'],
                    'color_panton_type_id' => $id,
                ]);
                if(count($model->getErrors())==0){
                    $model->save();
                }else{
                    var_dump($model->getErrors());
                }
            }else{
                echo "<b>{$key['name']} <span style='color: rgb({$key['rgb']['r']},{$key['rgb']['g']},{$key['rgb']['b']}'>{$key['code']}</span></b> oldin saqlangan <span style='width:100px;height:20px;background-color: rgb({$key['rgb']['r']},{$key['rgb']['g']},{$key['rgb']['b']}'>{$key['rgb']['r']},{$key['rgb']['g']},{$key['rgb']['b']}</span><br>";
            }
		}
    }
    public function actionColor($from=1,$to=5000){
        $model = ColorPantone::find()->where(['>','id',$from])->andWhere(['<','id',$to])->all();
        foreach ($model as $item){
            $code = substr($item['code'], 0, -4);
            $color = Color::findOne(['pantone'=>$code]);
            if ($color){
                $item->color_id = $color->id;
                $item->save();
            }
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
        $this->redirect('index');
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'login';
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


    public function actionAccessDenied()
    {
        throw new ForbiddenHttpException("Вам не разрешено!");
    }

    public function actionTest()
    {
        $auth = Yii::$app->authManager;

        $role = Yii::$app->authManager->getRole('super_admin');

        $auth->assign($role,1);
    }

    /**
     * Departament bo'yicha ma'sul shaxs id va fish larini qaytaradi
     *
     * @param $department_id
     * @return array|null
     * @throws NotFoundHttpException
     */
    public function actionGetResponsibleEmployeeByDepartment($department_id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $dynamicModel = new DynamicModel(['department_id']);
            $dynamicModel->addRule(['department_id'], 'integer');
            $dynamicModel->setAttributes(['department_id' => $department_id]);

            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($dynamicModel->validate()) {
                $result = HrDepartmentResponsiblePerson::getResponsiblePersonByDepartmentId($dynamicModel['department_id']);

                return $result;
            } else {
                return [
                    'error' => true,
                ];
            }
        }

        throw new NotFoundHttpException();
    }
}
