<?php


namespace app\commands;


use app\models\News;
use app\models\User;
use Exception;
use yii\console\Controller;
use Yii;
use yii\helpers\Console;

class AppController extends Controller
{
    /**
     * @var Add User
     * */
    public function actionAddUser()
    {
        $array = [
            [
                'username' => 'Umidjon',
                'password' => '123456',
                'email'    => 'vebdeveloper571632@mail.ru',
                'phone'    => '999956693',
                'status'   => 1,
            ],
            [
                'username' => 'Sanjarbek',
                'password' => '123456',
                'email'    => 'sanjarbek@mail.ru',
                'phone'    => '123456789',
                'status'   => 2,
            ],
        ];
        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        try {
            foreach ($array as $key => $item) {
                $model = new User();
                $model->setAttributes($item);
                $model->access_token = Yii::$app->security->generateRandomString(255);
                $saved = $model->save();
                if ($saved) {
                    unset($model);
                } else {
                    break;
                }
            }

            if ($saved) {
                $transaction->commit();
                Yii::info('Hard kod ishladi );\n User table malumotlar kiritildi!', 'save');
                Console::output('Saved');
            } else {
                $transaction->rollBack();
                $error = Yii::$app->controller->id;
                Yii::info($error, 'save');
                Console::error('Error');
            }
        } catch (Exception $e) {
            Yii::error($e, 'save');
            Console::error('Exception error');
        }
    }

    /**
     * @var Add News
     * */
    public function actionAddNews()
    {
        $array = [
            [
                'title'   => 'Yangiliklar',
                'content' => 'lorem 1',
                'author'  => 'Nasriddinov Umidjon',
                'date'    => date('Y-m-d'),
                'status'  => 1
            ],
            [
                'title'   => 'Futbol yangiliklari',
                'content' => 'lorem 2',
                'author'  => 'Karimov Sherzodbek',
                'date'    => date('Y-m-d'),
                'status'  => 2
            ]
        ];

        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        $controller = Yii::$app->controller->id;
        $action = Yii::$app->controller->action->id;
        try {
            foreach ($array as $item) {
                $model = new News();
                $model->setAttributes($item);
                $saved = $model->save();
                if ($saved) {
                    Yii::info("$model->id - $model->title nomli xabar saqlandi", 'save');
                    unset($model);
                } else {
                    Yii::info($model->errors, 'save');
                    Console::output($model->errors);
                    break;
                }
            }

            if ($saved) {
                $transaction->commit();
                Yii::info('Saqlandi!', 'save');
                Console::output('Saqlandi');
            } else {
                $transaction->rollBack();
                Yii::info($model->errors, 'save');
                Console::output('Saqlanmadi!');
            }
        } catch (Exception $e) {
            Yii::info("($controller) controller - ($action) da Exception : ".$e->getMessage(), 'save');
            Console::output('Exception error');
        }
    }
}