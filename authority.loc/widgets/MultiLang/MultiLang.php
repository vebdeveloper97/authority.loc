<?php


namespace app\widgets\MultiLang;
use yii\bootstrap\Widget;

class MultiLang extends Widget
{

    public $cssClass;
    public function init()
    {
    }

    public function run()
    {
        return $this->render('view', [
           'cssClass' => $this->cssClass
        ]);
    }

}