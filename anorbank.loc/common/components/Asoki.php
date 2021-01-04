<?php

namespace common\components;

use JsonException;
use common\response\AsokiStatus;
use GuzzleHttp\Client;
use Yii;
use yii\base\Exception;

/**
 * Class Asoki
 *
 * @package app\components
 */
class Asoki extends BaseHttpComponent
{
    /**
     * @param string $claim_id
     *
     * @return array
     * @throws Exception
     * @throws JsonException
     */
    public function report(string $claim_id): array
    {
        return $this->send('katm-api/v1/credit/report', [
            'security' => [
                'pLogin'    => Yii::$app->params['asoki']['username'],
                'pPassword' => Yii::$app->params['asoki']['password'],
            ],
            'data'     => [
                'pHead'     => Yii::$app->params['asoki']['bank_code'],
                'pCode'     => Yii::$app->params['asoki']['bank_mfo'],
                'pLegal'    => 1,
                'pClaimId'  => $claim_id,
                'pReportId' => 8,
            ],
        ]);
    }

    /**
     * @param string $claim_id
     *
     * @return array
     * @throws Exception
     * @throws JsonException
     */
    public function scoreReport(string $claim_id): array
    {
        return $this->send('katm-api/v1/credit/report', [
            'security' => [
                'pLogin'    => Yii::$app->params['asoki']['username'],
                'pPassword' => Yii::$app->params['asoki']['password'],
            ],
            'data'     => [
                'pHead'         => Yii::$app->params['asoki']['bank_code'],
                'pCode'         => Yii::$app->params['asoki']['bank_mfo'],
                'pLegal'        => 1,
                'pClaimId'      => $claim_id,
                'pReportId'     => 22,
                'pReportFormat' => '1',
            ],
        ]);
    }

    /**
     * @param string $token
     * @param string $claim_id
     *
     * @return AsokiStatus
     * @throws Exception
     * @throws JsonException
     */
    public function status(string $token, string $claim_id): AsokiStatus
    {
        return new AsokiStatus($this->send('katm-api/v1/credit/report/status', [
            'data' => [
                'pHead'         => Yii::$app->params['asoki']['bank_code'],
                'pCode'         => Yii::$app->params['asoki']['bank_mfo'],
                'pToken'        => $token,
                'pClaimId'      => $claim_id,
                'pReportFormat' => '1',
            ],
        ]));
    }

    /**
     * @param string $claim_id
     *
     * @return array
     * @throws Exception
     * @throws JsonException
     */
    public function mib(string $claim_id): array
    {
        return $this->send('katm-api/v1/credit/report', [
            'security' => [
                'pLogin'    => Yii::$app->params['asoki']['username'],
                'pPassword' => Yii::$app->params['asoki']['password'],
            ],
            'data'     => [
                'pHead'         => Yii::$app->params['asoki']['bank_code'],
                'pCode'         => Yii::$app->params['asoki']['bank_mfo'],
                'pLegal'        => 1,
                'pClaimId'      => $claim_id,
                'pReportId'     => 39,
                'pReportFormat' => '1',
            ],
        ]);
    }

    /**
     * @param string $method
     * @param array  $data
     *
     * @return array
     * @throws Exception
     * @throws JsonException
     */
    private function send(string $method, array $data): array
    {
        $content = $this->sendPost(
            'asoki',
            new Client([
                'verify'  => false,
                'auth'    => [
                    Yii::$app->params['asoki']['username'],
                    Yii::$app->params['asoki']['password'],
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]),
            Yii::$app->params['asoki']['url8001'] . '/' . $method,
            json_encode($data, JSON_THROW_ON_ERROR, 512),
            false
        );

        return $this->json2array($content);
    }
}
