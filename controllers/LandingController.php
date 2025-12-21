<?php
namespace app\controllers;
use app\models\LoginForm;
use Yii;
use yii\widgets\ActiveForm;
use yii\web\Controller;
use yii\web\Response;

class LandingController extends Controller
{    
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/task']);
        }

        $model = new LoginForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->user->login($model->getUser());
            return $this->redirect(['/task']);
        }

        return $this->renderAjax('index', [
            'model' => $model,
        ]);
    }
}