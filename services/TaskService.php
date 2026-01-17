<?php

namespace app\services;

use app\models\Offer;
use app\models\Task;
use HtmlAcademy\enums\OfferStatus;
use HtmlAcademy\enums\TaskStatus;

class TaskService{
    /**
     * Принятие оффера заказчиком
     */
    public function accept(int $userId, int $taskId, int $offerId): bool
    {   
        $task = Task::findOne([
            'id' => $taskId,
        ]);

        if (!$task->canBeChangedBy($userId)) {
            return false;
        }
        if ($task->status !== TaskStatus::NEW->value) {
            return false;
        }


        $offer = Offer::findOne([
            'id' => $offerId,
            'task_id' => $task->id
        ]);

        if (!$offer) {
            return false;
        }

        $task->performer_id = $offer->performer_id;
        $task->status = TaskStatus::INPROGRESS->value;

        $offer->status = OfferStatus::CONFIRM->value;
        $offer->save(false);
        
        Offer::updateAll(
            ['status' => OfferStatus::DENY->value],
            ['and', ['task_id' => $task->id], ['!=', 'id', $offerId]]
        );


        return $task->save(false);
    }

    /**
     * Отказ оффера заказчиком
     */    
    public function reject($userId, $taskId, $offerId): bool
    {
        $task = Task::findOne([
            'id' => $taskId,
        ]);
        if (!$task->canBeChangedBy($userId)) {
            return false;
        }

        $offer = Offer::findOne([
            'id' => $offerId,
            'task_id' => $task->id
        ]);

        if (!$offer) {
            return false;
        }

        $offer->status = OfferStatus::DENY->value;
        return $offer->save(false);
    }

    /**
     *  Отмена задания заказчиком
     */    
    public function cancel($userId, $taskId): bool
    {

        $task = Task::findOne([
            'id' => $taskId,
        ]);

        if (!$task) {
            return false;
        }

        if (!$task->canBeChangedBy($userId)) {
            return false;
        }

        Offer::updateAll(
            ['status' => OfferStatus::DENY->value],
            ['and', ['task_id' => $task->id]]
        );
        
        $task->status = TaskStatus::CANCELED->value;
        return $task->save(false);
    }
}