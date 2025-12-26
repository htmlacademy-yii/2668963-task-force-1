<?php

declare(strict_types=1);
namespace HtmlAcademy\enums;

enum OfferStatus: string {
    case NEW = 'new';
    case CONFIRM = 'confirm';
    case DENY = 'deny';
    case FAILED  = 'failed';
    case COMPLETED  = 'completed';


    public function label(): string
    {
        return match($this) {
            self::NEW => 'Новый',
            self::CONFIRM => 'Принять',
            self::DENY => 'Отказать',
            self::FAILED => 'Провалено',
            self::COMPLETED => 'Завершено',
        };
    }
}