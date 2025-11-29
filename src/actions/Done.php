<?php

declare(strict_types=1);
namespace HtmlAcademy\actions;
use HtmlAcademy\enums\TaskActions;

class Done extends Action
{

    public function getName(): string
    {
        return TaskActions::DONE->label();
    }
    public function getCode(): string
    {
        return TaskActions::DONE->value;
    }
    public function checkPermissions($customerId, $performerId, $userId): bool
    {
        return $userId === $customerId && $userId !== $performerId;
    }

}