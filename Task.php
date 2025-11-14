<?php 

class Task 
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_INPROGRESS = 'in_progress';
    const STATUS_COMPLETE = 'complete';
    const STATUS_FAILED = 'failed';

    const ACTION_CANCEL = 'action_cancel';
    const ACTION_RESPONSE = 'action_response';
    const ACTION_DONE = 'action_done';
    const ACTION_REJECT = 'action_reject';

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

    public function actionMatchingTranslate($action)
    {
        $match = [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_RESPONSE => 'Откликнуться',
            self::ACTION_DONE => 'Завершить',
            self::ACTION_REJECT => 'Отказаться'
        ];

        return $match[$action];
    }

    public function getNextStatus($action)
    {
        switch ($action) {
            case self::ACTION_CANCEL:
                return self::STATUS_CANCELED;
                break;
            case self::ACTION_RESPONSE:
                return self::STATUS_INPROGRESS;
                break;
            case self::ACTION_DONE:
                return self::STATUS_COMPLETE;
                break;
            case self::ACTION_REJECT:
                return self::STATUS_FAILED;
                break;
            default:
                return null;
        }
    }

    public function getAvailableAction()
    {
        switch ($this->currentStatus) {
            case self::STATUS_NEW:
                return [self::ACTION_RESPONSE, self::ACTION_CANCEL];
                break;
            case self::STATUS_INPROGRESS:
                return [self::ACTION_DONE, self::ACTION_REJECT];
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