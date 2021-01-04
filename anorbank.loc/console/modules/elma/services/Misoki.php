<?php

namespace console\modules\elma\services;

use Yii;
use Throwable;

/**
 * Class Misoki
 *
 * @package console\modules\elma\services
 */
class Misoki extends BaseService
{
    /**
     * @param array $params [
     *                      claim_id
     *                      claim_date
     *                      inn
     *                      claim_number
     *                      agreement_number
     *                      agreement_date
     *                      document_serial
     *                      document_number
     *                      document_date
     *                      gender
     *                      birth_date
     *                      document_region
     *                      document_district
     *                      family_name
     *                      name
     *                      patronymic
     *                      registration_region
     *                      registration_district
     *                      registration_address
     *                      phone
     *                      pin
     *                      live_address
     *                      ]
     */
    public function registration(array $params): void
    {
        try {
            $data = Yii::$app->misoki->registration($params);

            $this->publisher->sendSuccess($data);
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage());
        }
    }

    /**
     * @param array $params [claim_id]
     */
    public function getEstate(array $params): void
    {
        try {
            $data = Yii::$app->misoki->getEstate($params);
            $this->publisher->sendSuccess($data);
        } catch (Throwable $e) {
            $this->publisher->sendError($e);
        }
    }

    /**
     * @param array $params [claim_id]
     */
    public function getPropiska(array $params): void
    {
        try {
            $data = Yii::$app->misoki->getPropiska($params);
            $this->publisher->sendSuccess($data);
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage());
        }
    }
}
