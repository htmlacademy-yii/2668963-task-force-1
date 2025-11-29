<?php

declare(strict_types=1);
namespace HtmlAcademy\enums;

enum TaskStatus: string {
    case NEW = 'new';
    case CANCELED = 'canceled';
    case INPROGRESS = 'in_progress';
    case COMPLETE = 'complete';
    case FAILED = 'failed';

    public function label(): string
    {
        return match($this) {
            self::NEW => 'Новое',
            self::CANCELED => 'Отменено',
            self::INPROGRESS => 'В работе',
            self::COMPLETE => 'Выполнено',
            self::FAILED => 'Провалено',
        };
    }
}