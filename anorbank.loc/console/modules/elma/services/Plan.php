<?php

namespace console\modules\elma\services;

use Throwable;
use Ramsey\Uuid\Uuid;
use yii\helpers\ArrayHelper;
use tune\billmaster\Billmaster;
use yii\web\BadRequestHttpException;
use console\modules\elma\models\Publisher;
use tune\base\http\exceptions\RequestException;
use tune\base\http\exceptions\NetworkException;

/**
 * Class Plan
 *
 * @package console\modules\elma\services
 */
class Plan extends BaseService
{
    private Billmaster $billmaster;

    /**
     * Customer constructor.
     *
     * @param Publisher $publisher
     * @param array     $data
     *
     * @throws BadRequestHttpException
     * @throws NetworkException
     * @throws RequestException
     */
    public function __construct(Publisher $publisher, array $data)
    {
        parent::__construct($publisher, $data);
        $this->billmaster = \common\components\Billmaster::build($this->publisher->tag);
    }

    /**
     * @param array $params
     */
    public function createMerchant(array $params): void
    {
        try {
            $create_merchant = $this
                ->billmaster
                ->plan()
                ->createMerchant($params['merchant']);

            if ($create_merchant->hasError()) {
                $this
                    ->publisher
                    ->sendError($create_merchant->errorMessage(), $create_merchant->getAgentRequestId());
            } else {
                $this
                    ->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId(
                            ['data' => ArrayHelper::getValue($create_merchant->content(), 'data.data')],
                            $create_merchant
                        )
                    );
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params
     */
    public function updateMerchant(array $params): void
    {
        try {
            $update_merchant = $this
                ->billmaster
                ->plan()
                ->updateMerchant($params);

            if ($update_merchant->hasError()) {
                $this
                    ->publisher
                    ->sendError($update_merchant->errorMessage(), $update_merchant->getAgentRequestId());
            } else {
                $this
                    ->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId(
                            ['data' => ArrayHelper::getValue($update_merchant->content(), 'data.statusCode')],
                            $update_merchant
                        )
                    );
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params [agentRequestId]
     */
    public function getSchemas(): void
    {
        $request_id = Uuid::uuid4()->toString();
        try {
            $schema = $this
                ->billmaster
                ->plan()
                ->getSchemas($request_id);

            if ($schema->isSuccess()) {
                $this->publisher->sendSuccess(
                    $this->resultWithAgentRequestId($schema->content(), $schema)
                );
            } else {
                $this->publisher->sendError($schema->errorMessage(), $schema->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher
                ->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }
}
