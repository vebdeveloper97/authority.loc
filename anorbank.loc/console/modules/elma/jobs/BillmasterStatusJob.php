<?php

namespace console\modules\elma\jobs;

use Yii;
use Throwable;
use yii\queue\Queue;
use yii\base\Exception;
use yii\base\BaseObject;
use common\components\Billmaster;
use yii\queue\RetryableJobInterface;
use yii\web\BadRequestHttpException;
use console\modules\elma\models\Publisher;
use tune\base\http\exceptions\RequestException;
use tune\base\http\exceptions\NetworkException;
use common\modules\rabbitmq\models\RabbitMQConsume;
use console\modules\elma\exceptions\RetryableException;

/**
 * Class BillmasterStatusJob
 *
 * @package console\modules\elma\jobs
 *
 * @property-read int $ttr
 */
class BillmasterStatusJob extends BaseObject implements RetryableJobInterface
{
    public string $agentRequestId;
    public array $data = [];
    public array $elma_request_data = [];

    /**
     * @param Queue $queue
     *
     * @throws Exception
     * @throws RetryableException
     * @throws NetworkException
     * @throws RequestException
     * @throws BadRequestHttpException
     * @noinspection PhpMissingParamTypeInspection
     * @return mixed|void
     */
    public function execute($queue)
    {
        $consume = RabbitMQConsume::findOne(['tag' => $this->elma_request_data['_tag']]);

        if ($consume === null) {
            $error_message = 'consume not found ' . $this->elma_request_data['_tag'];
            Yii::error($error_message, __METHOD__);
            throw new Exception($error_message);
        }

        $publisher = new Publisher($consume, $this->elma_request_data);

        $billmaster = Billmaster::build($publisher->tag);

        try {
            $check_status = $billmaster
                ->nci()
                ->checkStatus($this->agentRequestId);
        } catch (Throwable $e) {
            throw new RetryableException('retry');
        }

        if ($check_status->getValue('message') === 'WAITING') {
            throw new RetryableException('retry');
        }


        if ($check_status->getValue('message') === 'SUCCESS') {
            $publisher->sendSuccess(['status' => 'success', 'data' => $this->data]);
        } else {
            $publisher->sendError($check_status->errorMessage());
        }
    }

    public function getTtr(): int
    {
        return 30;
    }

    public function canRetry($attempt, $error): bool
    {
        return $attempt <= 30;
    }
}
