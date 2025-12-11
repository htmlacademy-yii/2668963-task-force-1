<?php

namespace app\controllers;

use app\models\Task;
use yii\web\Controller;

class TaskController extends Controller
{
    public function actionIndex()
    {
        $tasks = Task::find()
        ->where(['status' => 'new'])
        ->orderBy(['date_add' => SORT_DESC])
        ->all();
        
        return $this->render('index', [
            'tasks' => $tasks,
        ]);
    }

    
}