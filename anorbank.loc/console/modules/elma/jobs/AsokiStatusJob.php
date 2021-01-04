<?php

namespace console\modules\elma\jobs;

use Yii;
use JsonException;
use yii\base\Exception;
use yii\base\BaseObject;
use yii\queue\RetryableJobInterface;
use console\modules\elma\models\Publisher;
use common\modules\rabbitmq\models\RabbitMQConsume;
use console\modules\elma\exceptions\RetryableException;

/**
 * Class AsokiStatusJob
 *
 * @package app\jobs
 *
 * @property-read int $ttr
 */
class AsokiStatusJob extends BaseObject implements RetryableJobInterface
{
    /**
     *
     * elma request -> asoki response status_id -> {loop 15 sec/ check by status id} -> elma result
     *
     *
     */
    public string $token;
    public string $claim_id;
    public array $elma_request_data = [];

    /**
     * @inheritDoc
     *
     * @param $queue
     *
     * @throws Exception
     * @throws RetryableException
     * @throws JsonException
     */
    public function execute($queue)
    {
        $status = Yii::$app->asoki->status($this->token, $this->claim_id);

        if ($status->result !== '05000') {
            throw new RetryableException('retry');
        }

        $consume = RabbitMQConsume::findOne(['tag' => $this->elma_request_data['_tag']]);

        if ($consume === null) {
            $error_message = 'consume not found ' . $this->elma_request_data['_tag'];
            Yii::error($error_message, __METHOD__);
            throw new Exception($error_message);
        }

        $publisher = new Publisher($consume, $this->elma_request_data);

        $report = json_decode(base64_decode($status->reportBase64), true, 512, JSON_THROW_ON_ERROR);

        if (!isset($report['report'])) {
            $publisher->sendError('Нет report от Asoki');
            exit();
        }

        if (!isset($report['report']['sysinfo'])) {
            $publisher->sendError('Нет sysinfo от Asoki');
            exit();
        }

        if ($publisher->getMethodName() === 'getXML') {
            if (!isset($report['report']['contracts'])) {
                $publisher->sendError('Нет contracts от Asoki');
                exit();
            }

            $data = [
                'report' => $report['report'],
            ];

            if (isset($data['report']['contracts']['contract']) && is_array($data['report']['contracts']['contract'])) {
                foreach ($data['report']['contracts']['contract'] as &$contract) {
                    if (!isset($contract['schedule']) || $contract['schedule'] === '') {
                        continue;
                    }

                    if (!is_array($contract['schedule']) || !isset($contract['schedule']['schedule_info'])) {
                        continue;
                    }

                    if (!is_array($contract['schedule']['schedule_info']) || empty($contract['schedule']['schedule_info'])) {
                        continue;
                    }

                    $keys = array_keys($contract['schedule']['schedule_info']);

                    if ($keys[0] !== 0) {
                        $info = $contract['schedule']['schedule_info'];
                        unset($contract['schedule']['schedule_info']);
                        $contract['schedule']['schedule_info'] = [$info];
                    }
                }

                unset($contract);
            }

            $publisher->sendSuccess($data);
        } else if ($publisher->getMethodName() === 'getKiatsScore') {
            $data = [
                'report' => $report['report'],
            ];
            $publisher->sendSuccess($data);
        } else {
            $publisher->sendError('some new function...');
            exit();
        }
    }

    /**
     * @inheritDoc
     */
    public function getTtr(): int
    {
        return 15;
    }

    /**
     * @inheritDoc
     */
    public function canRetry($attempt, $error): bool
    {
        return $attempt <= 60;
    }
}
