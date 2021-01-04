<?php


namespace app\components\Events;
use phpDocumentor\Reflection\Types\This;
use yii\base\Component;
use Yii;

class Foo extends Component
{
    const EVENT_HELLO = 'HELLO EVENTS';
    public $isTrue = false;

    public function init()
    {
        if($this->isTrue)
            self::on(self::EVENT_HELLO, [$this, self::getMessages()]);
        else
            self::on(self::EVENT_HELLO, [$this, self::getMessagesWarning()]);

    }

    public static function getMessages()
    {
        return Yii::$app->session->setFlash("success", 'Success');
    }

    public function getMessagesWarning()
    {
        return Yii::$app->session->setFlash("warning", 'Success');
    }

    public function bar()
    {
        $this->trigger(self::EVENT_HELLO);
    }
}