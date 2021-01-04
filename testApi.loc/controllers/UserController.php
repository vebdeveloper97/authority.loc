<?php


namespace app\controllers;


use app\models\rest\ProductDocument;

class UserController extends BaseApiController
{
    public $modelClass = ProductDocument::class;
}