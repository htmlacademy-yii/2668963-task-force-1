<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\User;

class UserController extends Controller
{
    public function actionIndex()
    {
        // $users = User::find()->all();

        return $this->render('index', [
            // 'users' => $users,
        ]);
    }

    public function actionView($id)
    {
        $user = User::findOne($id);

        if ($user === null) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return $this->render('view', [
            'user' => $user,
        ]);
    }
}
