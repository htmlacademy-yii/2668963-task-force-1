<?php

namespace app\controllers;

use Yii;
use app\models\Category;
use app\models\Offer;
use app\models\Review;
use app\models\Task;
use app\models\TaskFilterForm;
use HtmlAcademy\enums\OfferStatus;
use HtmlAcademy\enums\TaskStatus;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class TaskController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }
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

                $tasks = $query->orderBy(['date_add' => SORT_DESC])->all();

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

    /**
     *  Открытие задания
     */
    public function actionView($id) 
    {
        $newOffer = new Offer();
        $review = new Review();

        $task = Task::findOne($id);

        if ($task === null) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        if (Yii::$app->user->identity->id === $task->customer_id) {
            $offers = Offer::find()
            ->innerJoin('tasks')
            ->where(['offers.task_id' => $id])
            ->orderBy([new \yii\db\Expression("offers.status = 'confirmed' DESC"), 'offers.date_add' => SORT_DESC])
            ->all();
            $isCustomer = true;

        } elseif (Yii::$app->user->identity->id !== $task->customer_id) {
            $offers = Offer::find()
            ->innerJoin('tasks')
            ->where(['offers.task_id' => $id])
            ->andWhere(['offers.performer_id' => Yii::$app->user->identity->id])
            ->all();
            $isCustomer = false;
        }

        $currentUserId = Yii::$app->user->id ?? null;
        $userHasOffer = $currentUserId
            ? Offer::find()->where(['task_id' => $task->id, 'performer_id' => $currentUserId])->exists()
            : false;

        return $this->render('view', [
            'task' => $task,
            'offers' => $offers,
            'isCustomer' => $isCustomer,
            'userHasOffer' => $userHasOffer,
            'newOffer' => $newOffer,
            'review' => $review,
        ]);
    }
    
    /**
     *  Отмена задания заказчиком
     */
    public function actionCancel($taskId)
    {
        $task = Task::findOne($taskId);

        if (!$task->cancel(Yii::$app->user->id, $taskId)) {
            Yii::$app->session->setFlash('error', 'Нельзя удалить');
        }

        return $this->redirect(['view', 'id' => $taskId]);
    }

    /**
     *  Приём задания заказчиком
     */
    public function actionCompletion($taskId)
    {
        $task = Task::findOne($taskId);
        $review = new Review();

        $review->task_id = $taskId;
        $review->customer_id = $task->customer_id;
        $review->performer_id = $task->performer_id;

        Offer::updateAll(
            ['status' => OfferStatus::COMPLETED->value],
            ['and', 
                ['task_id' => $task->id], 
                ['status' => OfferStatus::CONFIRM->value],
            ]
        );
     
        $task->status = TaskStatus::COMPLETE->value;
        $task->save(false);


        if ($review->load(Yii::$app->request->post()) && $review->validate()) {


            if ($review->save()) {
                Yii::$app->session->setFlash('success', 'Задание закрыто');
                return $this->redirect(['task/view', 'id' => $taskId]);
            } else {
                Yii::error($review->errors);
            }
        }

        return $this->redirect(['view', 'id' => $taskId]);
    }

    /**
     * Создание оффера исполнителем
     */
    public function actionCreateOffer($taskId)
    {
        $newOffer = new Offer();

        $newOffer->task_id = $taskId;
        $newOffer->performer_id = Yii::$app->user->id;
        $newOffer->status = OfferStatus::NEW->value;

        if ($newOffer->load(Yii::$app->request->post()) && $newOffer->validate()) {


            if ($newOffer->save()) {
                Yii::$app->session->setFlash('success', 'Офер создан');
                return $this->redirect(['task/view', 'id' => $taskId]);
            } else {
                Yii::error($newOffer->errors);
            }
        }
        
        if ($newOffer->save()) {
            echo "Saved!";
        } else {
            var_dump($newOffer->errors);
        }
        exit;

        return $this->redirect(['task/view', 'id' => $taskId]);
    }
    
    /**
     * Принятие оффера заказчиком
     */
    public function actionAccept($taskId, $offerId)
    {
        $task = Task::findOne($taskId);

        if (!$task || !$task->accept(Yii::$app->user->id, $offerId)) {
            throw new ForbiddenHttpException();
        }

        return $this->redirect(['view', 'id' => $taskId]);
    }

    /**
     * Отказ оффера заказчиком
     */
    public function actionReject($taskId, $offerId)
    {
        $task = Task::findOne($taskId);

        if (!$task->reject(Yii::$app->user->id, $offerId)) {
            Yii::$app->session->setFlash('error', 'Нельзя отказать');
        }

        return $this->redirect(['view', 'id' => $taskId]);
    }

    /**
     *  Отмена задания исполнителем
     */
    public function actionFail($taskId)
    {
        $task = Task::findOne($taskId);

        Offer::updateAll(
            ['status' => OfferStatus::FAILED->value],
            ['and', 
                ['task_id' => $task->id], 
                ['status' => OfferStatus::CONFIRM->value],
            ]
        );
     
        $task->status = TaskStatus::FAILED->value;
        $task->save(false);

        return $this->redirect(['view', 'id' => $taskId]);
    }
}