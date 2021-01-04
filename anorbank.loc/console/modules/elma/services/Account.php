<?php

namespace console\modules\elma\services;

use Throwable;
use common\components\Billmaster;
use yii\web\BadRequestHttpException;
use console\modules\elma\models\Publisher;
use tune\base\http\exceptions\RequestException;
use tune\base\http\exceptions\NetworkException;

/**
 * Class Account
 *
 * @package console\modules\elma\services
 */
class Account extends BaseService
{
    private \tune\billmaster\Billmaster $billmaster;

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
        $this->billmaster = Billmaster::build($this->publisher->tag);
    }

    /**
     * @param array $params
     */
    public function get(array $params): void
    {
        try {
            $create = $this->billmaster
                ->account()
                ->get($params);

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
                        ));
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }
}
