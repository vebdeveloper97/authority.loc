<?php

namespace console\modules\elma\services;

use Yii;
use Throwable;
use JsonException;
use common\helpers\XmlHelper;
use console\modules\elma\jobs\AsokiStatusJob;

/**
 * Class Asoki
 *
 * @package console\modules\elma\services
 */
class Asoki extends BaseService
{
    /**
     * @param array $params [claim_id]
     *
     * @return void
     */
    public function getXML(array $params): void
    {
        try {
            $report = Yii::$app->asoki->report($params['claim_id']);
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage());
            return;
        }

        $token = $report['data']['token'];
        $status = $report['data']['result'] ?? '0';

        if ($status !== '05050') {
            $error_message = $report['errorMessage'] ?? '';

            if ($error_message === '') {
                $error_message = $report['data']['resultMessage'] ?? 'unknown error';
            }

            $this->publisher->sendError($error_message);
            return;
        }

        $job = new AsokiStatusJob([
            'claim_id' => $params['claim_id'],
            'token'    => $token,
        ]);
        $job->elma_request_data = $this->publisher->getElmaRequestData();

        Yii::$app
            ->queue
            ->delay(15)
            ->push($job);
    }

    /**
     * @param array $params ['base64']
     */
    public function parseXML(array $params): void
    {
        $xml = base64_decode($params['base64']);
        try {
            $data = XmlHelper::xml2array($xml);
        } catch (JsonException $e) {
            $this->publisher->sendError('xml parse error');
            return;
        }

        $data_contracts = $data['contracts']['contract'] ?? [];
        $contracts = [];

        foreach ($data_contracts as $item) {
            $contracts[] = [
                'branch'                => $this->getContractValue($item, 'branch'),
                'percent'               => $this->getContractValue($item, 'percent'),
                'summa'                 => $this->getContractValue($item, 'summa'),
                'loan_summa'            => $this->getContractValue($item, 'loan_summa'),
                'expired_summa'         => $this->getContractValue($item, 'expired_summa'),
                'expired_summa_change'  => $this->getContractValue($item, 'expired_summa_change'),
                'expired_date'          => $this->getContractValue($item, 'expired_date'),
                'low_summa'             => $this->getContractValue($item, 'low_summa'),
                'fall_summa'            => $this->getContractValue($item, 'fall_summa'),
                'expired_percent_summa' => $this->getContractValue($item, 'expired_percent_summa'),
                'fall_percent_summa'    => $this->getContractValue($item, 'fall_percent_summa'),
                'percent_summa'         => $this->getContractValue($item, 'percent_summa'),
                'schedule'              => $item['schedule']['schedule_info'] ?? [],
            ];
        }

        $this->publisher->sendSuccess([
            'demand_id' => $data['sysinfo']['demand_id'],
            'contracts' => $contracts,
        ]);
    }

    /**
     * @param array $params = ['claim_id' => '']
     */
    public function getKiatsScore(array $params): void
    {
        try {
            $report = Yii::$app->asoki->scoreReport($params['claim_id']);
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage());
            return;
        }

        if (isset($report['data']['token']) === false) {
            $this->publisher->sendError('token not given');
        }

        $job = new AsokiStatusJob();
        $job->claim_id = $params['claim_id'];
        $job->token = $report['data']['token'];
        $job->elma_request_data = $this->publisher->getElmaRequestData();

        Yii::$app->queue->push($job);
    }

    /**
     * @param array $params [claim_id]
     */
    public function mib(array $params): void
    {
        try {
            $result = Yii::$app->asoki->mib($params['claim_id']);
        } catch (Throwable $e) {
            $this->publisher->sendError($e->getMessage());
        }

        $is_ok = isset($result['data']['result']) && $result['data']['result'] === '05000';

        if ($is_ok) {
            $data = $result['data']['reportBase64'] ?? '';

            if (empty($data)) {
                $this->publisher->sendError('empty data');
                return;
            }

            try {
                $debts = json_decode(base64_decode($data), true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                $this->publisher->sendError('json parse error');
                return;
            }

            if (isset($debts['report'])) {
                $this->publisher->sendSuccess($debts['report']);
                return;
            }

            $this->publisher->sendError('asoki: report not found');
            return;
        }

        $this->publisher->sendError($result['data']['resultMessage'] ?? 'asoki: unknown error');
    }

    /**
     * @param array $item
     * @param       $name
     *
     * @return mixed|string
     */
    private function getContractValue(array $item, $name)
    {
        $value = $item[$name] ?? '';

        if (is_array($value)) {
            $value = $value[0] ?? '';
        }

        return $value;
    }
}
