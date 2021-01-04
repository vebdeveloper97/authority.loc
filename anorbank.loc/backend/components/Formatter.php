<?php

namespace backend\components;

use JsonException;

class Formatter extends \yii\i18n\Formatter
{
    /**
     * @param array $value
     *
     * @return false|string
     * @throws JsonException
     */
    public function asJson(array $value)
    {
        return json_encode($value, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
