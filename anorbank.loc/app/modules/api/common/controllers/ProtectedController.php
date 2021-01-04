<?php

namespace app\modules\api\common\controllers;

use yii\filters\auth\HttpBearerAuth;

class ProtectedController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        return $behaviors;
    }
}
