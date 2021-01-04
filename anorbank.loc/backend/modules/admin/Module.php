<?php

/** @noinspection PhpMissingFieldTypeInspection */

namespace backend\modules\admin;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\admin\controllers';
    public $layout = '@backend/modules/admin/views/layouts/main.php';

    public function init(): void
    {
        parent::init();

        $this->modules = [
            'user'     => \backend\modules\user\Module::class,
            'rabbitmq' => \common\modules\rabbitmq\Module::class,
            'logger'   => \backend\modules\logger\Module::class,
        ];
    }
}
