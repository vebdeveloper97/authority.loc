<?php

namespace app\api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\filters\VerbFilter;

/**
 * Country Controller API
 *
 * @author Omadbek Onorov <omadbek.onorov@gmail.com>
 */
class CountryController extends ActiveController
{
    public $modelClass = 'app\api\modules\v1\models\Country';


//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'index' => ['get'],
//                    'view' => ['get'],
//                    'create' => ['post'],
//                    'update' => ['post'],
//                    'delete' => ['post'],
//                ],
//
//            ]
//        ];
//    }

    public function actionIndex(){
        return ['hello' => 'World'];
    }
}


