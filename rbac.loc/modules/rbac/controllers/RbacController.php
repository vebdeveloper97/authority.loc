<?php


namespace app\modules\rbac\controllers;


use app\modules\rbac\models\RbacMenu;
use yii\web\Controller;
use Yii;

class RbacController extends Controller
{
    public $menus = [];

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        // menu olish
        $this->menus = RbacMenu::find()
            ->where(['status' => 1])
            ->asArray()
            ->all();

        $this->view->params['menus'] = $this->menus;
    }

}