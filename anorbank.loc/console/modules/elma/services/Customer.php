<?php

namespace console\modules\elma\services;

use Throwable;
use yii\helpers\ArrayHelper;
use tune\billmaster\Billmaster;
use yii\web\BadRequestHttpException;
use console\modules\elma\models\Publisher;
use tune\base\http\exceptions\RequestException;
use tune\base\http\exceptions\NetworkException;

/**
 * Class Customer
 *
 * @package console\modules\elma\services
 */
class Customer extends BaseService
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
     *
     */
    public function get(array $params): void
    {


        try {
            $get = $this->billmaster
                ->customer()
                ->get($params);

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
     * @param array $params
     */
    public function update(array $params): void
    {
        try {
            $update = $this->billmaster
                ->customer()
                ->update($params);

            if ($update->hasError()) {
                $this
                    ->publisher
                    ->sendError($update->errorMessage(), $update->getAgentRequestId());
            } else {
                $this
                    ->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId(
                            $update->content(),
                            $update
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
    public function block(array $params): void
    {
        try {
            $block = $this->billmaster
                ->customer()
                ->black($params);

            if ($block->hasError()) {
                $this
                    ->publisher
                    ->sendError($block->errorMessage(), $block->getAgentRequestId());
            } else {
                $this
                    ->publisher
                    ->sendSuccess(
                        $this->resultWithAgentRequestId(
                            ArrayHelper::getValue($block->content(), 'data', []),
                            $block
                        )
                    );
            }
            $this->publisher->sendError('billmaster network error');
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }
}
