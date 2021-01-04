<?php

namespace app\bootstrap;

use Yii;
use yii\base\Event;
use Ramsey\Uuid\Uuid;
use yii\web\Response;
use yii\base\Application;
use yii\base\BootstrapInterface;
use common\modules\request_log\models\RequestLog;

class Logger implements BootstrapInterface
{
    /**
     * @param Application $app
     *
     * @noinspection PhpMissingParamTypeInspection
     */
    public function bootstrap($app): void
    {
        $pair_id = str_replace('-', '', Uuid::uuid4()->toString());

        $request_log = new RequestLog();
        $request_log->pair_id = $pair_id;
        $request_log->service = 'api';
        $request_log->type = RequestLog::TYPE_REQUEST;
        $request_log->body = Yii::$app->request->getRawBody();
        $request_log->save(false);

        Yii::$app->response->on(Response::EVENT_AFTER_SEND, function (Event $event) use ($pair_id) {
            /* @var $response Response */
            $response = $event->sender;

            $request_log = new RequestLog();
            $request_log->pair_id = $pair_id;
            $request_log->service = 'api';
            $request_log->type = RequestLog::TYPE_RESPONSE;
            $request_log->body = $response->content;

            $request_log->save(false);
        });
    }
}
