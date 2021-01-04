<?php

namespace console\modules\elma\services;

use Throwable;
use common\components\Billmaster;
use yii\web\BadRequestHttpException;
use console\modules\elma\models\Publisher;
use tune\base\http\exceptions\NetworkException;
use tune\base\http\exceptions\RequestException;

/**
 * Class Client
 *
 * @package console\modules\elma\services
 */
class Client extends BaseService
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
     * @param array $data
     */
    public function update(array $data): void
    {
        try {
            $update = $this->billmaster
                ->client()->update($data['client']);

            if ($update->isSuccess()) {
                $this->publisher->sendSuccess(
                    $this->resultWithAgentRequestId($update->content(), $update)
                );
            } else {
                $this->publisher->sendError($update->errorMessage(), $update->getAgentRequestId());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage(), $this->billmaster->lastAgentRequestId);
        }
    }
}
