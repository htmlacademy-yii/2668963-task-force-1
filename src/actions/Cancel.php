<?php

declare(strict_types=1);
namespace HtmlAcademy\actions;
use HtmlAcademy\enums\TaskActions;

class Cancel extends Action
{

    public function getName(): string
    {
        return TaskActions::CANCEL->label();
    }
    public function getCode(): string
    {
        return TaskActions::CANCEL->value;
    }
    public function checkPermissions($customerId, $performerId, $userId): bool
    {
        return $userId === $customerId && $userId !== $performerId;
    }

}