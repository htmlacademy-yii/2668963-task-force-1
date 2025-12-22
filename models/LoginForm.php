<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;

    private $_user;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['password', 'validatePassword'],
        ];
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['email' => $this->email]);
        }
        return $this->_user;
    }

    public function validatePassword($attribute)
    {
        if ($this->hasErrors()) {
            return;
        }
        $user = $this->getUser();

        if (!$user || !Yii::$app->security->validatePassword($this->password, $user->password)) {
            $this->addError($attribute, 'Неправильный email или пароль');
        }

    }
}