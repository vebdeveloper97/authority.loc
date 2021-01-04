<?php


namespace app\controllers;


use app\models\Post;
use yii\web\Controller;

class PostController extends Controller
{
    public function actionIndex()
    {
        $post = Post::find()
            ->asArray()
            ->all();

        return $this->render('index', [
            'post' => $post
        ]);
    }
}