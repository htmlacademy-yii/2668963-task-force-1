<?php

namespace app\controllers;

use app\models\City;
use app\models\User;
use Yii;
use yii\web\Controller;

class SignupController extends Controller
{

    public function beforeAction($action)
    {
        return true;
    }

    public function actionIndex()
    {

        $cities = City::find()->select(['id', 'name'])->all();

        $user = new User();
        if (Yii::$app->request->getIsPost()) {
            $user->load(Yii::$app->request->post());
            if ($user->validate()) {
                //save
                $user->password = Yii::$app->security->generatePasswordHash($user->password);
                $user->save(false);

                return $this->redirect(['site/index']);
            }
        }
        return $this->render('index', [
            'model' => $user,
            'cities' => $cities
        ]);
    }
}