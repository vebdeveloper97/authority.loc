<?php

/** @noinspection PhpMissingFieldTypeInspection */

namespace app\modules\api;

/**
 * Class Module
 *
 * @package app\modules\api
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\api\common\controllers';

    public function init(): void
    {
        parent::init();

        $this->modules = [
            'v1'      => v1\Module::class,
            'jsonrpc' => jsonrpc\Module::class,
        ];
    }
}
