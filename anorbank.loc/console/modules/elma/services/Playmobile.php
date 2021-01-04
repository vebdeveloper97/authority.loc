<?php

namespace console\modules\elma\services;

use Yii;
use yii\base\DynamicModel;
use yii\base\InvalidConfigException;
use tune\base\http\exceptions\NetworkException;
use tune\base\http\exceptions\RequestException;

class Playmobile extends BaseService
{
    /**
     * @param array $params
     *
     * @throws InvalidConfigException
     */
    public function send(array $params): void
    {
        $model = DynamicModel::validateData($params, [
            [['phone', 'text'], 'required'],
            ['text', 'string', 'min' => 1, 'max' => 120],
            ['phone', 'string', 'length' => 12],
        ]);

        if ($model->hasErrors()) {
            $this->publisher->sendError(array_values($model->firstErrors)[0]);
            return;
        }

        try {
            $send_sms = Yii::$app->playmobile->sendSms($params['phone'], $params['text']);
        } catch (NetworkException $e) {
            $this->publisher->sendError('network error');
            return;
        } catch (RequestException $e) {
            $this->publisher->sendError('request error, code:' . $e->getCode());
            return;
        }

        if ($send_sms->isSuccess()) {
            if ($send_sms->isOk()) {
                $this->publisher->sendSuccess(['message_id' => Yii::$app->playmobile->lastMessageId]);
            } else {
                $this->publisher->sendError('not received');
            }
        } else {
            $this->publisher->sendError($send_sms->errorMessage());
        }
    }
}
