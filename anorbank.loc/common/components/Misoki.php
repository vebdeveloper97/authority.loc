<?php

namespace common\components;

use JsonException;
use GuzzleHttp\Client;
use Yii;
use yii\base\Exception;

/**
 * Class Misoki
 *
 * @package common\components
 */
class Misoki extends BaseHttpComponent
{
    /**
     * @param array $params
     *
     * @return array
     * @throws Exception
     * @throws JsonException
     */
    public function registration(array $params): array
    {
        return $this->send('/inquiry/individual', [
            'claim_id'              => (string)$params['claim_id'],
            'claim_date'            => $params['claim_date'],
            'inn'                   => $params['inn'],
            'claim_number'          => (string)$params['claim_number'],
            'agreement_number'      => (string)$params['agreement_number'],
            'agreement_date'        => $params['agreement_date'],
            'resident'              => '1',
            'document_type'         => '6',
            'document_serial'       => $params['document_serial'],
            'document_number'       => $params['document_number'],
            'document_date'         => $params['document_date'],
            'gender'                => $params['gender'],
            'client_type'           => '08',
            'birth_date'            => $params['birth_date'],
            'document_region'       => $params['document_region'],
            'document_district'     => $params['document_district'],
            'nibbd'                 => '99999999',
            'family_name'           => $params['family_name'],
            'name'                  => $params['name'],
            'patronymic'            => $params['patronymic'],
            'registration_region'   => $params['registration_region'],
            'registration_district' => $params['registration_district'],
            'registration_address'  => mb_substr($params['registration_address'], 0, 30),
            'phone'                 => $params['phone'],
            'pin'                   => $params['pin'],
            'katm_sir'              => '',
            'live_address'          => mb_substr($params['live_address'], 0, 30),
            'live_cadastr'          => '',
            'registration_cadastr'  => '',
        ]);
    }

    /**
     * @param array $params
     *
     * @return array
     * @throws Exception
     * @throws JsonException
     */
    public function getEstate(array $params): array
    {
        return $this->send('/report/getReport', [
            'claim_id'    => $params['claim_id'],
            'report_type' => '013',
            'client_type' => '2',
        ]);
    }

    /**
     * @param array $params
     *
     * @return array
     * @throws Exception
     * @throws JsonException
     */
    public function getPropiska(array $params): array
    {
        $result = $this->send('/report/getReport', [
            'claim_id'    => $params['claim_id'],
            'report_type' => '011',
            'client_type' => '2',
        ]);

        return $result['response'] ?? [];
    }

    /**
     * @param string $method_url
     * @param array  $data
     *
     * @return array
     * @throws Exception
     * @throws JsonException
     */
    private function send(string $method_url, array $data): array
    {
        $content = $this->sendPost(
            'misoki',
            new Client([
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ],
            ]),
            Yii::$app->params['asoki']['url8000'] . $method_url,
            json_encode([
                'header'  => [
                    'type' => 'B',
                    'code' => Yii::$app->params['asoki']['bank_mfo'],
                ],
                'request' => $data,
            ], JSON_THROW_ON_ERROR, 512),
            false
        );

        $data = $this->json2array($content);

        $code = $data['result']['code'] ?? 0;

        if ($code !== '05000') {
            throw new Exception($data['result']['message'] ?? 'unknown misoki error');
        }

        return $data['response'] ?? [];
    }
}
