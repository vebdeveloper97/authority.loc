<?php
/**
 * Created by PhpStorm.
 * User: Sher
 * Date: 17.02.2019
 * Time: 13:04
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;

class  MyController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->controller->action->id !== 'login'){
            if (Yii::$app->user->isGuest){
             //   return $this->redirect(['/site/login']);
            }
        }

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }
}