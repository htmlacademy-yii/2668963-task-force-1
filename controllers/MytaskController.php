<?php

namespace app\controllers;

use app\models\Task;
use HtmlAcademy\enums\TaskStatus;
use Yii;
use yii\web\Controller;

class MytaskController extends Controller 
{
    public function actionIndex() {

        $user = Yii::$app->user->identity;

        if ($user->role === 'customer') {
            $newTasks = Task::find()
                ->where(['customer_id' => $user->id])
                ->andWhere(['status' => TaskStatus::NEW])
                ->orderBy(['date_add' => SORT_DESC])
                ->all();
            
            $inProgressTasks = Task::find()
                ->where(['customer_id' => $user->id])
                ->andWhere(['status' => TaskStatus::INPROGRESS])
                ->orderBy(['date_add' => SORT_DESC])
                ->all();

            $closedTasks = Task::find()
                ->where(['customer_id' => $user->id])
                ->andWhere(['status' => [TaskStatus::CANCELED, TaskStatus::COMPLETE]])
                ->orderBy(['date_add' => SORT_DESC])
                ->all();
        }

        if ($user->role === 'performer') {
            $newTasks = Task::find()
                ->innerJoinWith('offers')
                ->where(['offers.performer_id' => $user->id])
                ->andWhere(['tasks.status' => TaskStatus::NEW])
                ->orderBy(['tasks.date_add' => SORT_DESC])
                ->all();
            
            $inProgressTasks = Task::find()
                ->where(['performer_id' => $user->id])
                ->andWhere(['status' => TaskStatus::INPROGRESS])
                ->orderBy(['date_add' => SORT_DESC])
                ->all();

            $closedTasks = Task::find()
                ->where(['performer_id' => $user->id])
                ->andWhere(['status' => [TaskStatus::FAILED, TaskStatus::COMPLETE]])
                ->orderBy(['date_add' => SORT_DESC])
                ->all();
        }


        return $this->render('index', [
            'newTasks' => $newTasks ?? null,
            'inProgressTasks' => $inProgressTasks ?? null,
            'closedTasks' => $closedTasks ?? null,
        ]);
    }
}