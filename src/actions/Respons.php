<?php

declare(strict_types=1);
namespace HtmlAcademy\actions;
use HtmlAcademy\enums\TaskActions;

class Respons extends Action
{

    public function getName(): string
    {
        return TaskActions::RESPONSE->label();
    }
    public function getCode(): string
    {
        return TaskActions::RESPONSE->value;
    }
    public function checkPermissions($customerId, $performerId, $userId): bool
    {
        return $userId === $performerId && $userId !== $customerId;
    }

}