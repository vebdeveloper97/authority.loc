<?php

namespace app\modules\api\models;

use app\models\User;
use app\modules\api\resources\UserResources;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user This property is read-only.
 *
 */
class SignupForm extends \app\models\LoginForm
{
    public $username;
    public $password;
    public $password_repeat;
    public $assess_token;

    public $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [
                'username','unique',
                'message' => 'Username bir xil bo\'lishi mumkin emas!',
                'targetClass' => 'app\modules\api\resources\UserResources',
            ],
            [['username', 'password', 'password_repeat'], 'required'],
            ['password', 'compare', 'compareAttribute' => 'password_repeat'],
            [['assess_token'], 'string']
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function register()
    {
        $this->_user = new UserResources();
        if ($this->validate()) {
            $security = Yii::$app->security;
            $this->_user->username = $this->username;
            $this->_user->password = $security->generatePasswordHash($this->password);
            $this->_user->assess_token = $security->generateRandomString(255);

            if($this->_user->save()){
                return true;
            }
            return false;
        }
        return false;
    }

}
