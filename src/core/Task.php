<?php 

declare(strict_types=1);
namespace HtmlAcademy\core;
use HtmlAcademy\actions\Cancel;
use HtmlAcademy\actions\Respons;
use HtmlAcademy\actions\Done;
use HtmlAcademy\actions\Reject;
use HtmlAcademy\enums\TaskActions;
use HtmlAcademy\enums\TaskStatus;
use HtmlAcademy\exceptions\TaskActionsException;
use HtmlAcademy\exceptions\TaskStatusException;

class Task 
{
    public int $customerId;
    public int $performerId;

    public ?string $currentStatus = null;

    public function __construct($customerId, $performerId)
    {
        $this->customerId = $customerId;
        $this->performerId = $performerId;
    }

    public function statusGetName(string $status): string
    {
        TaskStatus::from($status);
        return TaskStatus::from($status)->label();
    }

    public function getNextStatus(string $action): ?string
    {
        try {
            $enumAction = TaskActions::from($action);
        }catch (\ValueError $e) {
            throw new TaskActionsException("Действия '$action' не существует");
        }
        switch ($enumAction->value) {
            case (TaskActions::CANCEL->value):
                return TaskStatus::CANCELED->value;
                break;
            case (TaskActions::RESPONSE->value):
                return TaskStatus::INPROGRESS->value;
                break;
            case (TaskActions::DONE->value):
                return TaskStatus::COMPLETE->value;
                break;
            case (TaskActions::REJECT->value):
                return TaskStatus::FAILED->value;
                break;
            default:
                return null;
        }
    }

    public function getAvailableAction(): array
    {
        if ($this->currentStatus === null) {
            throw new TaskActionsException("Нет доступного действия, статус не установлен");
        }
        switch ($this->currentStatus) {
            case TaskStatus::NEW->value:
                return [new Cancel, new Respons];
                break;
            case TaskStatus::INPROGRESS->value:
                return [new Done, new Reject];
                break;
            default:
                return [];
        }
    }

    public function setStatus(string $status): void
    {
        try {
            $enumStatus = TaskStatus::from($status);
        } catch (\ValueError $e) {
            throw new TaskStatusException("Статус '$status' не существует");
        }
        $this->currentStatus = $enumStatus->value;
    }
    
    public function getStatus(): string
    {
        if ($this->currentStatus === null) {
            throw new TaskStatusException("Статус не установлен");
        }
        return $this->currentStatus;
    }
}