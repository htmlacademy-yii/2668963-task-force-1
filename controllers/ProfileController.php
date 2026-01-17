<?php

namespace app\controllers;

use app\models\Category;
use app\models\ChangePasswordForm;
use app\models\Specialization;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $model = Yii::$app->user->identity;
        $categories = Category::find()->all();
        $passwordForm = new ChangePasswordForm();


        $model->category_ids = ArrayHelper::getColumn($model->specializations, 'category_id');

        if ($model->load(Yii::$app->request->post())) {
            $oldAvatar = $model->avatar;
            $model->avatarFile = UploadedFile::getInstance($model, 'avatarFile');

            if ($model->avatarFile) {
                $path = 'img/avatars/' . $model->id . 'n.' . $model->avatarFile->extension;
                $model->avatarFile->saveAs($path);
                $model->avatar = $path;
            } else {
                $model->avatar = $oldAvatar;
            }

            if ($model->save()) {
                Specialization::deleteAll(['performer_id' => $model->id]);

                if (!empty($model->category_ids)) {
                    foreach ($model->category_ids as $catId) {
                        $spec = new Specialization();
                        $spec->performer_id = $model->id;
                        $spec->category_id = $catId;
                        $spec->save();
                    }
                }

                return $this->refresh();
            }
        }

        return $this->render('index', [
            'model' => $model,
            'categories' => $categories,
            'passwordForm' => $passwordForm,
        ]);
    }


    public function actionChangePassword()
    {
        $passwordForm = new ChangePasswordForm();

        if ($passwordForm->load(Yii::$app->request->post()) && $passwordForm->change()) {
            Yii::$app->session->setFlash('success', 'Пароль изменён');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка смены пароля');
        }

        return $this->redirect(['index']);
    }
}