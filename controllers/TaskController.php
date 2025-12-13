<?php

namespace app\controllers;

use Yii;
use app\models\Category;
use app\models\Task;
use app\models\TaskFilterForm;
use yii\web\Controller;

class TaskController extends Controller
{
    public function actionIndex()
    {
        $filterForm = new TaskFilterForm();

        if (Yii::$app->request->isGet) {            
            
            $query = Task::find();

            if ($filterForm->load(Yii::$app->request->get(), '')) {

                if (!empty($filterForm->categories)) {
                    $query->andWhere(['category_id' => $filterForm->categories]);
                }
                if ($filterForm->withoutPerformer) {
                    $query->andWhere(['performer_id' => NULL]);
                }
                if ($filterForm->creationTime) {
                    $fromTime = date(
                        'Y-m-d H:i:s',
                        time() - $filterForm->creationTime * 3600
                    );
                    $query->andWhere(['>=', 'date_add', $fromTime]);
                }

                $tasks = $query->all();

            } else {
                $tasks = Task::find()
                    ->where(['status' => 'new'])
                    ->orderBy(['date_add' => SORT_DESC])
                    ->all();
            }
        }

        $availableCategories = Category::find()
            ->innerJoinWith('tasks')
            ->where(['tasks.status' => 'new'])
            ->groupBy(Category::tableName() . '.id')
            ->all();


        return $this->render('index', [
            'tasks' => $tasks,
            'availableCategories' => $availableCategories,
            'filterForm' => $filterForm,
        ]);
    }
}