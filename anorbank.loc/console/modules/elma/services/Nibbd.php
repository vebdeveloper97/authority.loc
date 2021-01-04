<?php

namespace console\modules\elma\services;

use Yii;
use Throwable;
use tune\base\http\exceptions\RequestException;
use tune\base\http\exceptions\NetworkException;

/**
 * Class Nibbd
 *
 * @package console\modules\elma\services
 */
class Nibbd extends BaseService
{
    /**
     * @param array $params
     */
    public function getPhysical(array $params): void
    {
        try {
            $result = Yii::$app->nibbd->getPhysical([
                'inn'             => $params['inn'] ?? '',
                'pin'             => $params['pin'] ?? '',
                'passport_seria'  => $params['passport_seria'] ?? '',
                'passport_number' => $params['passport_number'] ?? '',
                'date_birth'      => $params['date_birth'] ?? '',
                'agreement'       => $params['agreement'] ?? '1',
            ]);

            if ($result->hasError()) {
                $this->publisher->sendError($result->errorMessage());
                return;
            }

            $data = $result->content();

            if ($result->getValue('inn') === '') {
                try {
                    $result2 = Yii::$app->nibbd->getPhysical([
                        'inn'             => '',
                        'pin'             => '',
                        'passport_seria'  => $params['passport_seria'] ?? '',
                        'passport_number' => $params['passport_number'] ?? '',
                        'date_birth'      => '',
                        'agreement'       => $params['agreement'] ?? '1',
                    ]);

                    if ($result2->getValue('inn') !== '') {
                        $data['inn'] = $result2->getValue('inn');
                    }
                } catch (Throwable $e) {
                    $this->publisher->sendSuccess($data);
                    return;
                }
            }

            $this->publisher->sendSuccess($data);
        } catch (NetworkException $e) {
            $this->publisher->sendError('network problem');
        } catch (RequestException $e) {
            $this->publisher->sendError('request error, code: ' . $e->getCode());
        }
    }
}
