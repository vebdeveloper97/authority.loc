<?php

namespace console\modules\elma\services;

use Throwable;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use tune\billmaster\Billmaster;
use yii\web\BadRequestHttpException;
use console\modules\elma\models\Publisher;
use tune\base\http\exceptions\NetworkException;
use tune\base\http\exceptions\RequestException;

/**
 * Class Humo
 *
 * @package console\modules\elma\services
 */
class Humo extends BaseService
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
    public function create(array $params): void
    {
        try {
            $create = $this
                ->billmaster
                ->humo()
                ->cardCreate($params);

            if ($create->hasError()) {
                $this
                    ->publisher
                    ->sendError($create->errorMessage(), $create->getAgentRequestId());
            } else {
                $this
                    ->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId(
                            $create->content(),
                            $create
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
    public function active(array $params): void
    {
        try {
            $active = $this
                ->billmaster
                ->humo()
                ->cardActive($params);

            if ($active->hasError()) {
                $this
                    ->publisher
                    ->sendError($active->errorMessage(), $active->getAgentRequestId());
            } else {
                $this
                    ->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId(
                            $active->content(),
                            $active
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
    public function getByClient(array $params): void
    {
        try {
            $get_by_client = $this
                ->billmaster
                ->humo()
                ->cardGetByClient($params);

            if ($get_by_client->hasError()) {
                $this
                    ->publisher
                    ->sendError($get_by_client->errorMessage(), $get_by_client->getAgentRequestId());
            } else {
                $this
                    ->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId(
                            $get_by_client->content(),
                            $get_by_client
                        )
                    );
            }
        } catch (Throwable $e) {
            $this->publisher->sendError('billmaster network error', $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params
     */
    public function getByPan(array $params): void
    {
        try {
            $get_by_pan = $this
                ->billmaster
                ->humo()
                ->cardGetByPan($params);

            if ($get_by_pan->hasError()) {
                $this
                    ->publisher
                    ->sendError($get_by_pan->errorMessage(), $get_by_pan->getAgentRequestId());
            } else {
                $this
                    ->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId(
                            $get_by_pan->content(),
                            $get_by_pan
                        )
                    );
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params [card => [card]]
     */
    public function getReal(array $params): void
    {
        try {
            $real_card = $this
                ->billmaster
                ->humo()
                ->cardGetReal($params['card']['card'] ?? '');

            if ($real_card->hasError()) {
                $this->publisher
                    ->sendError($real_card->errorMessage(), $real_card->getAgentRequestId());
            } else {
                $this->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId(
                            [
                                'realCardNumber' => ArrayHelper::getValue($real_card->content(), 'data.realCardDetails.realCardNumber'),
                            ],
                            $real_card
                        )
                    );
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params [merchant]
     */
    public function terminalsGetByMerchantId(array $params): void
    {
        try {
            $terminal = $this
                ->billmaster
                ->humo()
                ->terminalGetByMerchantId($params['merchant']);

            if ($terminal->hasError()) {
                $this->publisher
                    ->sendError($terminal->errorMessage(), $terminal->getAgentRequestId());
            } else {
                $this->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId(
                            [
                                'data' => $terminal->getValue('data', []),
                            ],
                            $terminal
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
    public function cardCreateInstallment(array $params): void
    {
        $params['limit'] = empty($params['limit']) ? new BaseObject() : $params['limit'];

        try {
            $create = $this
                ->billmaster
                ->humo()
                ->cardCreateInstallment([
                    'cardRequest' => $params,
                ]);

            if ($create->getIsRetryRequest()) {
                $this->publisher
                    ->setError($create->errorMessage())
                    ->sendSuccess($this->resultWithAgentRequestId([], $create), $create->getRetryRequestId());
            } else if ($create->isSuccess()) {
                $this->publisher
                    ->sendSuccess($this->resultWithAgentRequestId($create->content(), $create));
            } else {
                $this->publisher
                    ->sendError($create->errorMessage(), $create->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params
     */
    public function cardCreateDebit(array $params): void
    {
        try {
            $create_debit = $this
                ->billmaster
                ->humo()
                ->createDebit(['cardRequest' => $params]);

            if ($create_debit->getIsRetryRequest()) {
                $this->publisher
                    ->setError($create_debit->errorMessage())
                    ->sendSuccess(
                        $this->resultWithAgentRequestId([], $create_debit), $create_debit->getRetryRequestId(),
                    );
            } else if ($create_debit->isSuccess()) {
                $this->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId($create_debit->content(), $create_debit)
                    );
            } else {
                $this->publisher->sendError($create_debit->errorMessage(), $create_debit->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params
     */
    public function cardActiveDebit(array $params): void
    {
        try {
            $active_debit = $this
                ->billmaster
                ->humo()
                ->cardActiveDebit(['cardRequest' => $params]);

            if ($active_debit->getIsRetryRequest()) {
                $this->publisher
                    ->setError($active_debit->errorMessage())
                    ->sendSuccess(
                        $this->resultWithAgentRequestId([], $active_debit), $active_debit->getRetryRequestId()
                    );
            } else if ($active_debit->isSuccess()) {
                $this->publisher->sendSuccess(
                    $this->resultWithAgentRequestId($active_debit->content(), $active_debit)
                );
            } else {
                $this->publisher->sendError($active_debit->errorMessage(), $active_debit->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params
     */
    public function cardActiveInstallment(array $params): void
    {
        try {
            $active = $this
                ->billmaster
                ->humo()
                ->cardActiveInstallment(['cardRequest' => $params]);

            if ($active->getIsRetryRequest()) {
                $this->publisher
                    ->setError($active->errorMessage())
                    ->sendSuccess(
                        $this->resultWithAgentRequestId([], $active), $active->getRetryRequestId()
                    );
            } else if ($active->isSuccess()) {
                $this->publisher
                    ->sendSuccess($this->resultWithAgentRequestId($active->content(), $active));
            } else {
                $this->publisher->sendError($active->errorMessage(), $active->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params
     */
    public function cardCreateCorporate(array $params): void
    {
        try {
            $create = $this
                ->billmaster
                ->humo()
                ->cardCreateCorporate($params);

            if ($create->getIsRetryRequest()) {
                $this->publisher
                    ->setError($create->errorMessage())
                    ->sendSuccess(
                        $this->resultWithAgentRequestId([], $create), $create->getRetryRequestId()
                    );
            } else if ($create->isSuccess()) {
                $this->publisher
                    ->sendSuccess($this->resultWithAgentRequestId($create->content(), $create));
            } else {
                $this->publisher
                    ->sendError($create->errorMessage(), $create->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params
     */
    public function cardAddStop(array $params): void
    {
        try {
            $addToStop = $this
                ->billmaster
                ->humo()
                ->cardAddToStop($params['card']);

            if ($addToStop->isSuccess()) {
                $this->publisher
                    ->sendSuccess($this->resultWithAgentRequestId($addToStop->content(), $addToStop));
            } else {
                $this->publisher->sendError($addToStop->errorMessage(), $addToStop->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }

    /**
     * @param array $params
     */
    public function cardRemoveStop(array $params): void
    {
        try {
            $removeCard = $this
                ->billmaster
                ->humo()
                ->cardRemoveFromStop($params['card']);

            if ($removeCard->isSuccess()) {
                $this->publisher
                    ->sendSuccess($this->resultWithAgentRequestId($removeCard->content(), $removeCard));
            } else {
                $this->publisher
                    ->sendError($removeCard->errorMessage(), $removeCard->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }
}
