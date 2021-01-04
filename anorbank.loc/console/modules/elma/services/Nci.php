<?php

namespace console\modules\elma\services;

use Yii;
use Throwable;
use yii\helpers\ArrayHelper;
use tune\billmaster\Billmaster;
use yii\web\BadRequestHttpException;
use console\modules\elma\models\Publisher;
use tune\base\http\exceptions\NetworkException;
use tune\base\http\exceptions\RequestException;
use console\modules\elma\jobs\BillmasterStatusJob;

/**
 * Class Nci
 *
 * @package console\modules\elma\services
 */
class Nci extends BaseService
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
    public function createEntity(array $params): void
    {
        try {
            $create_entity = $this
                ->billmaster
                ->nci()
                ->createEntity($params);

            if ($create_entity->getValue('status') !== 'WAITING') {
                $this
                    ->publisher
                    ->sendError($create_entity->errorMessage(), $create_entity->getAgentRequestId());
            } else {
                $job = new BillmasterStatusJob([
                    'agentRequestId'    => $create_entity->getAgentRequestId(),
                    'data'              => $create_entity->getValue('data', []),
                    'elma_request_data' => $this->publisher->getElmaRequestData(),
                ]);

                Yii::$app
                    ->queue
                    ->delay(30)
                    ->push($job);
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params [numberTaxRegistration]
     */
    public function legalEntity(array $params): void
    {
        try {
            $legal_entity = $this
                ->billmaster
                ->nci()
                ->legalEntity($params['numberTaxRegistration'] ?? '');

            if ($legal_entity->hasError()) {
                $this
                    ->publisher
                    ->sendError($legal_entity->errorMessage(), $legal_entity->getAgentRequestId());
            } else {
                $this
                    ->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId(
                            $legal_entity->getValue('data', []),
                            $legal_entity
                        )
                    );
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params [transId]
     */
    public function getInfo(array $params): void
    {
        try {
            $get_info = $this
                ->billmaster
                ->nci()
                ->getInfo($params['transId'] ?? '');

            if ($get_info->hasError()) {
                $this
                    ->publisher
                    ->sendError($get_info->hasError(), $get_info->getAgentRequestId());
            } else {
                $this
                    ->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId(
                            ArrayHelper::getValue($get_info->content(), 'data', []),
                            $get_info
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
    public function depositCrud(array $params): void
    {
        try {
            $crud = $this
                ->billmaster
                ->nci()
                ->depositCrud($params['client']);

            if ($crud->isSuccess()) {
                $this->publisher->sendSuccess(
                    $this->resultWithAgentRequestId(ArrayHelper::getValue($crud->content(), 'data.0'), $crud)
                );
            } else {
                $this->publisher->sendError($crud->errorMessage(), $crud->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params
     */
    public function loanCreate(array $params): void
    {
        try {
            $create = $this
                ->billmaster
                ->nci()
                ->loanCreate($params['client']);

            if ($create->isSuccess()) {
                $this->publisher->sendSuccess(
                    $this->resultWithAgentRequestId(ArrayHelper::getValue($create->content(), 'data.0'), $create)
                );
            } else {
                $this->publisher->sendError($create->errorMessage(), $create->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params
     */
    public function accountCreate(array $params): void
    {
        try {
            $create = $this
                ->billmaster
                ->nci()
                ->accountCreate($params['account']);

            if ($create->isSuccess()) {
                $this->publisher->sendSuccess(
                    $this->resultWithAgentRequestId(ArrayHelper::getValue($create->content(), 'data.0'), $create)
                );
            } else {
                $this->publisher->sendError($create->errorMessage(), $create->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params
     */
    public function getAllCreditProducts(array $params): void
    {
        try {
            $credits = $this->billmaster
                ->nci()
                ->getAllCreditProduct($params['transId']);

            if ($credits->isSuccess()) {
                $this->publisher->sendSuccess(
                    $this->resultWithAgentRequestId($credits->content(), $credits)
                );
            } else {
                $this->publisher->sendError($credits->errorMessage(), $credits->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params
     */
    public function humoCardGetByClient(array $params): void
    {
        try {
            $result = $this->billmaster
                ->nci()
                ->humoCardGetByClient($params['client']['bankClientNumber']);

            if ($result->isSuccess()) {
                $this->publisher->sendSuccess(
                    $this->resultWithAgentRequestId($result->content(), $result)
                );
            } else {
                $this->publisher->sendError($result->errorMessage(), $result->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params
     */
    public function installmentExist(array $params): void
    {
        try {
            $result = $this->billmaster
                ->nci()
                ->nciInstallmentExist($params['client']);

            if ($result->isSuccess()) {
                $this->publisher->sendSuccess(
                    $this->resultWithAgentRequestId($result->content(), $result)
                );
            } else {
                $this->publisher->sendError($result->errorMessage(), $result->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }
}
