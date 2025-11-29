<?php

declare(strict_types=1);
namespace HtmlAcademy\actions;
use HtmlAcademy\enums\TaskActions;

class Reject extends Action
{

    public function getName(): string
    {
        return TaskActions::REJECT->label();
    }
    public function getCode(): string
    {
        return TaskActions::REJECT->value;
    }
    public function checkPermissions($customerId, $performerId, $userId): bool
    {
        return $userId === $performerId && $userId !== $customerId;
    }

}