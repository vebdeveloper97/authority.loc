<?php /** @noinspection JsonEncodingApiUsageInspection */

namespace console\modules\elma\models;

use Yii;
use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use common\modules\request_log\models\RequestLog;
use common\modules\rabbitmq\models\RabbitMQConsume;

/**
 * Class Publisher
 *
 * @package console\modules\elma\models
 */
class Publisher
{
    public array $data;
    private AMQPStreamConnection $connection;
    private string $pair_id = '';
    public ?string $tag = '';
    private ?string $error_message = null;

    /**
     * @var RabbitMQConsume
     */
    private RabbitMQConsume $consume;

    /**
     * Publisher constructor.
     *
     * @param RabbitMQConsume $consume
     * @param array           $elmaRequestData
     */
    public function __construct(RabbitMQConsume $consume, array $elmaRequestData = [])
    {
        if (!empty($elmaRequestData)) {
            $this->setElmaRequestData($elmaRequestData);
        }

        $this->data = $elmaRequestData;

        $this->connection = new AMQPStreamConnection(
            $consume->connection->host,
            $consume->connection->port,
            $consume->connection->user,
            $consume->connection->password,
            $consume->connection->vhost
        );

        $this->consume = $consume;
    }

    public function setElmaRequestData(array $data): void
    {
        $this->tag = $data['_tag'] ?? null;
        $this->data = $data;
    }

    /**
     * @param string $error_message
     *
     * @return $this
     */
    public function setError(string $error_message): Publisher
    {
        $this->error_message = $error_message;
        return $this;
    }

    public function sendError(string $errorMessage, ?string $agentRequestId = ""): void
    {
        $this->data['MessageContent'] = [];
        $this->data['ErrorMessage'] = $errorMessage;
        $this->data['Success'] = '0';
        $this->data['MessageType'] = 'DataResponse';
        $this->data['AgentRequestId'] = $agentRequestId;
        $publish_data = $this->data;
        unset($publish_data['_tag']);

        $this->publish(json_encode($publish_data, JSON_UNESCAPED_UNICODE));
    }

    public function sendSuccess(array $params, string $retryRequestId = ''): void
    {

        if ($this->error_message !== null) {
            $this->data['ErrorMessage'] = $this->error_message;
        } else {
            $this->data['ErrorMessage'] = '';
        }

        $this->data['MessageContent'] = $params;
        $this->data['Success'] = $retryRequestId !== '' ? '2' : '1';
        $this->data['MessageType'] = 'DataResponse';
        $this->data['RetryRequestId'] = $retryRequestId;
        $publish_data = $this->data;
        unset($publish_data['_tag']);

        $this->publish(json_encode($publish_data, JSON_UNESCAPED_UNICODE));
    }

    public function sendReport(array $params): void
    {
        unset($params['Action'], $params['Method'], $params['MessageContent'], $params['_tag']);
        $params['MessageType'] = 'DeliveryReport';

        $this->publish(json_encode($params));
    }

    public function getElmaRequestData(): array
    {
        return $this->data;
    }

    public function getMethodName(): ?string
    {
        return $this->data['Method'] ?? null;
    }

    private function publish(string $body): void
    {
        $date = date('Y-m-d H:i:s');
        echo "\nRESPONSE {$date}\n";
        echo $body;
        $channel = $this->connection->channel();

        if (in_array($this->tag, ['elma', 'prod', 'main', 'test162'])) {
            $queue_name = "BELTtoELMA";
            $exchange_name = "ExBELTtoELMA";
        } else {
            $queue_name = "{$this->tag}BELTtoELMA";
            $exchange_name = "{$this->tag}ExBELTtoELMA";
        }

        if ($this->tag === 'main') {
            $queue_name = "prodBELTtoELMA";
            $exchange_name = "prodExBELTtoELMA";
        }

        $channel->queue_declare($queue_name, false, true, false, false);
        $channel->exchange_declare($exchange_name, AMQPExchangeType::DIRECT, false, true, false);
        $channel->queue_bind($queue_name, $exchange_name);
        $message = new AMQPMessage($body, ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $channel->basic_publish($message, $exchange_name);
        $channel->close();

        $request_log = new RequestLog();
        $request_log->pair_id = $this->pair_id;
        $request_log->service = $this->tag;
        $request_log->type = RequestLog::TYPE_RESPONSE;
        $request_log->body = $body;

        if (!$request_log->save(false)) {
            Yii::error([$request_log->errors, $request_log->attributes], __METHOD__);
        }
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        $this->connection->close();
    }

    /**
     * @return string
     */
    public function getPairId(): string
    {
        return $this->pair_id;
    }

    /**
     * @param string $pair_id
     */
    public function setPairId(string $pair_id): void
    {
        $this->pair_id = $pair_id;
    }
}
