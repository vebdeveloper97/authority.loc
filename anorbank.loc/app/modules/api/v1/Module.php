<?php

namespace app\modules\api\v1;

use Yii;
use stdClass;
use yii\base\Event;
use yii\web\Response;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\api\v1\controllers';

    public function init()
    {
        parent::init();

        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, static function (Event $event) {
            /* @var $response Response */
            $response = $event->sender;
            if ($response->data !== null && Yii::$app->response->format === Response::FORMAT_JSON) {

                $result['success'] = $response->isSuccessful;

                if ($response->isSuccessful) {
                    $result['data'] = empty($response->data) ? new stdClass() : $response->data;
                    $result['error'] = new stdClass();
                } else {
                    $result['data'] = new stdClass();
                    $result['error'] = [
                        'code'    => $response->data['code'] ?? 0,
                        'message' => $response->data['message'] ?? 'server unknown error',
                    ];

                    if (YII_ENV_DEV) {
                        $result['dev'] = $response->data;
                    }
                }

                $response->data = $result;
                $response->statusCode = 200;
            }
        });
    }
}
