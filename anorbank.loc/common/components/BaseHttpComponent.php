<?php

namespace common\components;

use DateTime;
use JsonException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Ramsey\Uuid\Uuid;
use Throwable;
use Yii;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\helpers\FileHelper;

/**
 * Class BaseHttpComponent
 *
 * @package app\components
 *
 * @property string $sessionID
 */
class BaseHttpComponent extends BaseObject
{
    private string $session_id = '';

    /**
     * @param string $content
     *
     * @return array
     * @throws JsonException
     */
    protected function json2array(string $content): array
    {
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param string $service
     * @param string $data
     * @param bool   $isRequest
     * @param bool   $isError
     */
    protected function saveLog(string $service, string $data, bool $isRequest = true, $isError = false): void
    {
        if ($this->session_id === '') {
            $this->session_id = $this->getSessionID();
        }

        $folder = Yii::getAlias('@runtime/logs/' . date('Y/m/d'));

        try {
            if (file_exists($folder) === false) {
                FileHelper::createDirectory($folder);
            }

            $file_path = $folder . DIRECTORY_SEPARATOR . $service . '.log';
            try {
                $date = (new DateTime())->format('Y-m-d H:i:s.u');
            } catch (Throwable $e) {
                $date = date('Y-m-d H:i:s');
            }
            $type = $isRequest ? 'REQUEST' : 'RESPONSE';

            if ($isError) {
                $type = 'ERROR';
            }

            $data = "{$date} {$this->session_id} {$type} {$data}";

            file_put_contents($file_path, $data . "\n", FILE_APPEND);
        } catch (Throwable $e) {
            Yii::error($e, __METHOD__);
        }
    }

    /**
     * @param string $service_name
     * @param Client $client
     * @param string $url
     * @param string $body
     * @param bool   $save_log
     *
     * @return string
     * @throws Exception
     */
    protected function sendPost(string $service_name, Client $client, string $url, string $body, bool $save_log): string
    {
        if ($save_log) {
            $this->saveLog($service_name, $body);
        }

        try {
            $response = $client->post($url, [
                'proxy' => false,
                'body'  => $body,
            ]);
        } catch (ClientException $e) {
            $this->saveLog($service_name, $e->getMessage(), false, true);

            throw new Exception(
                sprintf('нет соединения с сервером %s, код ошибки: %d', $service_name, $e->getCode())
            );
        }

        $content = $response->getBody()->getContents();

        if ($save_log) {
            $this->saveLog($service_name, $content, false);
        }

        return $content;
    }

    protected function getSessionID(): string
    {
        try {
            return Uuid::uuid4();
        } catch (\Exception $e) {
            return (string)time();
        }
    }
}
