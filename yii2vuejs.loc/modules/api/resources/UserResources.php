<?php
namespace app\modules\api\resources;

use app\models\User;

class UserResources extends User{


    public function fields()
    {
        return ['username', 'id', 'password', 'assess_token'];
    }
}