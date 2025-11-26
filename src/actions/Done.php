<?php

declare(strict_types=1);
namespace HtmlAcademy\actions;

class Done extends Action
{

    public function getName(): string
    {
        return "Завершить";
    }
    public function getCode(): string
    {
        return "action_done";
    }
    public function checkPermissions($customerId, $performerId, $userId): bool
    {
        return $userId === $customerId && $userId !== $performerId;
    }

}