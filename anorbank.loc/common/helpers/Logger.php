<?php

namespace common\helpers;

use Yii;
use DateTime;
use Throwable;
use yii\helpers\FileHelper;

/**
 * Class Logger
 *
 * @package common\helpers
 */
class Logger
{
    /**
     * @param string $file_name
     * @param        $uid
     * @param        $flag
     * @param string $data
     */
    public static function saveToFile(string $file_name, $uid, $flag, string $data): void
    {
        try {
            $folder = Yii::getAlias('@root/runtime/logs/' . date('Y/m/d'));

            if (!file_exists($folder)) {
                FileHelper::createDirectory($folder);
            }

            $text = (new DateTime())->format('Y-m-d H:i:s.u') . ' ' . $flag . ' ' . $uid . ' ' . $data;

            file_put_contents("$folder/$file_name", $text . "\n", FILE_APPEND);
        } catch (Throwable $e) {
            Yii::error($e, __METHOD__);
        }
    }
}
