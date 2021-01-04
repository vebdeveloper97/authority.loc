<?php

namespace console\modules\elma\services;

use Throwable;
use Yii;

/**
 * Class Soliq
 *
 * @package console\modules\elma\services
 */
class Soliq extends BaseService
{
    /**
     * @param array $data ['tin']
     */
    public function personalPropertyInformation(array $data): void
    {
        $tin = $data['tin'] ?? null;

        try {
            $_info = Yii::$app
                ->soliq->personal()
                ->propertyInformation($tin);
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage());
            return;
        }

        $result = [];

        if ($_info->hasClientError()) {
            $this->publisher->sendSuccess(['data' => []]);
            return;
        }

        foreach ($_info->data as $item) {
            $result[] = $item->attributes;
        }

        $this->publisher->sendSuccess(['data' => $result]);
    }

    /**
     * @param array $data ['tin']
     */
    public function personalSalaryInformation(array $data): void
    {
        $tin = $data['tin'] ?? '';
        $pnfl = $data['pnfl'] ?? '';
        $passport_series = $data['passport_series'] ?? '';
        $passport_number = $data['passport_number'] ?? '';

        try {
            $_info = Yii::$app
                ->soliq
                ->personal()
                ->salaryInformation($tin, $pnfl, $passport_series, $passport_number);
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage());
            return;
        }

        $result = [];

        if ($_info->hasClientError()) {

            if ($_info->clientErrorMessage === 'Маьлумот топилмади') {
                $this->publisher->sendSuccess(['data' => []]);
            }

            $this->publisher->sendError($_info->clientErrorMessage);
            return;
        }

        foreach ($_info->data as $item) {
            $result[] = $item->attributes;
        }

        $this->publisher->sendSuccess(['data' => $result]);
    }

    /**
     * @param array $data [tin,lang]
     */
    public function entityInformation(array $data): void
    {
        try {
            $soliq = Yii::$app
                ->soliq
                ->entity()
                ->information($data['tin']);

            if ($soliq->isClientSuccess()) {
                $this->publisher->sendSuccess($soliq->data);
            } else {
                $this->publisher->sendError($soliq->clientErrorMessage);
            }
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage());
        }
    }
}
