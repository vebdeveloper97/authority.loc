<?php

namespace console\modules\elma\services;

use Throwable;
use yii\helpers\ArrayHelper;
use tune\billmaster\Billmaster;
use yii\web\BadRequestHttpException;
use console\modules\elma\models\Publisher;
use tune\base\http\exceptions\NetworkException;
use tune\base\http\exceptions\RequestException;

/**
 * Class Deposit
 *
 * @package console\modules\elma\services
 */
class Deposit extends BaseService
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
    public function get(array $params): void
    {
        try {
            $get = $this->billmaster
                ->nci()
                ->getDeposit($params);

            if ($get->hasError()) {
                $this
                    ->publisher
                    ->sendError($get->errorMessage(), $get->getAgentRequestId());
            } else {
                $this
                    ->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId(
                            $get->content(),
                            $get
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
    public function getAll(array $params): void
    {
        try {
            $get_all = $this->billmaster
                ->nci()
                ->getAllDeposit($params['transId'] ?? '');

            if ($get_all->hasError()) {
                $this
                    ->publisher
                    ->sendError($get_all->errorMessage(), $get_all->getAgentRequestId());
            } else {
                $this->publisher->sendSuccess(
                    $this->resultWithAgentRequestId(
                        [
                            'data' => ArrayHelper::getValue($get_all->content(), 'data', []),
                        ],
                        $get_all
                    )
                );
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }
}
