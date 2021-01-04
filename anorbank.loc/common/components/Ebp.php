<?php

namespace common\components;

use Yii;
use Ramsey\Uuid\Uuid;
use tune\ebp\Ebp as Master;
use common\helpers\Logger;
use tune\base\http\events\AfterSendEvent;
use tune\base\http\events\BeforeSendEvent;

/**
 * Class Ebp
 *
 * @package common\components
 */
class Ebp
{
    /**
     * @return Master
     */
    public static function build(): Master
    {
        $ebp = new Master(Yii::$app->params['ebp']['config']);

        $uid = Uuid::uuid4()->toString();
        $file_name = 'ebp.log';

        $ebp->on(Master::EVENT_BEFORE_SEND, function (BeforeSendEvent $event) use ($uid, $file_name) {
            $body = (string)$event->request->getBody();
            /** @noinspection JsonEncodingApiUsageInspection */
            $body .= ' ' . json_encode($event->request->getHeaders());
            Logger::saveToFile($file_name, $uid, 'REQUEST', $body);
        });

        $ebp->on(Master::EVENT_AFTER_SEND, function (AfterSendEvent $event) use ($uid, $file_name) {
            $body = (string)$event->response->getBody();
            Logger::saveToFile($file_name, $uid, 'RESPONSE', $body);
        });

        $ebp->on(Master::EVENT_REQUEST_ERROR, function () use ($uid, $file_name) {
            Logger::saveToFile($file_name, $uid, 'REQ_ERROR', '');
        });

        $ebp->on(Master::EVENT_NETWORK_ERROR, function () use ($uid, $file_name) {
            Logger::saveToFile($file_name, $uid, 'CONN_ERROR', '');
        });

        return $ebp;
    }
}
