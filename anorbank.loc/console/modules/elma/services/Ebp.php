<?php

namespace console\modules\elma\services;

use Yii;
use Throwable;

/**
 * Class Ebp
 *
 * @package console\modules\elma\services
 */
class Ebp extends BaseService
{
    /**
     * @param array $params [branch,tin]
     */
    public function small(array $params): void
    {
        try {
            $res = Yii::$app
                ->ebp
                ->identifyIndividual($params['branch'], $params['tin']);

            if ($res->isSuccess()) {
                $this->publisher->sendSuccess($res->content());
            } else {
                $this->publisher->sendError($res->errorMessage());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage());
        }
    }

    /**
     * @param array $params [branch,tin]
     */
    public function big(array $params): void
    {
        try {
            $res = Yii::$app
                ->ebp
                ->identifyLegalEntity($params['branch'], $params['tin']);

            if ($res->isSuccess()) {
                $this->publisher->sendSuccess($res->content());
            } else {
                $this->publisher->sendError($res->errorMessage());
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage());
        }
    }
}
