<?php

namespace app\modules\api\jsonrpc\controllers;

use app\modules\api\common\controllers\BaseController;

class MasterController extends BaseController
{
    public function actionIndex(): array
    {
        return [
            'status' => 'received',
        ];
    }
}
