<?php 

declare(strict_types=1);
namespace HtmlAcademy\core;
use HtmlAcademy\actions\Cancel;
use HtmlAcademy\actions\Respons;
use HtmlAcademy\actions\Done;
use HtmlAcademy\actions\Reject;

class Task 
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_INPROGRESS = 'in_progress';
    const STATUS_COMPLETE = 'complete';
    const STATUS_FAILED = 'failed';

    // const ACTION_CANCEL = 'action_cancel';
    // const ACTION_RESPONSE = 'action_response';
    // const ACTION_DONE = 'action_done';
    // const ACTION_REJECT = 'action_reject';

    public int $customerId;
    public int $performerId;

    public string $currentStatus;

    public function __construct($customerId, $performerId)
    {
        $this->customerId = $customerId;
        $this->performerId = $performerId;
    }

    public function statusMatchingTranslate($status)
    {
        $match = [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELED => 'Отменено',
            self::STATUS_INPROGRESS => 'В работе',
            self::STATUS_COMPLETE => 'Выполнено',
            self::STATUS_FAILED => 'Провалено'
        ];

        return $match[$status];
    }

    // public function actionMatchingTranslate($action)
    // {
    //     $match = [
    //         self::ACTION_CANCEL => 'Отменить',
    //         self::ACTION_RESPONSE => 'Откликнуться',
    //         self::ACTION_DONE => 'Завершить',
    //         self::ACTION_REJECT => 'Отказаться'
    //     ];

    //     return $match[$action];
    // }

    public function getNextStatus($action)
    {
        switch ($action) {
            case ($action instanceof Cancel):
                return self::STATUS_CANCELED;
                break;
            case ($action instanceof Respons):
                return self::STATUS_INPROGRESS;
                break;
            case ($action instanceof Done):
                return self::STATUS_COMPLETE;
                break;
            case ($action instanceof Reject):
                return self::STATUS_FAILED;
                break;
            default:
                return null;
        }
    }

    public function getAvailableAction($userId)
    {
        switch ($this->currentStatus) {
            case self::STATUS_NEW:
                return [new Cancel, new Respons];
                break;
            case self::STATUS_INPROGRESS:
                return [new Done, new Reject];
                break;
            default:
                return [];
        }
    }

    public function setStatus($status)
    {
        $this->currentStatus = $status;
    }
    
    public function getStatus()
    {
        return $this->currentStatus;
    }
}