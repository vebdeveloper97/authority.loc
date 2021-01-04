<?php

namespace console\modules\elma\services;

use Yii;
use tune\billmaster\ResponseManager;
use console\modules\elma\models\Publisher;

/**
 * Class BaseService
 *
 * @package console\modules\elma\services
 */
class BaseService
{
    public Publisher $publisher;
    public array $data;

    /**
     * BaseService constructor.
     *
     * @param Publisher $publisher
     * @param array     $data
     */
    public function __construct(Publisher $publisher, array $data)
    {
        $this->publisher = $publisher;
        $this->data = $data;

        $this->publisher->setElmaRequestData($data);
    }

    /** @noinspection JsonEncodingApiUsageInspection */
    public function getFromFile(string $name): array
    {
        return json_decode(file_get_contents(Yii::getAlias("@root/data/{$name}")), true);
    }

    /**
     * @param array           $result
     * @param ResponseManager $response_manager
     *
     * @return array
     */
    public function resultWithAgentRequestId(array $result, ResponseManager $response_manager): array
    {
        $result['agentRequestId'] = $response_manager->getAgentRequestId();

        return $result;
    }
}
