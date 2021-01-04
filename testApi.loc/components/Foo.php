<?php
    namespace app\components;

    use yii\base\Component;
    use yii\base\Event;

    class Foo extends Component{
        const EVENT_HELLO = 'Hello';

        public function bar()
        {
            $this->trigger(self::EVENT_HELLO);
        }
    }