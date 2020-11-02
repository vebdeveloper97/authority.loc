<?php

namespace app\modules\admin\controllers;

class SiteController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
