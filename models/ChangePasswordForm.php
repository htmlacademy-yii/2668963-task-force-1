<?php

namespace app\models;

use Yii;
use yii\base\Model;


class ChangePasswordForm extends Model
{
    public $password;
    public $password_repeat;

    public function rules()
    {
        return [
            [['password', 'password_repeat'], 'required'],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function change()
    {

        $user = User::findOne(Yii::$app->user->id);
        $user->password = Yii::$app->security->generatePasswordHash($this->password);

        return $user->save(false);
    }
}