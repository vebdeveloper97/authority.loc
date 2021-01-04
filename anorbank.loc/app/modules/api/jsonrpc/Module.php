<?php

/** @noinspection PhpMissingFieldTypeInspection */

namespace app\modules\api\jsonrpc;

use Yii;
use yii\base\Event;
use yii\web\Response;

/**
 * Class Module
 *
 * @package app\modules\api\jsonrpc
 */
class Module extends \app\modules\api\Module
{
    public $controllerNamespace = 'app\modules\api\jsonrpc\controllers';
    public $defaultRoute = 'master';

    public function init(): void
    {
        parent::init();

        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, static function (Event $event) {
            /* @var $response Response */
            $response = $event->sender;
            if ($response->data !== null && Yii::$app->response->format === Response::FORMAT_JSON) {
                $response->data = [
                    'jsonrpc' => '2.0',
                    'id'      => Yii::$app->request->post('id'),
                    'result'  => $response->data,
                ];
            }
        });
    }
}
