<?php


namespace app\models;
use yii\base\Model;
use yii\helpers\VarDumper;

class Main extends Model
{
    const USER_REGISTERED = 'admin';

    public function titleShow()
    {
        echo 'Hello Main Title';
    }

    public function init()
    {
        // Method1
        $this->on(Main::USER_REGISTERED, function($event){
            VarDumper::dump($event->sender,10,true);
        });

        // Method2
        $this->on(Main::USER_REGISTERED, [$this, 'titleShow']);

        // Method3
        $this->on(Main::USER_REGISTERED, [Mail::class, 'staticMethodObj']);

    }

    public function register()
    {
        $this->trigger(Main::USER_REGISTERED);
    }

    public function signup()
    {
        return true;
    }
}