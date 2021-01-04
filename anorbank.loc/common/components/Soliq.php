<?php

namespace common\components;

use Exception;
use Ramsey\Uuid\Uuid;
use common\helpers\Logger;
use tune\soliq\events\AfterRequest;
use tune\soliq\events\BeforeRequest;
use Yii;
use yii\base\BaseObject;
use tune\soliq\Soliq as BaseSoliq;

class Soliq extends BaseObject
{
    /**
     * @throws Exception
     * @return BaseSoliq
     */
    public static function build(): BaseSoliq
    {
        $obj = new BaseSoliq([
            'url'      => Yii::$app->params['soliq']['url'],
            'username' => Yii::$app->params['soliq']['username'],
            'password' => Yii::$app->params['soliq']['password'],
        ]);

        $request_id = Uuid::uuid4()->toString();

        $obj->on(BaseSoliq::EVENT_BEFORE_REQUEST, static function (BeforeRequest $event) use ($request_id) {
            $text = date('Y-m-d H:i:s') . ' REQUEST ';
            $text .= $request_id . ' ';

            $text .= json_encode([
                    'url'  => $event->url,
                    'data' => $event->params,
                ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";

            Logger::saveToFile('soliq.log', $request_id, 'REQUEST', $text);
        });

        $obj->on(BaseSoliq::EVENT_AFTER_REQUEST, static function (AfterRequest $event) use ($request_id) {
            $text = date('Y-m-d H:i:s') . ' RESPONSE ';
            $text .= $request_id . ' ';

            $text .= $event->body . "\n";

            Logger::saveToFile('soliq.log', $request_id, 'RESPONSE', $text);
        });

        return $obj;
    }
}
