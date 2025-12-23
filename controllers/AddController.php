<?php

namespace app\controllers;

use app\models\Category;
use app\models\File;
use app\models\Task;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class AddController extends Controller
{
    
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'customer') {
            return $this->redirect(['/']);
        }

        $categories = Category::find()->all();
        $categoryList = ArrayHelper::map($categories, 'id', 'name');


        $task = new Task();
        $uploadPath = Yii::getAlias('@app/uploads/');


        if (Yii::$app->request->isAjax && $task->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($task);
        }

        if (Yii::$app->request->getIsPost()) {

            $task->load(Yii::$app->request->post());

            $task->status = 'new';
            $task->city_id = 777;
            $task->customer_id = Yii::$app->user->identity->id;

            $task->deadline = date(
                'Y-m-d H:i:s',
                strtotime($task->deadline)
            );
    
            if ($task->validate()) {
                $task->save(false);

                $task->files = UploadedFile::getInstances($task, 'files');
                    
                foreach ($task->files as $file) {
                    if ($file->tempName && is_uploaded_file($file->tempName)) {
                        $fileName = uniqid() . '.' . $file->extension;
                        $file->saveAs($uploadPath . $fileName);

                        $fileRecord = new File();
                        $fileRecord->task_id = $task->id;
                        $fileRecord->file = $fileName;
                        $fileRecord->save(false);
                    }
                }

                return $this->redirect(['/task/view/' . $task->id]);
            }

        }

        return $this->render('index', [
            'task' => $task,
            'categoryList' => $categoryList
        ]);
    }
}