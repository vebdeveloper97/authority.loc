<?php


namespace app\controllers;
use app\models\ProductDocument;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = ProductDocument::class;
}