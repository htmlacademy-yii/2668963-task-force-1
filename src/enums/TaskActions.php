<?php

declare(strict_types=1);
namespace HtmlAcademy\enums;

enum TaskActions: string {
    case CANCEL = 'cancel';
    case RESPONSE = 'response';
    case DONE = 'done';
    case REJECT = 'reject';

    public function label(): string
    {
        return match($this) {
            self::CANCEL => 'Отменить',
            self::RESPONSE => 'Откликнуться',
            self::DONE => 'Завершить',
            self::REJECT => 'Отказаться',
        };
    }
}