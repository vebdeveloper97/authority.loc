<?php


namespace app\commands;


use app\models\User;
use yii\console\Controller;
use Yii;

class AppController extends Controller
{
    public function actionAddUser($username,$password,$email,$phone)
    {
        $model = new User();
        $model->username = $username;
        $model->password = Yii::$app->security->generatePasswordHash($password);
        $model->email = $email;
        $model->phone = $phone;
        $model->status = 1;
        $model->access_token = Yii::$app->security->generateRandomString(255);
        $model->save();
    }
}