<?php

namespace common\components;

use Yii;
use DateTime;
use Exception;
use DateTimeZone;
use JsonException;
use Ramsey\Uuid\Uuid;
use Firebase\JWT\JWT;
use tune\base\http\Sender;
use common\helpers\Logger;
use yii\web\BadRequestHttpException;
use tune\billmaster\Billmaster as Master;
use tune\base\http\events\AfterSendEvent;
use tune\base\http\events\BeforeSendEvent;
use tune\base\http\exceptions\RequestException;
use tune\base\http\exceptions\NetworkException;

/**
 * Class Billmaster
 *
 * @package common\components
 */
class Billmaster
{
    /**
     * @param string $env
     *
     * @throws BadRequestHttpException
     * @throws NetworkException
     * @throws RequestException
     * @throws Exception
     * @return Master
     */
    public static function build($env = 'test'): Master
    {
        if ($env !== 'prod') {
            $env = 'test';
        }

        $billmaster = new Master(Yii::$app->params['billmaster']['config'][$env]);
        $uid = Uuid::uuid4()->toString();
        $file_name = "billmaster_{$env}.log";

        $billmaster->on(Sender::EVENT_BEFORE_SEND, function (BeforeSendEvent $event) use ($uid, $file_name) {
            $body = (string)$event->request->getBody();
            /** @noinspection JsonEncodingApiUsageInspection */
            $body .= ' ' . json_encode($event->request->getHeaders());
            Logger::saveToFile($file_name, $uid, 'REQUEST', $body);
        });

        $billmaster->on(Sender::EVENT_AFTER_SEND, function (AfterSendEvent $event) use ($uid, $file_name) {
            $body = (string)$event->response->getBody();
            Logger::saveToFile($file_name, $uid, 'RESPONSE', $body);
        });

        $billmaster->on(Sender::EVENT_REQUEST_ERROR, function () use ($uid, $file_name) {
            Logger::saveToFile($file_name, $uid, 'REQ_ERROR', '');
        });

        $billmaster->on(Sender::EVENT_NETWORK_ERROR, function () use ($uid, $file_name) {
            Logger::saveToFile($file_name, $uid, 'CONN_ERROR', '');
        });

        $token = Yii::$app->cache->get("bm_{$env}_token");

        if (!empty($token)) {
            $payload_start_point = strpos($token, '.') + 1;
            $payload_string = substr($token, $payload_start_point, strrpos($token, '.') - $payload_start_point);

            try {
                $payload = json_decode(JWT::urlsafeB64Decode($payload_string), true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                throw new BadRequestHttpException('billmaster json syntax error');
            }

            if (!isset($payload['exp'])) {
                throw new BadRequestHttpException('billmaster exp not found');
            }

            $cache_expire_date = $payload['exp'] ?? time();
            $cache_expire_date += 14400;

            $timezone = new DateTimeZone('Asia/Tashkent');
            $exp_date = new DateTime();
            $exp_date->setTimestamp($cache_expire_date);
            $exp_date->setTimezone($timezone);

            if ($exp_date <= new DateTime('now', $timezone)) {
                $token = '';
            }
        }

        if (empty($token)) {
            $login = $billmaster->auth()->login();

            if ($login->isSuccess()) {
                $token = $login->access_token;
                Yii::$app->cache->set("bm_{$env}_token", $token);
            } else {
                throw new BadRequestHttpException($login->errorMessage());
            }
        }

        $billmaster->token = $token;

        return $billmaster;
    }
}
