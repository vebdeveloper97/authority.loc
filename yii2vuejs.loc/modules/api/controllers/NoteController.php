<?php


namespace app\modules\api\controllers;


use app\modules\api\resources\NoteResources;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\rest\ActiveController;
use Yii;

class NoteController extends ActiveController
{
    public $modelClass = NoteResources::class;

    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
            'cors' => Cors::class
        ]);
    }
}