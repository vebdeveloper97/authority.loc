<?php


namespace app\components;

use yii\helpers\Html;
use yii\base\Widget;

class MyWidgets extends Widget
{
    public $message;

    public function init()
    {
        parent::init();
        ob_start();
//        if($this->message === null){
//            $this->message = 'Hello My Widgets';
//        }
    }

    public function run()
    {
        $content = ob_get_clean();
        return $content;
        //return Html::encode($this->message);
    }
}