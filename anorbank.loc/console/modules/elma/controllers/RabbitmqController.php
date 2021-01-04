<?php

namespace console\modules\elma\controllers;

use Yii;
use Exception;
use Throwable;
use JsonException;
use ErrorException;
use Ramsey\Uuid\Uuid;
use yii\helpers\Console;
use yii\console\ExitCode;
use yii\console\Controller;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use console\modules\elma\models\Publisher;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use common\modules\request_log\models\RequestLog;
use common\modules\rabbitmq\models\RabbitMQConsume;

/**
 * Class RabbitmqController
 *
 * @package console\modules\elma\controllers
 */
class RabbitmqController extends Controller
{
    private ?RabbitMQConsume $consume = null;
    private ?string $tag = null;

    /**
     * @param string $tag
     *
     * @throws ErrorException
     * @return int|null
     */
    public function actionConsume(string $tag): ?int
    {
        $this->tag = $tag;
        $this->consume = RabbitMQConsume::findOne(['tag' => $this->tag]);

        if ($this->consume === null) {
            Console::stderr('consume not found ' . $this->tag);
            return ExitCode::DATAERR;
        }

        $connection = new AMQPStreamConnection(
            $this->consume->connection->host,
            $this->consume->connection->port,
            $this->consume->connection->user,
            $this->consume->connection->password,
            $this->consume->connection->vhost
        );

        $channel = $connection->channel();

        $channel->queue_declare(
            $this->consume->queue->name,
            false,
            true,
            false,
            false
        );

        $channel->exchange_declare(
            $this->consume->exchange->name,
            AMQPExchangeType::DIRECT,
            false,
            true,
            false
        );

        $channel->queue_bind($this->consume->queue->name, $this->consume->exchange->name);

        $channel->basic_consume(
            $this->consume->queue->name,
            $this->consume->tag,
            false,
            false,
            false,
            false,
            [$this, 'messageHandler']
        );

        register_shutdown_function(__NAMESPACE__ . '\RabbitmqController::shutdown', $channel, $connection);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        return null;
    }

    /**
     * @param AMQPMessage $message
     */
    public function messageHandler(AMQPMessage $message): void
    {
        $date = date('Y-m-d H:i:s');
        echo "\nREQUEST {$date}\n";
        echo $message->body;

        $pair_id = Uuid::uuid4()->toString();

        $request_log = new RequestLog();
        $request_log->pair_id = $pair_id;
        $request_log->service = $this->tag;
        $request_log->type = RequestLog::TYPE_REQUEST;
        $request_log->body = $message->body;

        $message->ack();

        $publisher = new Publisher($this->consume);
        $publisher->setPairId($pair_id);

        try {
            $data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR);

            if (isset($data['MessageType']) && $data['MessageType'] === 'DeliveryReport') {
                $request_log->save(false);
                return;
            }

            $data['_tag'] = $this->tag;
            $publisher->setElmaRequestData($data);
            $publisher->sendReport($data);

            $action = 'console\modules\elma\services\\' . $data['Action'] ?? '';

            if (!class_exists($action)) {
                if (!$request_log->save(false)) {
                    Yii::error([$request_log->errors, $request_log->attributes], __METHOD__);
                }
                $publisher->sendError("'{$data['Action']}' action not found");
                return;
            }

            $method = $data['Method'] ?? '';
            $params = $data['MessageContent'] ?? [];

            $request_log->service = "elma.{$data['Action']}.{$method}";

            if (!$request_log->save(false)) {
                Yii::error([$request_log->errors, $request_log->attributes], __METHOD__);
            }

            $obj = Yii::createObject($action, [$publisher, $data]);

            if (!method_exists($obj, $method)) {
                $publisher->sendError("'{$method}' method not found");
                return;
            }

            if (isset($data['RetryRequestId']) && !empty($data['RetryRequestId'])) {
                $params['retryRequestId'] = $data['RetryRequestId'];
            }

            try {
                $obj->$method($params);
            } catch (Throwable $e) {
                $publisher->sendError($e->getMessage());
            }
        } catch (JsonException $e) {
            $publisher->sendError('json syntax error');
        } catch (Throwable $e) {
            echo $e->getMessage();
            $publisher->sendError($e->getMessage());
        }
    }

    /**
     * @param AMQPChannel          $channel
     * @param AMQPStreamConnection $connection
     *
     * @throws Exception
     */
    public static function shutdown(AMQPChannel $channel, AMQPStreamConnection $connection): void
    {
        $channel->close();
        $connection->close();
    }
}
